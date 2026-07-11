<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VideoDetectionModel;

class ReportController extends BaseController
{
    protected VideoDetectionModel $detectionModel;

    public function __construct()
    {
        $this->detectionModel = new VideoDetectionModel();
    }

    public function index()
    {
        $filters = [
            'start_date'      => $this->request->getGet('start_date'),
            'end_date'        => $this->request->getGet('end_date'),
            'predicted_label' => $this->request->getGet('predicted_label'),
            'status'          => $this->request->getGet('status'),
        ];

        $detections = $this->detectionModel->getAllWithUser($filters);

        $summary = [
            'total'    => count($detections),
            'real'     => 0,
            'deepfake' => 0,
            'unknown'  => 0,
            'failed'   => 0,
        ];

        foreach ($detections as $row) {
            if ($row['predicted_label'] === 'REAL') {
                $summary['real']++;
            }

            if ($row['predicted_label'] === 'DEEPFAKE') {
                $summary['deepfake']++;
            }

            if ($row['predicted_label'] === 'UNKNOWN') {
                $summary['unknown']++;
            }

            if ($row['status'] === 'failed') {
                $summary['failed']++;
            }
        }

        $data = [
            'title'      => 'Laporan Deteksi Video',
            'detections' => $detections,
            'filters'    => $filters,
            'summary'    => $summary,
        ];

        return view('admin/reports/index', $data);
    }
}