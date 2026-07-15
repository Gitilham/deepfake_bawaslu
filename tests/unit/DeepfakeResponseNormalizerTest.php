<?php

use App\Libraries\DeepfakeResponseNormalizer;
use CodeIgniter\Test\CIUnitTestCase;

final class DeepfakeResponseNormalizerTest extends CIUnitTestCase
{
    public function testFinalDecisionHasPriorityWithoutRecalculatingThreshold(): void
    {
        $result = (new DeepfakeResponseNormalizer())->normalize([
            'success' => true,
            'final_decision' => 'MENCURIGAKAN',
            'result' => 'REAL',
            'prediction' => 'DEEPFAKE',
            'fake_score' => 0.5,
        ]);

        $this->assertSame('MENCURIGAKAN', $result['final_decision']);
        $this->assertSame(0.5, $result['fake_score']);
    }

    public function testLegacyResultAndPredictionRemainCompatible(): void
    {
        $normalizer = new DeepfakeResponseNormalizer();
        $this->assertSame('REAL', $normalizer->normalize(['success' => true, 'result' => 'REAL'])['final_decision']);
        $this->assertSame('DEEPFAKE', $normalizer->normalize(['success' => true, 'prediction' => 'DEEPFAKE'])['final_decision']);
    }

    public function testInvalidDecisionBecomesUnknown(): void
    {
        $result = (new DeepfakeResponseNormalizer())->normalize(['success' => true, 'final_decision' => 'INVALID']);
        $this->assertSame('UNKNOWN', $result['final_decision']);
    }
}

