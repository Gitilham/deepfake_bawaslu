<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class PruneApiLogs extends BaseCommand
{
    protected $group = 'Maintenance';
    protected $name = 'api-logs:prune';
    protected $description = 'Menghapus log API lama sesuai retensi.';
    protected $usage = 'api-logs:prune [--dry-run]';
    protected $options = ['--dry-run' => 'Hanya menghitung tanpa menghapus data.'];

    public function run(array $params)
    {
        $dryRun = CLI::getOption('dry-run') !== null;
        $settings = new \App\Models\SystemSettingModel();
        $successDays = $settings->getInt('api_success_log_retention_days', 14, 1, 3650);
        $errorDays = $settings->getInt('api_error_log_retention_days', 90, 1, 3650);
        $builder = db_connect()->table('flask_api_logs');

        $successCutoff = date('Y-m-d H:i:s', strtotime('-' . $successDays . ' days'));
        $errorCutoff = date('Y-m-d H:i:s', strtotime('-' . $errorDays . ' days'));
        $successCount = (clone $builder)->where('error_message', null)->where('created_at <', $successCutoff)->countAllResults();
        $errorCount = (clone $builder)->where('error_message !=', null)->where('created_at <', $errorCutoff)->countAllResults();

        CLI::write("Log sukses: {$successCount}; log error: {$errorCount}." . ($dryRun ? ' (dry-run)' : ''));
        if (! $dryRun) {
            $builder->groupStart()->where('error_message', null)->where('created_at <', $successCutoff)->groupEnd()
                ->orGroupStart()->where('error_message !=', null)->where('created_at <', $errorCutoff)->groupEnd()->delete();
            CLI::write('Pruning selesai.', 'green');
        }
    }
}
