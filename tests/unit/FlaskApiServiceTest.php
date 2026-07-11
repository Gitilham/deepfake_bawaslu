<?php

use App\Libraries\FlaskApiService;
use CodeIgniter\Test\CIUnitTestCase;

final class FlaskApiServiceTest extends CIUnitTestCase
{
    private FlaskApiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = (new ReflectionClass(FlaskApiService::class))->newInstanceWithoutConstructor();
    }

    public function testNormalizesNewResponse(): void
    {
        $result = $this->service->normalizePredictionResponse([
            'success' => true,
            'request_id' => 'req-1',
            'result_status' => 'MENCURIGAKAN',
            'binary_prediction' => 'REAL',
            'requires_manual_review' => true,
            'confidence' => 0.55,
        ]);
        $this->assertSame('MENCURIGAKAN', $result['result_status']);
        $this->assertSame('REAL', $result['binary_prediction']);
        $this->assertTrue($result['requires_manual_review']);
    }

    public function testNormalizesLegacyResponse(): void
    {
        $result = $this->service->normalizePredictionResponse(['success' => true, 'prediction' => 'DEEPFAKE']);
        $this->assertSame('DEEPFAKE', $result['result_status']);
        $this->assertSame('DEEPFAKE', $result['binary_prediction']);
    }

    public function testNoFaceIsValidAndNotUnknown(): void
    {
        $result = $this->service->normalizePredictionResponse([
            'success' => true,
            'prediction' => 'NO_FACE',
            'confidence' => 0,
        ]);
        $this->assertSame('NO_FACE', $result['result_status']);
        $this->assertNull($result['binary_prediction']);
        $this->assertFalse($result['requires_manual_review']);
    }

    public function testMissingAndInvalidStatusesBecomeUnknown(): void
    {
        $this->assertSame('UNKNOWN', $this->service->normalizePredictionResponse(['success' => true])['result_status']);
        $this->assertSame('UNKNOWN', $this->service->normalizePredictionResponse(['success' => true, 'status' => 'OTHER'])['result_status']);
    }

    public function testExtractsFastApiErrors(): void
    {
        $this->assertSame('Video tidak valid.', $this->service->extractErrorMessage(['detail' => 'Video tidak valid.']));
        $this->assertSame('Field required', $this->service->extractErrorMessage([
            'detail' => [['loc' => ['body', 'video'], 'msg' => 'Field required', 'type' => 'missing']],
        ]));
    }

    public function testCompactLogExcludesLargePayloads(): void
    {
        $compact = $this->service->buildCompactLog([
            'request_id' => 'req-2',
            'http_status' => 200,
            'latency_ms' => 50,
            'result_status' => 'REAL',
            'message' => 'ok',
            'frames' => [['large' => true]],
            'feature_debug' => ['large' => true],
            'raw_response' => 'forbidden',
        ]);
        $this->assertArrayNotHasKey('frames', $compact);
        $this->assertArrayNotHasKey('feature_debug', $compact);
        $this->assertArrayNotHasKey('raw_response', $compact);
    }
}
