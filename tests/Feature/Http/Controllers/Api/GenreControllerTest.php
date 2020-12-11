<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
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
            'name' => '',
            'categories_id' => ''
        ];
        $this->assertInvalidationInStoreAction($data, 'required');

        $data = [
            'name' => str_repeat('a', 256)
        ];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max'=>255]);

        $data = [
            'is_active' => 'a'
        ];

        $this->assertInvalidationInStoreAction($data, 'boolean');

        $data = [
            'categories_id' => 'a'
        ];
        $this->assertInvalidationInStoreAction($data, 'array');

        $data = [
            'categories_id' => ["0f5c8a0d-2fd3-4013-9553-ffb09d2461e8"]
        ];
        $this->assertInvalidationInStoreAction($data, 'exists');

        $category = factory(Category::class)->create();
        $category->delete();
        $data = [
            'categories_id' => [$category->id]
        ];
        $this->assertInvalidationInUpdateAction($data, 'exists');
    }

    public function testInvalidationDateUpdate()
    {

        $data = [
            'name' => '',
            'categories_id' => ''
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
        $data = [
            'categories_id' => 'a'
        ];
        $this->assertInvalidationInUpdateAction($data, 'array');

        $data = [
            'categories_id' => ["0f5c8a0d-2fd3-4013-9553-ffb09d2461e8"]
        ];
        $this->assertInvalidationInUpdateAction($data, 'exists');

        $category = factory(Category::class)->create();
        $category->delete();
        $data = [
            'categories_id' => [$category->id]
        ];
        $this->assertInvalidationInUpdateAction($data, 'exists');

    }

    public function testStore()
    {
        $category = factory(Category::class)->create();
        $data = [
            'name' => 'test',
            'categories_id' => [$category->id]
        ];
        $response = $this->assertStore($data, $data+['is_active' => true, 'deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
        $this->assertHasCategory($response->json('id'), $category->id);
        $data = [
            'name' => 'test',
            'is_active' => false
        ];

        $this->assertStore($data, $data+['is_active' => false]);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create();
        $this->genre = factory(Genre::class)->create([
            'is_active' => false,
        ]);
        $data = [
            'name' => 'test',
            'is_active' => true,
            'categories_id' => [$category->id]
        ];
        $response = $this->assertUpdate($data, $data+['deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
        $this->assertHasCategory($response->json('id'), $category->id);
    }

    protected function assertHasCategory($genreId, $categoryId)
    {
        $this->assertDatabaseHas('category_genre', [
           'genre_id' => $genreId,
           'category_id' => $categoryId
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
