<?php

$githubApiUrl = "https://api.github.com/repos/infinitydaemon/Wifi-AP/contents/config";
$lastCommitFile = "last_commit.txt";

$lastCommit = "";
if (file_exists($lastCommitFile)) {
    $lastCommit = trim(file_get_contents($lastCommitFile));
}

$ch = curl_init($githubApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: My User Agent',
    'Accept: application/vnd.github.v3+json',
]);
$result = curl_exec($ch);
curl_close($ch);

if ($result === false) {
    die("Error getting GitHub API response");
}

$data = json_decode($result, true);

if (!is_array($data)) {
    die("Error decoding GitHub API response");
}

$changesDetected = false;
foreach ($data as $file) {
    if ($file["type"] == "file" && $file["name"] != ".gitignore") {
        $fileApiUrl = $file["url"];

        $ch = curl_init($fileApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: My User Agent',
            'Accept: application/vnd.github.v3+json',
        ]);
        $fileResult = curl_exec($ch);
        curl_close($ch);

        if ($fileResult === false) {
            die("Error getting file from GitHub API: {$file['name']}");
        }

        $fileData = json_decode($fileResult, true);

        if (!is_array($fileData) || !isset($fileData["sha"])) {
            die("Error decoding file from GitHub API: {$file['name']}");
        }

        $fileSha = $fileData["sha"];

        if ($lastCommit != "" && $fileSha != $lastCommit) {
            $changesDetected = true;
            echo "File {$file['name']} has changed!\n";
        }
    }
}

if ($changesDetected) {
    file_put_contents($lastCommitFile, $fileSha);
}

?>
