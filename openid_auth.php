<?php
include('config.php');

include_once('cred.php');
include_once('user.php');
require_once('OAuth2/Client.php');
require_once('OAuth2/GrantType/IGrantType.php');
require_once('OAuth2/GrantType/AuthorizationCode.php');


const REDIRECT_URI           = 'https://server.com/im-web/openid_auth.php';

$AUTHORIZATION_ENDPOINT = $openid_issuer . 'authorize';
$TOKEN_ENDPOINT         = $openid_issuer . 'token';
$USER_INFO_ENDPOINT     = $openid_issuer . 'userinfo';

$client = new OAuth2\Client($CLIENT_ID, $CLIENT_SECRET, OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);

if (isset($_GET['error']))
{
	header("HTTP/1.1 401 Unauthorized");
        echo $_GET['error'] . ": " . $_GET['error_description'];
}
elseif (!isset($_GET['code']))
{
    $auth_url = $client->getAuthenticationUrl($AUTHORIZATION_ENDPOINT, REDIRECT_URI, array('scope' => 'profile openid email'));
    header('Location: ' . $auth_url);
}
else
{
    $params = array('code' => $_GET['code'], 'redirect_uri' => REDIRECT_URI);
    $response = $client->getAccessToken($TOKEN_ENDPOINT, 'authorization_code', $params);

    if ($response['code'] != 200) {
        header("HTTP/1.1 401 Unauthorized");
        echo "Non Authorized. Error returned by IdP: " . $response["result"]["error_description"];
        die();
    }

    if ( !session_id() ) {
        session_start();
    }

    $_SESSION["user_token"] = $response['result']['access_token'];
    $client->setAccessToken($response['result']['access_token']);
    $client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);
    $params = array('schema' => 'openid', 'access_token' => $response['result']['access_token']);
    $response = $client->fetch($USER_INFO_ENDPOINT, $params);

    if ($response['code'] == 200) {
        $_SESSION["user_name"] = $response['result']['name'];
        $_SESSION["user"] = $response['result']['sub'];
        $_SESSION["password"] = $response['result']['name'];

        if (is_null(get_user($_SESSION["user"]))) {
            // this the first login of the user
            $err = insert_user($_SESSION["user"], $response['result']['name'], array('users'), 0);
            $err = insert_credential($_SESSION["user"], "", "InfrastructureManager", "", $_SESSION["user"], '', '', '', '', '', '', '', '', '', '', '', '', '');
            $err = insert_credential($_SESSION["user"], "", "VMRC", "http://servproject.i3m.upv.es:8080/vmrc/vmrc", "micafer", "ttt25", '', '', '', '', '', '', '', '', '', '', '', '');
        }

        header('Location: list.php');
    } else {
        header("HTTP/1.1 401 Unauthorized");
        echo "Non Authorized. Error returned by IdP: " . $response["result"]["error_description"];
    }
}
?>
