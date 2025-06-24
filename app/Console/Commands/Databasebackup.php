<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Databasebackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = "backup-" . now()->format('Y-m-d') . ".sql";

        // Windows Laragon path to mysqldump
        $mysqldump = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe';

        // Linux path to mysqldump
        // $mysqldump = '/usr/bin/mysqldump';

        $command = "\"{$mysqldump}\" --user=" . env('DB_USERNAME') .
            " --password=" . env('DB_PASSWORD') .
            " --host=" . env('DB_HOST') .
            " " . env('DB_DATABASE') .
            " > " . storage_path("app/Backup/{$filename}");

        $returnVar = null;
        $output  = null;

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('Backup failed.');
        } else {
            $this->info("Database backup saved to: storage/app/Backup/{$filename}");
        }
    }
}
