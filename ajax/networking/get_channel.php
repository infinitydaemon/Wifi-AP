<?php

require_once '../../includes/config.php';
require_once '../../includes/csrf.php';

$hostapdConfig = file(RASPI_HOSTAPD_CONFIG, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$hostapdConfigData = array();

foreach ($hostapdConfig as $hostapdConfigLine) {
    $hostapdConfigLine = trim($hostapdConfigLine);

    if (strlen($hostapdConfigLine) === 0 || strpos($hostapdConfigLine, '#') === 0) {
        continue;
    }

    $hostapdConfigParts = explode('=', $hostapdConfigLine, 2);

    if (count($hostapdConfigParts) !== 2) {
        continue;
    }

    $hostapdConfigData[$hostapdConfigParts[0]] = trim($hostapdConfigParts[1]);
}

$channel = intval($hostapdConfigData['channel'] ?? 0);
echo json_encode($channel);
