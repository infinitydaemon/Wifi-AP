<?php

require_once 'includes/status_messages.php';

if (!defined('RASPI_ADMIN_DETAILS')) {
    define('RASPI_ADMIN_DETAILS', '/path/to/admin/details');
}

/**
 * Displays the authentication configuration form and updates the admin password if submitted.
 *
 * @param string $username The current username.
 * @param string $password The current password.
 */
function DisplayAuthConfig($username, $password)
{
    // Initialize a status message container
    $status = new StatusMessages();

    // If the form has been submitted
    if (isset($_POST['UpdateAdminPassword'])) {
        // If the old password matches
        if (password_verify($_POST['oldpass'], $password)) {
            $new_username = trim($_POST['username']);
            // If the new passwords don't match
            if ($_POST['newpass'] !== $_POST['newpassagain']) {
                $status->addMessage('New passwords do not match', 'danger');
            // If the new username is empty
            } elseif ($new_username === '') {
                $status->addMessage('Username must not be empty', 'danger');
            } else {
                // Create the admin details file if it doesn't exist
                if (!file_exists(RASPI_ADMIN_DETAILS)) {
                    file_put_contents(RASPI_ADMIN_DETAILS, '');
                }

                // Write the new username and password hash to the admin details file
                if (file_put_contents(RASPI_ADMIN_DETAILS, $new_username.PHP_EOL, LOCK_EX | FILE_APPEND) !== false &&
                    file_put_contents(RASPI_ADMIN_DETAILS, password_hash($_POST['newpass'], PASSWORD_BCRYPT).PHP_EOL, LOCK_EX | FILE_APPEND) !== false) {
                    $username = $new_username;
                    $status->addMessage('Admin password updated');
                } else {
                    $status->addMessage('Failed to update admin password', 'danger');
                }
            }
        } else {
            $status->addMessage('Old password does not match', 'danger');
        }
    }

    // Render the authentication configuration template
    echo renderTemplate('admin', compact('status', 'username'));
}
