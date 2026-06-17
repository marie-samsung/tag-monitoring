<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\BackupHandler;

class BackupRun extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Database';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'db:backup-cron';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Runs the system daily auto backup if enabled and schedule is met. Supports forcing execution.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'db:backup-cron [options]';

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--force' => 'Force database backup even if daily auto backup is disabled or was recently run.',
    ];

    /**
     * Actually run the command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $force = array_key_exists('force', $params) || CLI::getOption('force');

        CLI::write("Initializing Database Auto Backup CLI Task...", "cyan");

        $handler = new BackupHandler();
        $settings = $handler->getSettings();

        if ($force) {
            CLI::write("Force flag detected. Bypassing schedule & status checks.", "yellow");
        } else {
            if (!$settings['enabled']) {
                CLI::write("Auto-backup is currently disabled in system settings. Exiting.", "yellow");
                return;
            }
        }

        CLI::write("Running backup operation...", "cyan");
        $result = $handler->runBackup($force);

        if ($result['success']) {
            CLI::write("SUCCESS: " . $result['message'], "green");
        } else {
            CLI::write("FAILURE: " . $result['message'], "red");
        }
    }
}
