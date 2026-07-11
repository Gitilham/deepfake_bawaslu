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
            'start_date'      => $this->request->getGet('start_date') ?? $this->request->getGet('date_from'),
            'end_date'        => $this->request->getGet('end_date') ?? $this->request->getGet('date_to'),
            'predicted_label' => $this->request->getGet('predicted_label') ?? $this->request->getGet('label'),
            'status'          => $this->request->getGet('status'),
        ];

        $summary = $this->detectionModel->getReportSummary($filters);
        $detections = $this->detectionModel->paginateWithUser($filters, 20, 'reports');

        $data = [
            'title'      => 'Laporan Deteksi Video',
            'detections' => $detections,
            'filters'    => $filters,
            'summary'    => $summary,
            'total_videos' => $summary['total'],
            'total_real' => $summary['real'],
            'total_deepfake' => $summary['deepfake'],
            'total_failed' => $summary['failed'],
            'pager'      => $this->detectionModel->pager,
        ];

        return view('admin/reports/index', $data);
    }
}
