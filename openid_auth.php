<?php
require 'config.php';

require_once 'cred.php';
require_once 'user.php';
require_once 'OAuth2/JWT.php';
require_once 'OAuth2/Client.php';
require_once 'OAuth2/GrantType/IGrantType.php';
require_once 'OAuth2/GrantType/AuthorizationCode.php';

$AUTHORIZATION_ENDPOINT = $openid_issuer . '/protocol/openid-connect/auth';
$TOKEN_ENDPOINT         = $openid_issuer . '/protocol/openid-connect/token';
$USER_INFO_ENDPOINT     = $openid_issuer . '/protocol/openid-connect/userinfo';

$client = new OAuth2\Client($CLIENT_ID, $CLIENT_SECRET, OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);

if (isset($_GET['error'])) {
    header("HTTP/1.1 401 Unauthorized");
        echo $_GET['error'] . ": " . $_GET['error_description'];
} elseif (!isset($_GET['code'])) {
    $auth_url = $client->getAuthenticationUrl($AUTHORIZATION_ENDPOINT, $REDIRECT_URI, array('scope' => 'profile openid email'));
    header('Location: ' . $auth_url);
} else {
    $params = array('code' => $_GET['code'], 'redirect_uri' => $REDIRECT_URI);
    $response = $client->getAccessToken($TOKEN_ENDPOINT, 'authorization_code', $params);

    if ($response['code'] != 200) {
        header("HTTP/1.1 401 Unauthorized");
        echo "Non Authorized. Error returned by IdP: " . $response["result"]["error_description"];
        die();
    }

    if (!session_id() ) {
        session_start();
    }

    $_SESSION["user_token"] = $response['result']['access_token'];
    $client->setAccessToken($response['result']['access_token']);
    $client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);
    $params = array('schema' => 'openid', 'access_token' => $response['result']['access_token']);
    $response = $client->fetch($USER_INFO_ENDPOINT, $params);

    if ($response['code'] == 200) {
        $_SESSION["user"] = $response['result']['sub'];

        $username = $response['result']['sub'];
        if (isset($response['result']['name']) && $response['result']['name'] != "") {
            $username = $response['result']['name'];
        } elseif (isset($response['result']['given_name']) && $response['result']['given_name'] != "") {
            $username = $response['result']['given_name'];
            if (isset($response['result']['family_name']) && $response['result']['family_name'] != "") {
                $username = $username . " " . $response['result']['family_name'];
            }
        }
        $_SESSION["user_name"] = $username;
        $_SESSION["password"] = $username;

        // Get expiration time
        $access_token_data = OAuth2\JWT::decode($_SESSION['user_token'], "", array("RS256"));
        $access_token_data = json_decode(json_encode($access_token_data), true);
        $_SESSION["token_exp"] = $access_token_data["exp"];

        if (is_null(get_user($_SESSION["user"]))) {
            // this the first login of the user
            $err = insert_user($_SESSION["user"],  $username, array('users'), 0);
            $err = insert_credential($_SESSION["user"], "", "InfrastructureManager", "", $_SESSION["user"], '', '', '', '', '', '', '', '', '', '', '', '', '');
            $err = insert_credential($_SESSION["user"], "", "VMRC", "http://appsgrycap.i3m.upv.es:32080/vmrc/vmrc", "micafer", "ttt25", '', '', '', '', '', '', '', '', '', '', '', '');
        }

        header('Location: list.php');
    } else {
        header("HTTP/1.1 401 Unauthorized");
        echo "Non Authorized. Error returned by IdP: " . $response["result"]["error_description"];
    }
}
?>
