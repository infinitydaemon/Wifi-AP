<?php
// Get the HTTP Basic Authentication credentials from the request.
$username = $_SERVER['PHP_AUTH_USER'] ?? '';
$password = $_SERVER['PHP_AUTH_PW'] ?? '';

require_once RASPI_CONFIG . '/raspap.php';
$config = getConfig();

// Validate the credentials against the configured admin username and password.
$is_authenticated = ($username === $config['admin_user']) && password_verify($password, $config['admin_pass']);

if (!$is_authenticated) {
    // If the credentials are invalid, send a 401 Unauthorized response and exit.
    header('WWW-Authenticate: Basic realm="RaspAP"');
    if (function_exists('http_response_code')) {
        http_response_code(401);
    } else {
        header('HTTP/1.0 401 Unauthorized');
    }
    echo 'Not authorized' . PHP_EOL;
} else {
    // If the credentials are valid, allow the user to access the protected resource.
    // ...
}
