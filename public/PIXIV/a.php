<?php
require "../PHP/curl.php";

$curl = new Curl();
$curl->addCookieString("first_visit_datetime_pc=2023-04-07+03:07:25; p_ab_id=4; p_ab_id_2=1; p_ab_d_id=2068035057; yuid_b=JYJIAAA; _gcl_au=1.1.1741549242.1680880163; _fbp=fb.1.1680880165048.772445486; __cf_bm=Xi6F0Dk7iGlqsFu_NmDSIt25pRYnnYGfDu9YmoN4HKk-1680951176-0-AZjuHryo+ejvJ/h3zzajXXCR8JOyD/CDPV6pgfBFXdkPXG5XGlBzE6Pfj+wcqP2socGosW0w45MU6w2ps0qf56rHFEj0Mjv6PK1UqrekPKUfg/jNIxN1wCP1ayxS5SJwJjyf7mXKw+D7qjIOmWGDOlZ3V1n57GAz+BhE8YReEmZ2UF9J2t/wXSzOMPs8EAJAmA==; _ga_75BBYNYN9J=GS1.1.1680951175.3.0.1680951177.0.0.0; _gid=GA1.2.179708455.1680951178; _gat_UA-1830249-3=1; _ga=GA1.2.1647476922.1680880163; _gat_gtag_UA_76252338_1=1; PHPSESSID=80697987_pBJl99KFU0ycIrlUcnWtLj6NfTJ1ffkK; device_token=36e3d3cb537637b2c4dfebf5fc801895; privacy_policy_agreement=5; _ga_MZ1NL4PHH0=GS1.1.1680951178.1.0.1680951196.0.0.0");
$curl->setProxy("127.0.0.1", "7890", CURLPROXY_HTTP);
$curl->setReturnTransfer(true);
$curl->stopSSL();
$curl->setUserAgent("Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36 Edg/112.0.1722.34");
$curl->get("https://www.pixiv.net/ranking.php?format=json&mode=daily_r18");
print_r($curl->response);
?>