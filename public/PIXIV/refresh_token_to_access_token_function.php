<?php
error_reporting(E_ALL & ~E_NOTICE); 
include_once "../PHP/curl.php";

function refresh_token_to_access_token($refresh_token) {
    // $refresh_token = "hyiyg8wJ5LSF1LDkz8XWt3rgUz11mdzGc4UiTmdgWKE";
    $PROXY = "127.0.0.1:7890";

    $data = array("client_id"=> "MOBrBDS8blbauoSck0ZfDbtuzpyT", "client_secret"=> "lsACyCD94FhDUtGTXi3QzcFE2uU1hqtDaKeqrdwj", "grant_type"=> "refresh_token", "include_policy"=> "true", "refresh_token"=> $refresh_token);
    
    $curl = new Curl();
    $headers = array("user-agent" => "PixivIOSApp/7.13.3 (iOS 14.6; iPhone13,2)", "app-os-version" => "14.6", "app-os" => "ios");
    $curl->addHeaderArray($headers);
    $curl->setTimeOut(15);
    $curl->setProxy("127.0.0.1", "7890", CURLPROXY_HTTP);
    $curl->stopSSL();
    $curl->setReturnTransfer(true);
    $curl->post("https://oauth.secure.pixiv.net/auth/token", $data);

    return json_decode($curl->response, true)['access_token'];
}
?>