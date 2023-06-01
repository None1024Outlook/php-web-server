<?php
if (key_exists("refresh_token", $_COOKIE)) {
    
} else {
    
}
if (key_exists("token_use", $_COOKIE)) {
    if ($_COOKIE["token_use"] != true) {

    } 
} else {
    
}
?>

<?php
require "../PHP/curl.php";

$TOKEN = "hyiyg8wJ5LSF1LDkz8XWt3rgUz11mdzGc4UiTmdgWKE";
$PROXY = "127.0.0.1:7890";
$PROXY_PIXIVURL = "pixiv.re";
$curl = new Curl();
// $curl->setProxy("127.0.0.1", "7890", CURLPROXY_HTTP);
$curl->setReturnTransfer(true);
$curl->stopSSL();
$curl->setTimeOut(15);

$search_word = "";
$search_access_token = "";
$search_page = "";
function HTML($search_word, $search_access_token, $search_page) { 
    return"<center>
            <form action='' method='get'>
                Word: <input type='text' name='word' value='$search_word'>
                <!-- Access Token: <input type='text' name='access_token' value='$search_access_token'> -->
                Page: <input type='text' name='page'value='$search_page'>
                Submit: <input type='submit'>
            </form>
            </center>
            <!-- <center><h6>Tip: 如果报错(Warning)可能是因为Access Token过期或错误, 请更换一个或者向网站这提出更换默认Token请求</h6></center> -->
            <!-- <center><h6>Tip: 如果提交后没有变化, 可能是没有填写word</h6><br><br></center> -->
            <center><h6>Tip: 如果报错(Warning)可能是因为Access Token过期或错误, 请更换一个</h6></center>
            <center><h6>Tip: 如果提交后页面空白, 可能是没有填写word或access token</h6><br><br></center>
            "; 
}

// 处理表单
if (key_exists("word", $_GET)) { // 判断_GET是否包含word(key)
    $search_word = urlencode($_GET["word"]); // 转换成url编码 构建URL时要用
} else { // 如果不包含(key)
    // echo $HELP;
    exit(HTML($search_word, $search_access_token, $search_page));
}
if ($search_word == "") {  // 如果search_word为空则退出
    // echo $HELP;
    exit(HTML($search_word, $search_access_token, $search_page));
}

// token
require "./refresh_token_to_access_token_function.php";
$search_access_token = refresh_token_to_access_token($TOKEN);

if (key_exists("page", $_GET)) { // 判断_GET是否包含page(key)
    $search_page = intval($_GET["page"]);
}
if ($search_page <= 0) {
    $search_page = 1;
}
if ($search_page % 1 != 0) {
    $search_page = 1;
}
$offset = ($search_page - 1) * 30;
$page_next = $search_page + 1;
$page_back = $search_page -1;
if ($page_back < 0) {
    $page_back = 1;
}

echo HTML($search_word, $search_access_token, $search_page);

$url = "https://app-api.pixiv.net/v1/search/illust?offset=$offset&filter=for_android&word=$search_word&sort=date_desc&search_target=partial_match_for_tags&access_token=$search_access_token"; // 构建请求URL
$url_next = "/public/PIXIV/search.php?page=$page_next&word=$search_word"; // 构建请求URL next
$url_back = "/public/PIXIV/search.php?page=$page_back&word=$search_word"; // 构建请求URL back
$curl->get($url);
$response = json_decode($curl->response, true); // 结果转换为数组


for($i = 0; $i < count($response["illusts"]); $i++) {
    $image_url = str_replace("pximg.net", $PROXY_PIXIVURL, $response["illusts"][$i]["image_urls"]["square_medium"]); // 图片微缩图网址
    $image_id = $response["illusts"][$i]["id"]; // 图片ID
    $image_page_count = $response["illusts"][$i]["page_count"]; // 图片数量
    $image_title = $response["illusts"][$i]["title"]; // 标题
    $image_caption = $response["illusts"][$i]["caption"]; // 简介
    $image_user_id = $response["illusts"][$i]["user"]["id"]; // 用户ID
    $image_user_name = $response["illusts"][$i]["user"]["name"]; // 用户名
    // <a> <img> square_medium </img> </a>
    // title
    // caption
    // page_count
    // id
    // <hr width='360' align='center'>
    echo "<center>";
    echo "<a href='/public/PIXIV/view_illust.php?id=$image_id&page_count=$image_page_count'><img src='$image_url'></img></a><br>";
    echo "TITLE: $image_title<br>";
    echo "CAPTION: $image_caption<br>";
    echo "PAGE COUNT: $image_page_count<br>";
    echo "USER ID: $image_user_id<br>";
    echo "USER NAME: $image_user_name<br>";
    echo "ID: $image_id<br>";
    echo "<hr width='360' align='center'><br>";
    echo "</center>";
}

echo "<br>" . HTML($search_word, $search_access_token, $search_page);

?>

<center>
    <br>
    <input type="button" onclick='location.href= ("<?echo $url_back?>")' value="上一页"/>
    <input type="button" onclick='location.href= ("/public/PIXIV/search.php?page=<?echo $search_page?>&word=<?echo $search_word?>&access_token=<?echo $tmp?>")' value="当前页 (<?echo $search_page?>)"/>
    <input type="button" onclick='location.href= ("<?echo $url_next?>")' value="下一页"/>
    <br>
    <h6><?echo $search_access_token?></h6>
</center>
