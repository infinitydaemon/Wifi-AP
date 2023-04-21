<?php

require_once '../../includes/config.php';
require_once '../../includes/functions.php';

if (isset($_POST['cfg_id'])) {
    $ovpncfg_id = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['cfg_id']);
    $ovpncfg_files = pathinfo(RASPI_OPENVPN_CLIENT_LOGIN, PATHINFO_DIRNAME).'/'.$ovpncfg_id.'_*.conf';
    exec("sudo rm ".escapeshellarg($ovpncfg_files), $return);
    $jsonData = ['return'=>$return];
    echo json_encode($jsonData);
}

