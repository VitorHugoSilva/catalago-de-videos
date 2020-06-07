<?php
declare(strict_types=1);

namespace Tests\Traits;


use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves
{

    protected function assertStore(array $sendData, array $testDataBase, array $testJsonData = null)
    {
        /** @var  TestResponse $response */
        $response = $this->json('POST',$this->routeStore(), $sendData);
        if ($response->status() !== 201) {
            throw new \Exception("Response status must be 201, give {$response->status()}: \n {$response->content()}");
        }
        $this->assertInDatabase($response, $testDataBase);
        $this->assertJsonResponseContent($response, $testDataBase, $testJsonData);
        return $response;
    }
    protected function assertUpdate(array $sendData, array $testDataBase, array $testJsonData = null)
    {
        /** @var  TestResponse $response */
        $response = $this->json('PUT',$this->routeUpdate(), $sendData);
        if ($response->status() !== 200) {
            throw new \Exception("Response status must be 200, give {$response->status()}: \n {$response->content()}");
        }
        $this->assertInDatabase($response, $testDataBase);
        return $response;
    }

    private function assertInDatabase($response, $testDataBase)
    {
        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDataBase + ['id' => $response->json('id')]);

    }

    private function assertJsonResponseContent(TestResponse $response, array $testDataBase, array $testJsonData = null)
    {
        $testResponse = $testJsonData ?? $testDataBase;
        $response->assertJsonFragment($testResponse+ ['id' => $response->json('id')]);

    }
}
