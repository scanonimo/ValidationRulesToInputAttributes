<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;
use Tests\TestCase;

class UserTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function must_have_a_username() {
        $user = User::factory()->create();

        $this->assertNotEmpty($user->username);
    }

    /** @test */
    public function email_can_be_null() {
        $this->expectNotToPerformAssertions();

        User::factory()->create([
            'email' => null
        ]);
    }

    /** @test */
    public function username_must_be_unique_case_insensitively() {
        $user = User::factory()->create();

        $this->assertThrows(
                fn() => User::factory()->create(['username' => strtoupper($user->username)]),
                expectedMessage: 'Duplicate entry'
        );
    }

    /** @test */
    function mass_assignment_protection_should_be_disabled() {
        $userModel = new User;
        $reflection = (new ReflectionClass($userModel));
        $this->assertEmpty($reflection->getProperty('guarded')
                        ->getValue($userModel));
        $this->assertEmpty($reflection->getProperty('fillable')
                        ->getValue($userModel));
    }

}
