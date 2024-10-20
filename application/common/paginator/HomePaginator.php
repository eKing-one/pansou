<?php
namespace app\common\paginator;

use think\Paginator;
use think\Request;

class HomePaginator extends Paginator
{
    protected $request;

    public function __construct($items, $total, $perPage = 15, $currentPage = null, $options = [])
    {
        parent::__construct($items, $total, $perPage, $currentPage, $options);
        $this->request = request();
    }

    public function url($page)
    {
        $params = $this->request->param();
        // 移除keyword参数
        unset($params['keyword']);
        
        // 设置当前页码
        $params['page'] = $page;
        
        // 生成新的URL
        return $this->request->baseUrl() . '?' . http_build_query($params);
    }

    public function render()
    {
        if ($this->lastPage <= 1) {
            return '';
        }

        $currentPage = $this->currentPage();
        $lastPage = $this->lastPage();
        $html = '<div class="pager-wrap">';

        // PC端分页
        $html.= '<div class="pc-pager-wrap">';
        
        // 显示首页和上一页
        if ($currentPage > 1) {
            $html.= '<a class="pager-item" href="'. $this->url(1). '">首页</a>';
            $html.= '<a class="pager-item" href="'. $this->url($currentPage - 1). '">上一页</a>';
        }

        // 页码显示逻辑
        if ($lastPage <= 6) {
            // 页码总数不超过6页时，显示所有页码
            for ($page = 1; $page <= $lastPage; $page++) {
                if ($page == $currentPage) {
                    $html.= '<a class="pager-item active" href="'. $this->url($page). '">'. $page. '</a>';
                } else {
                    $html.= '<a class="pager-item" href="'. $this->url($page). '">'. $page. '</a>';
                }
            }
        } else {
            // 页码总数超过6页时，显示部分页码和省略号
            if ($currentPage <= 3) {
                // 当前页靠前，显示前5页 + 最后一页 + 省略号
                for ($page = 1; $page <= 5; $page++) {
                    if ($page == $currentPage) {
                        $html.= '<a class="pager-item active" href="'. $this->url($page). '">'. $page. '</a>';
                    } else {
                        $html.= '<a class="pager-item" href="'. $this->url($page). '">'. $page. '</a>';
                    }
                }
                $html.= '<span class="pager-mark pager-item">...</span>';
                $html.= '<a class="pager-item" href="'. $this->url($lastPage). '">'. $lastPage. '</a>';
            } elseif ($currentPage > 3 && $currentPage < $lastPage - 2) {
                // 当前页在中间，显示第一页 + 省略号 + 前2页 + 当前页 + 后2页 + 省略号 + 最后一页
                $html.= '<a class="pager-item" href="'. $this->url(1). '">1</a>';
                $html.= '<span class="pager-mark pager-item">...</span>';

                for ($page = $currentPage - 2; $page <= $currentPage + 2; $page++) {
                    if ($page == $currentPage) {
                        $html.= '<a class="pager-item active" href="'. $this->url($page). '">'. $page. '</a>';
                    } else {
                        $html.= '<a class="pager-item" href="'. $this->url($page). '">'. $page. '</a>';
                    }
                }
                $html.= '<span class="pager-mark pager-item">...</span>';
                $html.= '<a class="pager-item" href="'. $this->url($lastPage). '">'. $lastPage. '</a>';
            } else {
                // 当前页靠后，显示第一页 + 省略号 + 最后5页
                $html.= '<a class="pager-item" href="'. $this->url(1). '">1</a>';
                $html.= '<span class="pager-mark pager-item">...</span>';
                for ($page = $lastPage - 4; $page <= $lastPage; $page++) {
                    if ($page == $currentPage) {
                        $html.= '<a class="pager-item active" href="'. $this->url($page). '">'. $page. '</a>';
                    } else {
                        $html.= '<a class="pager-item" href="'. $this->url($page). '">'. $page. '</a>';
                    }
                }
            }
        }

        // 显示下一页和末页
        if ($currentPage < $lastPage) {
            $html.= '<a class="pager-item" href="'. $this->url($currentPage + 1). '">下一页</a>';
            $html.= '<a class="pager-item" href="'. $this->url($lastPage). '">末页</a>';
        }

        $html.= '</div>'; // 结束PC分页

        // 移动端分页
        $html.= '<div class="mobile-pager-wrap">';

        // 如果当前页不是第一页，显示“上一页”
        if ($currentPage > 1) {
            $html.= '<a class="pager-item" href="'. $this->url($currentPage - 1). '">上一页</a>';
        }

        // 如果当前页不是最后一页，显示“下一页”
        if ($currentPage < $lastPage) {
            $html.= '<a class="pager-item" href="'. $this->url($currentPage + 1). '">下一页</a>';
        }

        $html.= '</div>'; // 结束移动分页
        $html.= '</div>'; // 结束分页

        return $html;
    }
}
