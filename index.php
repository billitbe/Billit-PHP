<?php
  
  // This PHP example is based upon the excellent work of https://gist.github.com/amfeng/3507366
  define('CLIENT_ID', 'YOURCLIENTID');
  define('API_KEY', 'YOURAPIKEY');
  define('REDIRECT_URI', 'REDIRECT TO WHERE THIS PAGE IS ACCESIBLE');

  define('TOKEN_URI', 'https://api.sandbox.billit.be/OAuth2/token'); // staging environment change to 'https://api.billit.be/OAuth2/token'
  define('AUTHORIZE_URI', 'https://my.sandbox.billit.be/Account/Logon?client_id=' . CLIENT_ID . '&redirect_uri=' .  REDIRECT_URI);

  if (isset($_GET['code'])) { // Redirect w/ code
    $code = $_GET['code'];

    $token_request_body = array(
      'client_secret' => API_KEY,
      'grant_type' => 'authorization_code',
      'client_id' => CLIENT_ID,
      'code' => $code,
    );

    $req = curl_init(TOKEN_URI);

    $data_string = json_encode($token_request_body);

    curl_setopt($req, CURLOPT_HTTPHEADER, array(                                                                          
      'Content-Type: application/json',                                                                                
      'Content-Length: ' . strlen($data_string))                                                                       
    );
    curl_setopt($req, CURL, true);
    curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($req, CURLOPT_POST, true );
    curl_setopt($req, CURLOPT_POSTFIELDS, json_encode($token_request_body));

    // TODO: Additional error handling
    $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
    $jsonResp = curl_exec($req);
    $arrayResp = json_decode($jsonResp, true);

    curl_close($req);

    echo 'Response (in JSON):<br />';
    echo $jsonResp . '<br /><br />';

    echo 'Items in response:<br /><br />';

    echo 'access_token:<br />';
    echo $arrayResp['access_token']."<br /><br />";

    echo 'refresh_token:<br />';
    echo $arrayResp['refresh_token']."<br /><br />";

    echo 'expires_in:<br />';
    echo $arrayResp['expires_in']."<br /><br />";

  } else if (isset($_GET['error'])) { // Error
    echo $_GET['error_description'];
  } else { // Show OAuth link
    $authorize_request_body = array(
      'response_type' => 'code',
      'scope' => 'read_write',
      'client_id' => CLIENT_ID
    );

    $url = AUTHORIZE_URI;
    echo "<a href='$url'>Connect with Billit</a>";
  }
?>