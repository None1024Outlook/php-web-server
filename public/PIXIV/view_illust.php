<?php
$PROXY = "127.0.0.1:7890";
$PROXY_PIXIVURL = "pixiv.re";
$CTX = stream_context_create(array(     
        'http' => array('timeout' => 5,  
                        'proxy' => $PROXY,   
                        'request_fulluri' => True
        )     
    )     
);
$PROXY_PIXIVURL = "pixiv.re";

// 处理表单
if (key_exists("id", $_GET)) {
    $view_id = $_GET["id"];
} else {
    exit("<center><h3>ID参数错误, 请返回或联系网站管理员</h3></center>");
}
if ($view_id == "") {
    exit("<center><h3>ID参数错误, 请返回或联系网站管理员</h3></center>");
}
$url = "https://www.pixiv.net/ajax/illust/$view_id";
$view_page_count = json_decode(file_get_contents($url, false, $CTX), true)["body"]["pageCount"]; // 结果转换为数组 

if ($view_page_count == 1) {
    exit("<img src='http://pixiv.re/$view_id.jpg'></img>");
} else {
    for ($i = 1; $i <= $view_page_count; $i++) {
        echo "<center><img src='http://pixiv.re/$view_id-$i.jpg'><hr></center>";
    }
}
?>