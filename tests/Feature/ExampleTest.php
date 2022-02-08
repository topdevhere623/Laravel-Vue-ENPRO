<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');
        //$response->dumpHeaders();
        $response->assertStatus(200);
    }

    public function testSubstationList()
    {
        $this->seed(\AdminSettingTableSeeder::class);
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->get('/admin');
        //$response->dumpHeaders();
        //$response->dumpSession();
        //file_put_contents('test.html', $response->getContent());
        $response->assertRedirect('/login');
    }
}
