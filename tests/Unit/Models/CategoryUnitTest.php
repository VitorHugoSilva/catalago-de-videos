<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class CategoryUnitTest extends TestCase
{
    use DatabaseMigrations;

    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }

    public function testFillableAttribute()
    {
        $fillable = [
        'name',
        'description',
        'is_active'
    ];

        $this->assertEquals($fillable, $this->category->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];
        $categoryTraits = array_keys(class_uses(Category::class));
        $this->assertEquals($traits, $categoryTraits);
    }

    public function testCats()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];
        $category  = $this->category;
        $this->assertEquals($casts, $category->getCasts());
    }

    public function testIncrementing()
    {
        $category  = new Category();
        $this->assertFalse($category->incrementing);
    }

    public function testsDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $category  = $this->category;
        foreach ($dates as $date) {
            $this->assertContains($date, $category->getDates());
        }
        $this->assertCount(count($dates), $category->getDates());
    }
}
