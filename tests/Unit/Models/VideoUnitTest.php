<?php

namespace Tests\Unit\Models;

use App\Models\Video;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class VideoUnitTest extends TestCase
{
    use DatabaseMigrations;

    private $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = new Video();
    }

    public function testFillableAttribute()
    {
        $fillable = [
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration'
        ];

        $this->assertEquals($fillable, $this->video->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];
        $videoTraits = array_keys(class_uses(Video::class));
        $this->assertEquals($traits, $videoTraits);
    }

    public function testCats()
    {
        $casts = [
            'id' => 'string',
            'opened' => 'boolean',
            'year_launched' => 'integer',
            'duration' => 'integer',
        ];
        $video  = $this->video;
        $this->assertEquals($casts, $video->getCasts());
    }

    public function testIncrementing()
    {
        $video  = new Video();
        $this->assertFalse($video->incrementing);
    }

    public function testsDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $video  = $this->video;
        foreach ($dates as $date) {
            $this->assertContains($date, $video->getDates());
        }
        $this->assertCount(count($dates), $video->getDates());
    }
}
