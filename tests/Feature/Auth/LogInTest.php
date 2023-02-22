<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use function __;

class LogInTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function page_is_reachable_by_unlogged_users() {
        $this->get('/')->assertSee('/login');

        $this->get('login')->assertStatus(200);
    }

    /** @test */
    public function page_is_not_reachable_by_logged_users() {
        $this->signIn();

        $this->get('/')->assertDontSee('/login');

        $this->get('login')->assertRedirect('dashboard');
    }

    /** @test */
    public function unlogged_users_can() {
        $user = User::factory()
                ->create(['password' => Hash::make('password')]);

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'password'
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function logged_users_can_not() {
        $john = $this->signIn();

        $carol = User::factory()
                ->create(['password' => Hash::make('password')]);

        $this->post('/login', [
            'username' => $carol->username,
            'password' => 'password'
        ])->assertRedirect('dashboard');

        $this->assertAuthenticatedAs($john);
    }

    /** @test */
    public function submitting_the_wrong_credentials_should_return_a_warning() {
        $user = User::factory()->create();

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'wrong password'
        ])->assertSessionHasErrors([
            'username' => __('auth.failed')
        ]);

        $this->assertGuest();
    }

    /** @test */
    public function test_temporally_username_locked_out_after_some_failed_attemps() {
        $user = User::factory()
                ->create(['password' => Hash::make('password')]);

        for ($x = 0; $x <= 6; $x++) {
            $this->post('/login', [ 'username' => $user->username,
                'password' => 'wrong password'
            ])->assertSessionHasErrors('username');
        }

        $this->post('/login', [ 'username' => $user->username,
            'password' => 'password'
        ])->assertSessionHasErrors('username');

        $this->assertGuest();

        $this->assertExpectedErrorWasReceivedUsingAPattern(
                __('auth.throttle', ['seconds' => '*']),
                'username'
        );
        
        Carbon::setTestNow(Carbon::now()->addMinutes(3));
        
        $this->post('/login', [ 'username' => $user->username,
            'password' => 'password'
        ])->assertSessionDoesntHaveErrors();
        
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function submitting_invalid_log_in_data_should_return_errors() {
        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->assertInvalidDataReturnsErrors('/login', [
            [['username', 'password'], '', 'required'],
            [['username', 'password'], $file, 'string']
        ]);
    }

}
