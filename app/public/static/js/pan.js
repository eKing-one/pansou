// 定义 Pan 对象，用于包含相关功能
var Pan = {};

// 搜索功能
Pan.search = function(word) {
    // 如果用户输入了关键字
    if (word) {
		
        // 将页面重定向到 "/search/{word}" URL，进行搜索
        location.href = "/search/" + word + ".html";
    }
};

// 数据收集功能
Pan.collect = function() {
    // 检查是否支持 sessionStorage，如果不支持则创建一个简易的替代对象
    var cache = window["sessionStorage"] != null ? sessionStorage : {
        "setItem": function() {},
        "getItem": function() {}
    };
	console.log(cache);

    
    // 如果当前 URL 包含 "/search/"，说明是在搜索页面
    if (location.pathname.includes("/search/")) {
        // 提取 URL 中的关键字，去除无关部分，并对其解码
        var word = decodeURIComponent(location.pathname.replace(/\/search\/|\.html/g, "")).trim();
        console.log(word);

        // 如果关键字有效，长度小于10，并且缓存中没有该词
        if (word && word.length < 10 && !cache.getItem(word)) {
            // 构建收集数据的请求 URL，包含关键字，发送统计请求
            var src = "/collect/" + word;
			console.log(src);

            // 在页面中添加一个隐藏的图片元素，发送请求以收集数据
            $("<img src='" + src + "' width='0' height='0'/>").appendTo("body");
        }
        // 将关键字存储到缓存中，防止重复统计
        cache.setItem(word, "1");
    }
};

