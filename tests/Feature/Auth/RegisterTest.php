<?php

namespace Auth;

use App\Entity\User;
use Illuminate\Support\Str;

class RegisterTest extends \Tests\TestCase
{
    public function testForm(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200)
            ->assertSee('Register');
    }

    public function testErrors(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function testSuccess(): void
    {
        $user = factory(User::class)->make();

        $response = $this->post('/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            //'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Please, check your email and complete the registration.');
    }

    public function testVerifyIncorrect(): void
    {
        $response = $this->get('/verify/' . Str::uuid());

        $response->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('error', 'Sorry, your link cannot be identified.');
    }

    public function testVerify(): void
    {
        $user = factory(User::class)->create([
            'status' => User::STATUS_WAIT,
            'verify_token' => Str::uuid(),
        ]);

        $response = $this->get('/verify/' . $user->verify_token);

        $response->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Your email is verified. You can login now.');
    }
}
