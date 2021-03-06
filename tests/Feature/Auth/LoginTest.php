<?php

namespace Auth;

use App\Entity\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    public function testForm(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200)
            ->assertSee('Login');
    }

    public function testErrors(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function testWait(): void
    {
        $user = factory(User::class)->create(['status' => User::STATUS_WAIT]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('error', 'You need to confirm your account. Please, check your email.');
    }

    public function testActive(): void
    {
        $user = factory(User::class)->create(['status' => User::STATUS_ACTIVE]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/cabinet');

        $this->assertAuthenticated();
    }
}
