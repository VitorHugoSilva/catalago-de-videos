<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;
use Tests\Traits\TestDestroy;

class CastMemberControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves, TestDestroy;

    protected $castMember;
    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = factory(CastMember::class)->create();
    }

    public function testIndex()
    {
       $response = $this->get(route('cast_members.index'));

       $response->assertStatus(200)
           ->assertJson([$this->castMember->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('cast_members.show', ['cast_member' => $this->castMember->id]));

        $response->assertStatus(200)
            ->assertJson($this->castMember->toArray());
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
            'type' => ''
        ];
        $this->assertInvalidationInStoreAction($data, 'required');

        $data = [
            'type' => 100
        ];
        $this->assertInvalidationInStoreAction($data, 'in');
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
            'type' => ''
        ];
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data = [
            'type' => 100
        ];
        $this->assertInvalidationInStoreAction($data, 'in');
    }

    public function testStore()
    {

        $data = ['name' => 'test', 'type' => CastMember::TYPE_DIRECTOR];

        $response = $this->assertStore($data, $data+['type' => CastMember::TYPE_DIRECTOR, 'deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
        $data = [
            'name' => 'test',
            'type' => CastMember::TYPE_ACTOR
        ];

        $this->assertStore($data, $data+['type' => CastMember::TYPE_ACTOR]);
    }

    public function testUpdate()
    {
        $this->castMember = factory(CastMember::class)->create([
            'type' => CastMember::TYPE_ACTOR
        ]);
        $data = [
            'name' => 'test',
            'type' => CastMember::TYPE_DIRECTOR
        ];
        $response = $this->assertUpdate($data, $data+['deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
    }

    public function testDestroy()
    {
        $this->assertDestroy($this->castMember->id);
    }

    protected function model()
    {
        return CastMember::class;
    }

    protected function routeStore()
    {
        return route('cast_members.store');
    }

    protected function routeUpdate()
    {
        return route('cast_members.update', ['cast_member' => $this->castMember->id]);
    }

    protected function routeDestroy()
    {
        return route('cast_members.destroy', ['cast_member' => $this->castMember->id]);
    }
}
