<?php

require '../../includes/csrf.php';
require_once '../../includes/config.php';

if (isset($_POST['blocklist_id'])) {
    $blocklist_id = $_POST['blocklist_id'];

    // Sanitize the input
    if (!in_array($blocklist_id, ["notracking-hostnames", "notracking-domains"])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid blocklist ID']);
        exit;
    }

    $notracking_url = "https://raw.githubusercontent.com/notracking/hosts-blocklists/master/";

    switch ($blocklist_id) {
        case "notracking-hostnames":
            $file = "hostnames.txt";
            break;
        case "notracking-domains":
            $file = "domains.txt";
            break;
    }
    $blocklist = $notracking_url . $file;

    // Use prepared statements to update the database
    $stmt = $pdo->prepare("UPDATE blocklists SET url = ?, file = ? WHERE id = 1");
    $stmt->execute([$blocklist, $file]);

    // Run the shell command to update the blocklist
    exec("sudo /etc/raspap/adblock/update_blocklist.sh $blocklist $file " . RASPI_ADBLOCK_LISTPATH, $output, $return);

    // Return a JSON response with the output and return code
    $response = ['output' => $output, 'return' => $return];
    echo json_encode($response);
}
