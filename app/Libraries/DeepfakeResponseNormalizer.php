<?php

namespace App\Libraries;

class DeepfakeResponseNormalizer
{
    public function normalize(array $response): array
    {
        $decision = strtoupper((string) ($response['final_decision'] ?? $response['result'] ?? $response['label'] ?? $response['prediction'] ?? 'UNKNOWN'));
        if (! in_array($decision, ['REAL', 'MENCURIGAKAN', 'DEEPFAKE', 'NO_FACE'], true)) $decision = 'UNKNOWN';

        return [
            'success' => ($response['success'] ?? false) === true,
            'prediction' => strtoupper((string) ($response['prediction'] ?? 'UNKNOWN')),
            'final_decision' => $decision,
            'confidence' => $response['confidence'] ?? null,
            'real_score' => $response['real_score'] ?? null,
            'fake_score' => $response['fake_score'] ?? null,
            'processing_seconds' => $response['processing_seconds'] ?? $response['duration_seconds'] ?? null,
            'frames_used' => $response['frames_used'] ?? $response['frames_count'] ?? null,
            'face_detected_count' => $response['faces'] ?? $response['face_detected_count'] ?? null,
            'model_version' => $response['model_version'] ?? null,
            'frames' => is_array($response['frames'] ?? null) ? $response['frames'] : [],
            'message' => (string) ($response['message'] ?? ''),
            'backend' => $response,
        ];
    }
}

