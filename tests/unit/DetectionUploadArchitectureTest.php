<?php

use CodeIgniter\Test\CIUnitTestCase;

final class DetectionUploadArchitectureTest extends CIUnitTestCase
{
    private string $client;
    private string $workflow;
    private string $userController;
    private string $adminController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = file_get_contents(APPPATH . 'Libraries/DeepfakeApiClient.php');
        $this->workflow = file_get_contents(APPPATH . 'Libraries/DetectionWorkflowService.php');
        $this->userController = file_get_contents(APPPATH . 'Controllers/User/DetectionController.php');
        $this->adminController = file_get_contents(APPPATH . 'Controllers/Admin/DetectionController.php');
    }

    public function testClientUsesTemporaryPathAndMultipartCurlFile(): void
    {
        $this->assertStringContainsString('new CURLFile($temporaryPath', $this->client);
        $this->assertStringContainsString("'video' =>", $this->client);
        $this->assertStringNotContainsString('base64_encode', $this->client);
        $this->assertStringNotContainsString('file_get_contents', $this->client);
    }

    public function testPredictDoesNotPerformHealthCheckOrAutomaticRetry(): void
    {
        $predict = substr($this->client, strpos($this->client, 'public function predictVideo'), strpos($this->client, 'public function testConnection') - strpos($this->client, 'public function predictVideo'));
        $this->assertStringNotContainsString('getHealthUrl', $predict);
        $this->assertSame(1, substr_count($predict, '$this->request('));
        $this->assertStringNotContainsString('retry', strtolower($predict));
    }

    public function testRuntimeControllersHaveNoHardcodedBackendUrlOrThreshold(): void
    {
        foreach ([$this->userController, $this->adminController] as $source) {
            $this->assertStringContainsString("config('DeepfakeApi')", $source);
            $this->assertStringNotContainsString('103.76.120.208', $source);
            $this->assertStringNotContainsString('0.50', $source);
            $this->assertStringNotContainsString('fake_score >', $source);
        }
    }

    public function testPermanentMoveOccursAfterPrediction(): void
    {
        $predictPosition = strpos($this->workflow, 'predictVideo(');
        $movePosition = strpos($this->workflow, '$video->move(');
        $insertPosition = strpos($this->workflow, '$this->detections->insert(');
        $this->assertGreaterThan($predictPosition, $movePosition);
        $this->assertGreaterThan($movePosition, $insertPosition);
        $this->assertSame(1, substr_count($this->workflow, '$video->move('));
    }

    public function testValidationCoversEmptySizeExtensionAndMime(): void
    {
        foreach ([$this->userController, $this->adminController] as $source) {
            $this->assertStringContainsString('getSize() < 1', $source);
            $this->assertStringContainsString('max_size[video,', $source);
            $this->assertStringContainsString('ext_in[video,', $source);
            $this->assertStringContainsString('mime_in[video,', $source);
        }
    }

    public function testClientContainsSafeTimeoutHttpAndJsonFailureHandling(): void
    {
        $this->assertStringContainsString('CURLOPT_CONNECTTIMEOUT', $this->client);
        $this->assertStringContainsString('CURLOPT_TIMEOUT', $this->client);
        $this->assertStringContainsString('BACKEND_CONNECTION_ERROR', $this->client);
        $this->assertStringContainsString('BACKEND_HTTP_ERROR', $this->client);
        $this->assertStringContainsString('INVALID_BACKEND_JSON', $this->client);
    }

    public function testWorkflowCleansOrphanAndUsesTransaction(): void
    {
        $this->assertStringContainsString('transBegin()', $this->workflow);
        $this->assertStringContainsString('transCommit()', $this->workflow);
        $this->assertStringContainsString('transRollback()', $this->workflow);
        $this->assertStringContainsString('@unlink($storedPath)', $this->workflow);
    }
}
