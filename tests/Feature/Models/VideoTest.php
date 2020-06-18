<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Video::class, 1)->create();
        $videos = Video::all();
        $this->assertCount(1, $videos);
        $videoKey = array_keys($videos->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id',
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration',
            'created_at',
            'updated_at',
            'deleted_at'
        ], $videoKey);
    }
    public function testCreate()
    {
        $video = Video::create([
            'title' => 'title',
            'description' => 'description',
            'year_launched' => 2010,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90,
            'opened' => true
        ]);
        $video->refresh();
        $this->assertEquals(36, strlen($video->id));
        $this->assertEquals('title', $video->title);
        $this->assertEquals('description', $video->description);
        $this->assertEquals(2010, $video->year_launched);
        $this->assertEquals(Video::RATING_LIST[0], $video->rating);
        $this->assertEquals(90, $video->duration);
        $this->assertTrue($video->opened);

        $video = Video::create([
            'title' => 'title',
            'description' => 'description',
            'year_launched' => 2010,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90
        ]);
        $video->refresh();
        $this->assertFalse($video->opened);
    }

    public function testUpdate()
    {
        /** @var Video $video*/
        $video = factory(Video::class)->create([
            'rating' => Video::RATING_LIST[0],
        ]);
        $data =[
            'rating' => Video::RATING_LIST[1],
            'opened' => true
        ];

        $video->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $video->{$key});
        }

    }

    public function testDelete()
    {
        /** @var Video $video*/
        $video = factory(Video::class)->create();
        $video->delete();
        $this->assertNull(Video::find($video->id));
        $video->restore();
        $this->assertNotNull(Video::find($video->id));
    }

}
