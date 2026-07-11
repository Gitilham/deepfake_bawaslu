<?php

namespace App\Commands;

use App\Models\SystemSettingModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanupDetections extends BaseCommand
{
    protected $group = 'Maintenance';
    protected $name = 'detections:cleanup';
    protected $description = 'Membersihkan video audit, file orphan, dan metadata frame lama.';
    protected $usage = 'detections:cleanup [--dry-run]';
    protected $options = ['--dry-run' => 'Tampilkan kandidat tanpa menghapus file atau data.'];

    public function run(array $params)
    {
        $dryRun = CLI::getOption('dry-run') !== null;
        $settings = new SystemSettingModel();
        $retention = $settings->getInt('raw_video_retention_days', 7, 1, 3650);
        $root = realpath(WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'videos');
        $cutoff = date('Y-m-d H:i:s', strtotime('-' . $retention . ' days'));
        $db = db_connect();
        $rows = $db->table('video_detections')->select('id, file_path')
            ->where('file_path !=', null)->where('file_deleted_at', null)->where('created_at <', $cutoff)->get()->getResultArray();
        $deleted = 0;

        if ($root !== false) {
            foreach ($rows as $row) {
                $path = realpath(WRITEPATH . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, (string) $row['file_path']), DIRECTORY_SEPARATOR));
                if ($path === false || ! str_starts_with($path, $root . DIRECTORY_SEPARATOR) || ! is_file($path)) {
                    continue;
                }
                $deleted++;
                if (! $dryRun && unlink($path)) {
                    $db->table('video_detections')->where('id', $row['id'])->update(['file_deleted_at' => date('Y-m-d H:i:s')]);
                }
            }

            $known = array_flip(array_filter(array_map(static fn (array $row): string => basename((string) $row['file_path']),
                $db->table('video_detections')->select('file_path')->where('file_path !=', null)->get()->getResultArray())));
            foreach (glob($root . DIRECTORY_SEPARATOR . '*') ?: [] as $file) {
                if (is_file($file) && ! isset($known[basename($file)]) && filemtime($file) < strtotime($cutoff)) {
                    $deleted++;
                    if (! $dryRun) {
                        unlink($file);
                    }
                }
            }
        }

        $frameCount = 0;
        if ($settings->getBool('store_frame_metadata', false) && $db->tableExists('detection_frames')) {
            $frameDays = $settings->getInt('frame_metadata_retention_days', 30, 1, 3650);
            $frameCutoff = date('Y-m-d H:i:s', strtotime('-' . $frameDays . ' days'));
            $frameCount = $db->table('detection_frames')->where('created_at <', $frameCutoff)->countAllResults();
            if (! $dryRun) {
                $db->table('detection_frames')->where('created_at <', $frameCutoff)->delete();
            }
        }

        CLI::write("Kandidat file: {$deleted}; metadata frame: {$frameCount}." . ($dryRun ? ' (dry-run)' : ''), $dryRun ? 'yellow' : 'green');
    }
}
