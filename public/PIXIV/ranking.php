<?php
require "../PHP/curl.php";

$PROXYIP = "127.0.0.1";
$PROXYPORT = "7890";
$PROXY_PIXIVURL = "pixiv.re";
$curl = new Curl();
$curl->addCookieString("first_visit_datetime_pc=2023-04-07+03:07:25; p_ab_id=4; p_ab_id_2=1; p_ab_d_id=2068035057; yuid_b=JYJIAAA; _gcl_au=1.1.1741549242.1680880163; _fbp=fb.1.1680880165048.772445486; __cf_bm=Xi6F0Dk7iGlqsFu_NmDSIt25pRYnnYGfDu9YmoN4HKk-1680951176-0-AZjuHryo+ejvJ/h3zzajXXCR8JOyD/CDPV6pgfBFXdkPXG5XGlBzE6Pfj+wcqP2socGosW0w45MU6w2ps0qf56rHFEj0Mjv6PK1UqrekPKUfg/jNIxN1wCP1ayxS5SJwJjyf7mXKw+D7qjIOmWGDOlZ3V1n57GAz+BhE8YReEmZ2UF9J2t/wXSzOMPs8EAJAmA==; _ga_75BBYNYN9J=GS1.1.1680951175.3.0.1680951177.0.0.0; _gid=GA1.2.179708455.1680951178; _gat_UA-1830249-3=1; _ga=GA1.2.1647476922.1680880163; _gat_gtag_UA_76252338_1=1; PHPSESSID=80697987_pBJl99KFU0ycIrlUcnWtLj6NfTJ1ffkK; device_token=36e3d3cb537637b2c4dfebf5fc801895; privacy_policy_agreement=5; _ga_MZ1NL4PHH0=GS1.1.1680951178.1.0.1680951196.0.0.0");
// $curl->setProxy($PROXYIP, $PROXYPORT, CURLPROXY_HTTP);
$curl->setReturnTransfer(true);
$curl->stopSSL();
$curl->setUserAgent("Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36 Edg/112.0.1722.34");

function HTML($search_page) { 
    return"<center>
            <form action='' method='get'>
                Ranking: <select name='ranking'>
                <option value='daily'>日榜</option>
                <option value='weekly'>周榜</option>
                <option value='monthly'>月榜</option>
                <option value='male'>男性</option>
                <option value='female'>女性</option>
                <option value='rookie'>新畫師</option>
                <option value='daily_r18'>日榜成人</option>
                <option value='weekly_r18'>周榜成人</option>
                <option value='male'>男性成人</option>
                <option value='female'>女性成人</option>
              </select>
                Page: <input type='text' name='page'value='$search_page'>
                Submit: <input type='submit'>
            </form>
            </center>
            <center><h6>Tip: 如果提交后页面空白, 請更改ranking, 如果不行請報告管理員</h6><br><br></center>
            "; 
}

$ranking = "";
$page = "";

if (key_exists("ranking", $_GET)) { // 判断_GET是否包含word(key)
    $ranking = urlencode($_GET["ranking"]); // 转换成url编码 构建URL时要用
} else { // 如果不包含(key)
    // echo $HELP;
    exit(HTML($page));
}
if (key_exists("page", $_GET)) { // 判断_GET是否包含page(key)
    $page = intval($_GET["page"]);
}
if ($page <= 0) {
    $page = 1;
}
if ($page % 1 != 0) {
    $page = 1;
}
$page_next = $page + 1;
$page_back = $page -1;
if ($page_back < 0) {
    $page_back = 1;
}

echo HTML($page);

$url = "https://www.pixiv.net/ranking.php?format=json&mode=$ranking&p=$page";
$url_back = "/public/PIXIV/ranking.php?ranking=$ranking&page=$page_back";
$url_next = "/public/PIXIV/ranking.php?ranking=$ranking&page=$page_next";

// exit($url);

$curl->get($url);
$response = json_decode($curl->response, true);

for($i = 0; $i < count($response["contents"]); $i++) {
    $image_url = str_replace("pximg.net", $PROXY_PIXIVURL, $response["contents"][$i]["url"]); // 图片微缩图网址
    $image_id = $response["contents"][$i]["illust_id"]; // 图片ID
    $image_page_count = $response["contents"][$i]["illust_page_count"]; // 图片数量
    $image_title = $response["contents"][$i]["title"]; // 标题
    $image_user_id = $response["contents"][$i]["user_id"]; // 用户ID
    $image_user_name = $response["contents"][$i]["user_name"]; // 用户名
    // <a> <img> square_medium </img> </a>
    // title
    // caption
    // page_count
    // id
    // <hr width='360' align='center'>
    echo "<center>";
    echo "<a href='/public/PIXIV/view_illust.php?id=$image_id&page_count=$image_page_count'><img src='$image_url'></img></a><br>";
    echo "TITLE: $image_title<br>";
    echo "PAGE COUNT: $image_page_count<br>";
    echo "USER ID: $image_user_id<br>";
    echo "USER NAME: $image_user_name<br>";
    echo "ID: $image_id<br>";
    echo "<hr width='360' align='center'><br>";
    echo "</center>";
}

echo HTML($page);
?>

<center>
    <br>
    <input type="button" onclick='location.href= ("<?echo $url_back?>")' value="上一页"/>
    <input type="button" onclick='location.href= ("/public/PIXIV/ranking.php?page=<?echo $page?>&ranking=<?echo $ranking?>")' value="当前页 (<?echo $page?>)"/>
    <input type="button" onclick='location.href= ("<?echo $url_next?>")' value="下一页"/>
</center>
