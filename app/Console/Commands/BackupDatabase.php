<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';

    protected $description = 'Backup the database to a file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $databaseName = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $port = env('DB_PORT', '3306');

        $date = now()->format('Y-m-d_H-i-s');

        // Windows path for the backup file
        $backupFile = "app\\backups\\{$databaseName}_{$date}.sql";

        // Linux or MacOS path for the backup file
        // $backupFile = "app/backups/{$databaseName}_{$date}.sql";

        // Windows compatibility
        $command = "\"C:\\laragon\\bin\\mysql\\mysql-8.4.3-winx64\\bin\\mysqldump.exe\" --user=\"{$username}\" --password=\"{$password}\" --host=\"{$host}\" --port=\"{$port}\" \"{$databaseName}\" > \"{$backupFile}\"";

        // Linux/MacOS compatibility
        // $command = "mysqldump --user={$username} --password={$password} --host={$host} --port={$port} {$databaseName} > " . storage_path($backupFile);

        // dd(storage_path($backupFile));

        exec($command, $output, $return);

        if ($return === 0) {
            $this->info('Backup successfully created: ' . $backupFile);
        } else {
            $this->error('Backup failed.');
        }
    }
}
