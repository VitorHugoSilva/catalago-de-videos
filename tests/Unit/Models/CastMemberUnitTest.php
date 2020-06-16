<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class CastMemberUnitTest extends TestCase
{
    use DatabaseMigrations;

    private $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = new CastMember();
    }

    public function testFillableAttribute()
    {
        $fillable = [ 'name', 'type'];

        $this->assertEquals($fillable, $this->castMember->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];
        $castMemberTraits = array_keys(class_uses(CastMember::class));
        $this->assertEquals($traits, $castMemberTraits);
    }

    public function testCats()
    {
        $casts = ['id' => 'string', 'type' => 'integer'];
        $castMember  = $this->castMember;
        $this->assertEquals($casts, $castMember->getCasts());
    }

    public function testIncrementing()
    {
        $castMember  = new CastMember();
        $this->assertFalse($castMember->incrementing);
    }

    public function testsDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $castMember  = $this->castMember;
        foreach ($dates as $date) {
            $this->assertContains($date, $castMember->getDates());
        }
        $this->assertCount(count($dates), $castMember->getDates());
    }
}
