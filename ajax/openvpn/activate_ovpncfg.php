<?php

require_once '../../includes/config.php';
require_once '../../includes/functions.php';

if (!isset($_POST['cfg_id'])) {
    exit; // Exit early if the POST parameter is missing
}

$ovpncfg_id = $_POST['cfg_id'];
$ovpncfg_client = RASPI_OPENVPN_CLIENT_PATH . $ovpncfg_id . '_client.conf';
$ovpncfg_login = RASPI_OPENVPN_CLIENT_PATH . $ovpncfg_id . '_login.conf';

// Remove existing client config and login files and symbolically link the selected ones
$commands = [
    "sudo rm -f " . RASPI_OPENVPN_CLIENT_CONFIG,
    "sudo ln -sf " . escapeshellarg($ovpncfg_client) . " " . RASPI_OPENVPN_CLIENT_CONFIG,
    "sudo rm -f " . RASPI_OPENVPN_CLIENT_LOGIN,
    "sudo ln -sf " . escapeshellarg($ovpncfg_login) . " " . RASPI_OPENVPN_CLIENT_LOGIN,
];

foreach ($commands as $command) {
    exec($command, $output, $return);
    if ($return !== 0) {
        // Handle error, log, or display error message to the user
        exit;
    }
}

// Restart the OpenVPN client service
$commands = [
    "sudo /bin/systemctl stop openvpn-client@client",
    "sudo /bin/systemctl enable openvpn-client@client",
    "sudo /bin/systemctl start openvpn-client@client",
];

foreach ($commands as $command) {
    exec($command, $output, $return);
    if ($return !== 0) {
        // Handle error, log, or display error message to the user
        exit;
    }
}

// Return success response as JSON
echo json_encode(['success' => true]);
