<?php

// Include required files and headers
require '../../includes/csrf.php';
require_once '../../includes/config.php';
require_once RASPI_CONFIG.'/raspap.php';
require_once '../../includes/authenticate.php';

// Set headers for security
header('X-Frame-Options: DENY');
header("Content-Security-Policy: default-src 'none'; connect-src 'self'");
header('X-Content-Type-Options: nosniff');
header('Content-Type: application/json');

// Get interface parameter
$interface = filter_input(INPUT_GET, 'inet', FILTER_SANITIZE_SPECIAL_CHARS);

if (empty($interface)) {
    // Use first interface if inet parameter not provided
    exec("ip -o link show | awk -F ': ' '{print $2}' | grep -v lo ", $interfacesWlo);
    if (count($interfacesWlo) > 0) {
        $interface = $interfacesWlo[0];
    } else {
        exit('No network interfaces found.');
    }
} 

// Check interface name length and validity
define('IFNAMSIZ', 16);
if (strlen($interface) > IFNAMSIZ) {
    exit('Interface name too long.');
} elseif(!preg_match('/^[a-zA-Z0-9]+$/', $interface)) {
    exit('Invalid interface name.');
}

// Get bandwidth data from vnstat
require_once './get_bandwidth_hourly.php';
exec(sprintf('vnstat -i %s --json ', escapeshellarg($interface)), $jsonstdoutvnstat, $exitcodedaily);

if ($exitcodedaily !== 0) {
    exit('vnstat error');
}

// Parse JSON output and get time units
$jsonobj = json_decode($jsonstdoutvnstat[0], true);
$timeunits = filter_input(INPUT_GET, 'tu');

if ($timeunits === 'm') {
    // Get monthly data
    $jsonData = $jsonobj['interfaces'][0]['traffic']['month'];
} else {
    // Default: get daily data
    $jsonData = $jsonobj['interfaces'][0]['traffic']['day'];
}

// Get data size units and factor
$datasizeunits = filter_input(INPUT_GET, 'dsu');
$dsu_factor = $datasizeunits == "mb" ? 1024 * 1024 : 1024;

// Output JSON data
echo '[ ';
$firstelm = true;
for ($i = count($jsonData) - 1; $i >= 0; --$i) {
    if ($timeunits === 'm') {
        $dt = DateTime::createFromFormat('Y n', $jsonData[$i]['date']['year'].' '.$jsonData[$i]['date']['month']);
    } else {
        $dt = DateTime::createFromFormat('Y n j', $jsonData[$i]['date']['year'].' '.$jsonData[$i]['date']['month'].' '.$jsonData[$i]['date']['day']);
    }

    if ($firstelm) {
        $firstelm = false;
    } else {
        echo ',';
    }

    $datasend = round($jsonData[$i]['tx'] / $dsu_factor, 0);
    $datareceived = round($jsonData[$i]['rx']
