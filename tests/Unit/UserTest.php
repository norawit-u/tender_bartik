<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', '/user', [
            'fname' => 'Sally',
            'lname' => 'Sally',
            'password' => '123456',
            'c_password' => '123456',
            'address' => 'Sally',
            'telno' => 'Sally',
            'fb' => 'Sally',
            'ig' => 'Sally',
            'line' => 'Sally',
            'department' => 'Sally',
            'role' => 'Sally',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'created' => true,
            ]);
    }
}
