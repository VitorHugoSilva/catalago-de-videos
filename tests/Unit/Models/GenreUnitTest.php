<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class GenreUnitTest extends TestCase
{
    use DatabaseMigrations;

    private $genre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = new Genre();
    }

    public function testFillableAttribute()
    {
        $fillable = [
        'name',
        'is_active'
    ];

        $this->assertEquals($fillable, $this->genre->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];
        $genreTraits = array_keys(class_uses(Genre::class));
        $this->assertEquals($traits, $genreTraits);
    }

    public function testCats()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];
        $genre  = $this->genre;
        $this->assertEquals($casts, $genre->getCasts());
    }

    public function testIncrementing()
    {
        $genre  = new Genre();
        $this->assertFalse($genre->incrementing);
    }

    public function testsDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $genre  = $this->genre;
        foreach ($dates as $date) {
            $this->assertContains($date, $genre->getDates());
        }
        $this->assertCount(count($dates), $genre->getDates());
    }
}
