<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;
use Tests\Traits\TestDestroy;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves, TestDestroy;

    protected $genre;
    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = factory(Genre::class)->create();
    }

    public function testIndex()
    {
       $response = $this->get(route('genres.index'));

       $response->assertStatus(200)
           ->assertJson([$this->genre->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('genres.show', ['genre' => $this->genre->id]));

        $response->assertStatus(200)
            ->assertJson($this->genre->toArray());
    }

    public function testInvalidationDateStore()
    {
        $data = [
            'name' => ''
        ];
        $this->assertInvalidationInStoreAction($data, 'required');

        $data = [
            'name' => str_repeat('a', 256)
        ];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max'=>255]);

        $data = [
            'is_active' => 'a'
        ];
    }

    public function testInvalidationDateUpdate()
    {

        $data = [
            'name' => ''
        ];
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data = [
            'name' => str_repeat('a', 256)
        ];
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max'=>255]);

        $data = [
            'is_active' => 'a'
        ];
        $this->assertInvalidationInUpdateAction($data, 'boolean');

    }

    private function assertInvalidationRequired(TestResponse $response)
    {
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                Lang::get('validation.required', ['attribute' => 'name'])
            ]);
    }

    public function testStore()
    {

        $data = ['name' => 'test'];

        $response = $this->assertStore($data, $data+['is_active' => true, 'deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
        $data = [
            'name' => 'test',
            'is_active' => false
        ];

        $this->assertStore($data, $data+['is_active' => false]);
    }

    public function testUpdate()
    {
        $this->genre = factory(Genre::class)->create([
            'is_active' => false
        ]);
        $data = [
            'name' => 'test',
            'is_active' => true
        ];
        $response = $this->assertUpdate($data, $data+['deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
    }

    public function testDestroy()
    {
        $this->assertDestroy($this->genre->id);
    }

    protected function model()
    {
        return Genre::class;
    }

    protected function routeStore()
    {
        return route('genres.store');
    }

    protected function routeUpdate()
    {
        return route('genres.update', ['genre' => $this->genre->id]);
    }

    protected function routeDestroy()
    {
        return route('genres.destroy', ['genre' => $this->genre->id]);
    }
}
