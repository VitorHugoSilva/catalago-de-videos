<?php


namespace Tests\Traits;


use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Lang;

trait TestValidations
{
    protected abstract function model();

    protected abstract function routeStore();

    protected abstract function routeUpdate();

    protected function assertInvalidationInStoreAction(
        array $data,
        string $rule,
        $rulePrams = []
    )
    {
        $response = $this->json('POST',$this->routeStore(), $data);
        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule,$rulePrams);
    }

    protected function assertInvalidationInUpdateAction(
        array $data,
        string $rule,
        $rulePrams = []
    )
    {
        $response = $this->json('PUT',$this->routeUpdate(), $data);
        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule,$rulePrams);
    }

    protected function assertInvalidationFields(
        TestResponse $response,
        array $fields,
        string $rule,
        array $ruleParams = []
    )
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors($fields);
        foreach ($fields as $field) {
            $fieldName = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
               Lang::get("validation.{$rule}", ['attribute' => $fieldName] + $ruleParams)
            ]);
        }
    }
}
