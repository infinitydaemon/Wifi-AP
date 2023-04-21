<?php

require_once '../../includes/config.php';
require_once '../../includes/functions.php';

define('SUDO_CMD', '/usr/bin/sudo');

if (isset($_POST['logfile'])) {
    $logfile = realpath($_POST['logfile']);
    $basename = basename($logfile);

    // Validate that the file path is not empty and is inside the logs directory
    if (!$logfile || strpos($logfile, LOGS_DIR) !== 0) {
        http_response_code(400);
        exit('Invalid file path');
    }

    // truncate requested log file using sudo
    exec(SUDO_CMD . " truncate -s 0 $basename", $return);
    echo json_encode($return);
}
