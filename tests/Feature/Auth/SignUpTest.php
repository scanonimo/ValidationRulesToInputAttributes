<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class SignUpTest extends TestCase {

    use RefreshDatabase;
    
    /** @test */
    public function page_is_reachable_by_guests() {
        $this->get('/')->assertSee('/signup');

        $this->get('signup')->assertStatus(200);
    }

    /** @test */
    public function page_is_not_reachable_by_logged_users() {
        $this->signIn();

        $this->get('/')->assertDontSee('/signup');

        $this->get('signup')->assertRedirect('dashboard');
    }

    /** @test */
    public function guests_can() {
        $this->post('/signup', $this->validSingUpData());

        $this->assertDatabaseCount('users', 1);
    }

    /** @test */
    public function logged_users_can_not() {
        $this->signIn();

        $this->post('/signup', $this->validSingUpData())
                ->assertRedirect('dashboard');

        $this->assertDatabaseCount('users', 1);
    }
    
    /** @test */
    public function after_signing_up_the_new_user_should_be_logged() {
        $this->post('/signup', $this->validSingUpData())
                ->assertRedirect('dashboard');
        
        $this->assertAuthenticatedAs(User::first());
    }

    /** @test */
    public function submitting_invalid_sign_up_data_should_return_errors() {
        $file = UploadedFile::fake()->image('avatar.jpg');
        
        $takenUsername = User::factory()->create()->username;
        
        $this->assertInvalidDataReturnsErrors('/signup', [
            [['username', 'password'], '', 'required'],
            [['username', 'password'], $file, 'string'],
            ['username', 'xx', 'min:3'],
            ['username', str_repeat('x', 65), 'max:64'],
            [
                'username', 
                '0inv@lid-user..name.', 
                'regex:/^[a-z]([a-z0-9]|[a-z0-9]\.[a-z0-9])*$/i'
            ],
            ['username', $takenUsername, 'unique:'.User::class],
            ['password', 'without confirmation', 'confirmed'],
            ['password', 'short', Password::min(6)],
        ]);

    }
    
    private function validSingUpData() {
        return User::factory()->raw([
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
    }

}
