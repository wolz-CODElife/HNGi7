<?php
/**
 * PHP/cURL function to check a web site traffic. If the website is unreachable, ERRNO is thrown.
 * @param string $url URL that must be checked
 */

function url_test($url) {
  $url = urldecode($url);
  
  //$outputs holds json's object
  $outputs = array();

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch) !== false) {
        $info = curl_getinfo($ch);
        $outputs[$url] = array(
           'lookupTime' => ($info['namelookup_time'] * 1000) . 'ms',
           'connectTime' => ($info['connect_time'] * 1000) . 'ms',
           'pretransferTime' => ($info['pretransfer_time']) . 'ms',
           'redirectTime' => ($info['redirect_time'] * 1000) . 'ms',
           'startTransferTime' => ($info['starttransfer_time'] * 1000) . 'ms',
           'totalTime' => ($info['total_time'] * 1000) . 'ms',
        );
        return json_encode($outputs);
    } else {
        echo 'Error: ' . curl_error($ch);
    }
  curl_close( $ch );
}




//A sample run on the script function
if (isset($_GET['url'])) {
  $website =  urlencode($_GET['url']);
}
else{
  $website =  urlencode("https://www.google.com");
}
echo (url_test($website));


?>
