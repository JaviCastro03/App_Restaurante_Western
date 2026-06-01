<?php
/**
 * Google Auth - Redirect to Google
 */
require_once '../../models/Config.php';
require_once '../../models/vendor/autoload.php';

session_start();

$client = new Google\Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope('email');
$client->addScope('profile');
$client->setAccessType('online');
$client->setPrompt('select_account');

$authUrl = $client->createAuthUrl();
header("Location: $authUrl");
exit;
