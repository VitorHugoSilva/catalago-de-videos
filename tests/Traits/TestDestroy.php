<?php


namespace Tests\Traits;


trait TestDestroy
{
    protected abstract function model();
    protected abstract function routeDestroy();

    protected function assertDestroy(string $id)
    {
        /** @var  TestResponse $response */
        $response = $this->json('DELETE',$this->routeDestroy());
        $response->assertStatus(204);
        $this->assertNull($this->model()::find($id));
        $this->assertNotNull($this->model()::withTrashed()->find($id));
        return $response;
    }

}
