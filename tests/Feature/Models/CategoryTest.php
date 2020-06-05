<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Category::class, 1)->create();
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $categoryKey = array_keys($categories->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id',
            'name',
            'description',
            'is_active',
            'created_at',
            'updated_at',
            'deleted_at'
        ], $categoryKey);
    }
    public function testCreate()
    {
        $category = Category::create([
            'name' => 'test 1'
        ]);
        $category->refresh();
        $this->assertEquals('test 1', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        $category = Category::create([
            'name' => 'test 1',
            'description' => null
        ]);
        $this->assertNull($category->description);

        $category = Category::create([
            'name' => 'test 1',
            'description' => 'test_description'
        ]);
        $this->assertEquals($category->description, 'test_description');

        $category = Category::create([
            'name' => 'test 1',
            'is_active' => false
        ]);
        $this->assertFalse($category->is_active);
    }





}
