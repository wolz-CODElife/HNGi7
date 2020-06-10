<?php

/**
 * PHP/cURL function to check a web site status. If HTTP status is not 200 or 302, or
 * the requests takes longer than 10 seconds, the website is unreachable.
 * 
 * @param string $url URL that must be checked
 */

function url_test( $url ) {
  $url = stripslashes(urldecode($url));
  
  //$outputs holds json's object
  $outputs = array();

  $timeout = 10;
  $ch = curl_init();
  curl_setopt ( $ch, CURLOPT_URL, $url );
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
  curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );


  $http_respond = curl_exec($ch);
  $http_respond = trim( strip_tags( $http_respond ) );
  $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

  //Run a check on the status valid to distinguish the status comment
  switch ($http_code) {

    //For case where status is >100 bu < 199
    case $http_code >= 100 &&  $http_code <= 199:
      $outputs[$url]= array(
        'url'       => $url,
        'comment'   => 'Informative Response',
        'url_status' => $http_code,
      );
      return json_encode($outputs);
      break;

    //For case where status is >200 bu < 299
    case $http_code >= 200 &&  $http_code <= 299:
      $outputs[$url]= array(
        'url'       => $url,
        'comment'   => 'successful Response',
        'url_status' => $http_code,
      );
      return json_encode($outputs);
      break;

    //For case where status is >300 bu < 399
    case $http_code >= 300 &&  $http_code <= 399:
      $outputs[$url]= array(
        'url'       => $url,
        'comment'   => 'Redirects',
        'url_status' => $http_code,
      );
      return json_encode($outputs);
      break;

    //For case where status is >400 bu < 499
    case $http_code >= 400 &&  $http_code <= 499:
      $outputs[$url]= array(
        'url'       => $url,
        'comment'   => 'client Error',
        'url_status' => $http_code,
      );
      return json_encode($outputs);
      break;

    //For case where status is >500 bu < 599
    case $http_code >= 500 &&  $http_code <= 599:
      $outputs[$url]= array(
        'url'       => $url,
        'comment'   => 'Server Error',
        'url_status' => $http_code,
      );
      return json_encode($outputs);
      break;
    
    //For case where status is outside considered specs
    default:
      $outputs[$url]= array(
        'url'       => $url,
        'comment'   => 'Url response is invalid',
        'url_status' => $http_code,
      );
      break;
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
