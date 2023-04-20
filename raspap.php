function getConfig(): array
{
    $config = [
        'admin_user' => 'admin',
        'admin_pass' => '$2y$10$YKIyWAmnQLtiJAy6QgHQ.eCpY4m.HCEbiHaTgN6.acNC6bDElzt.i'
    ];

    $authFile = RASPI_CONFIG . '/raspap.auth';
    if (file_exists($authFile) && $authDetails = file($authFile, FILE_IGNORE_NEW_LINES)) {
        $config['admin_user'] = $authDetails[0];
        $config['admin_pass'] = $authDetails[1];
    }

    return $config;
}
