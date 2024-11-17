<?php
namespace eking\netdisk;

class LinkChecker
{
    const VERSION = "1.0.0";

    protected $url = '';


    public function __construct()
    {
        // 初始化操作，如果需要的话
    }
    
    /**
     * 发送 HTTP GET 请求
     *
     * @param string $url 请求的 URL
     * @param array $headers 请求头
     * @return array 包含响应码、响应头、响应体和错误信息的数组
     */
    protected function get($url, $headers = [])
    {
        // 初始化一个新的 cURL 会话
        $ch = curl_init();

        // 设置 URL 和其他适当的选项
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 将 curl_exec() 获取的信息以字符串返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_HEADER, true); // 包含头部信息
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(array_map(function ($k, $v) {
                return "$k: $v";
            }, array_keys($headers), $headers), ['Content-Type: application/json']));
        }

        // 忽略 SSL 证书验证（仅用于开发环境）
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // 执行请求
        $response = curl_exec($ch);

        // 检查是否有错误发生
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return [null, null, $error_msg];
        }

        // 获取响应码
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // 分离响应头和响应体
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $responseHeader = substr($response, 0, $headerSize);
        $responseBody = substr($response, $headerSize);

        // 关闭 cURL 资源，并且释放系统资源
        curl_close($ch);

        // 返回响应码、响应头、响应体
        return [$httpCode, $responseHeader, $responseBody, null];
    }

    
    /**
     * 发送 HTTP POST 请求
     *
     * @param string $url 请求的 URL
     * @param array $headers 请求头
     * @param array $data 请求体
     * @return array 包含响应码、响应头、响应体和错误信息的数组
     */
    protected function post($url, $headers, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true); // 包含头部信息
        curl_setopt($ch, CURLOPT_POST, true);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(array_map(function ($k, $v) {
                return "$k: $v";
            }, array_keys($headers), $headers), ['Content-Type: application/json']));
        }
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // 禁止自动重定向
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 禁用SSL证书验证（不推荐用于生产环境）

        // 执行请求
        $response = curl_exec($ch);
        $error = curl_error($ch);
        
        // 检查是否有错误发生
        if ($error) {
            return [null, null, null, $error];
        }

        // 获取响应码
        @$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // 分离响应头和响应体
        @$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $responseHeader = substr($response, 0, $headerSize);
        $responseBody = substr($response, $headerSize);
        curl_close($ch);

        // 返回响应码、响应头、响应体
        return [$httpCode, $responseHeader, $responseBody, null];
    }



    /**
     * 检查阿里云盘分享链接是否有效
     *
     * @param string $url 阿里云盘分享链接
     * @return bool 如果链接有效，返回 true；否则返回 false
     */
    protected function aliYunCheck($url)
    {
        $pattern = '/s\/(.*)/';
        preg_match($pattern, $url, $matches);
        $share_id = substr($matches[1], 30);
        
        $url = "https://api.aliyundrive.com/adrive/v3/share_link/get_share_by_anonymous?share_id=" . $share_id;
        $headers = [
            "User-Agent: Mozilla/5.0 (Linux; Android 11; SM-G9880) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.37 Mobile Safari/537.36",
            "Referer: https://www.aliyundrive.com/"
        ];
        $data = json_encode(["share_id" => $share_id]);
        list($code, $header, $body, $error) = $this->post($url, $headers, $data);
        $responseData = json_decode($body);
        // 检查响应数据
        if ($responseData && isset($responseData->display_name)) {
            return true;
        } else {
            return false;
        }


    }


    /**
     * 检查夸克网盘分享链接是否有效
     *
     * @param string $url 夸克网盘分享链接
     * @return bool 如果链接有效，返回 true；否则返回 false
     */
    protected function quarkCheck($url)
    {
        // $url = 'https://pan.quark.cn/s/4e4cdbad65a0';
        // 使用正则表达式从URL中提取分享ID
        preg_match('/https:\/\/pan\.quark\.cn\/s\/(\w+)[\?]?/', $url, $matches);
        // 如果没有匹配到分享ID，则返回false
        if (!$matches) {
            return false;
        }
        // 提取提取码
        $pwd_id = $matches[1];

        // 构建获取分享信息的API URL
        $apiUrl = "https://pan.quark.cn/1/clouddrive/share/sharepage/token";
        // 设置请求头，指定Referer
        $headers = [
            'Referer: https://pan.quark.cn',
        ];
        $data = json_encode(['pwd_id' => $pwd_id]);

        // 发起POST请求获取分享信息
        list($code, $header, $body, $error) = $this->post($apiUrl, $headers, $data);

        // 打印响应内容和HTTP状态码

        // 如果请求成功
        if ($body) {
            // 解析返回的JSON数据
            $r = json_decode($body, true);
            // 返回是否存在错误码（即链接是否有效）
            if ($r['code'] == 0 && $r['message'] == 'ok') {
                return true;
            } else {
                return false;
            }
        } else {
            // 如果请求失败，返回false
            return false;
        }
    }


    /**
     * 检查百度云盘分享链接是否有效
     *
     * @param string $url 百度云盘分享链接
     * @return bool 如果链接有效，返回 true；否则返回 false
     */
    protected function baiduYunCheck($url)
    {
        $headers = [
            "User-Agent" => "Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1 Edg/94.0.4606.81",
        ];

        list($code, $header, $body, $error) = $this->get($url, $headers);

        if (!preg_match('/Location: (.*)/', $header, $matches)) {
            return false;
        }

        $locationUrl = trim($matches[1]);
        $errorIndex = strpos($locationUrl, "error");
        return $errorIndex === false;
    }


    /**
     * 检查 115 网盘分享链接是否有效
     *
     * @param string $url 115 网盘分享链接
     * @return bool 如果链接有效，返回 true；否则返回 false
     */
    protected function d115check($url)
    {
        $url = "https://webapi.115.com/share/snap?share_code=" . substr($url, 18, 11);
        list($code, $header, $body, $error) = $this->get($url, []);
        if ($body === null) {
            return false;
        }
        $errorIndex = strpos($body, '"errno":4100012');
        return $errorIndex !== false;
    }


    /**
     * 检查给定的URL是否属于支持的云存储服务，并调用相应的检查方法
     *
     * @param string $url 要检查的URL
     * @return bool 如果URL属于支持的云存储服务并且有效，则返回true；否则返回false
     */
    public function checkUrl($url)
    {
        // 检查URL中是否包含阿里云盘的域名
        if (strpos($url, 'www.ali') !== false) {
            // 如果是阿里云盘的URL，调用aliYunCheck方法进行检查
            return $this->aliYunCheck($url);
        }
        // 检查URL中是否包含115网盘的域名
        elseif (strpos($url, '115.com') !== false) {
            // 如果是115网盘的URL，调用d115check方法进行检查
            return $this->d115check($url);
        }
        // 检查URL中是否包含夸克网盘的域名
        elseif (strpos($url, 'quark.cn') !== false) {
            // 如果是夸克网盘的URL，调用quarkCheck方法进行检查
            return $this->quarkCheck($url);
        }
        // 检查URL中是否包含百度网盘的域名
        elseif (strpos($url, 'baidu.com') !== false) {
            // 如果是百度网盘的URL，调用baiduyunCheck方法进行检查
            return $this->baiduYunCheck($url);
        }
        // 如果URL不属于上述任何支持的云存储服务，返回false
        else {
            return false;
        }
    }
}
