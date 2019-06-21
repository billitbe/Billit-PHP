<?php
  
  // This PHP example is based upon the excellent workd of https://gist.github.com/amfeng/3507366
  define('CLIENT_ID', 'YOURCLIENTID');
  define('API_KEY', 'YOURAPIKEY');

  define('TOKEN_URI', 'https://api.sandbox.billit.be/OAuth2/token');
  define('AUTHORIZE_URI', 'https://my.sandbox.billit.be/Account/Logon?client_id=YOURCLIENTID&redirect_uri=REDIRECT_URI');

  if (isset($_GET['code'])) { // Redirect w/ code
    $code = $_GET['code'];

    $token_request_body = array(
      'client_secret' => API_KEY,
      'grant_type' => 'authorization_code',
      'client_id' => CLIENT_ID,
      'code' => $code,
    );

    $req = curl_init(TOKEN_URI);
    curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($req, CURLOPT_POST, true );
    curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

    // TODO: Additional error handling
    $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
    $resp = json_decode(curl_exec($req), true);
    curl_close($req);

    echo $resp['access_token'];
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