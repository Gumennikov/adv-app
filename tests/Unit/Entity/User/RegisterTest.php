<?php


namespace Entity\User;


use App\Entity\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends \Tests\TestCase
{
    use DatabaseTransactions; //Откатывает БД в исходное состояние после заверешения тестов

    public function testRequest(): void
    {
        $user = User::register(
            $name = 'name',
            $email = 'email',
            $password = 'password'
        );

        self::assertNotEmpty($user);

        self::assertEquals($name, $user->name);
        self::assertEquals($email, $user->email);

        self::assertNotEmpty($user->password);
        self::assertNotEquals($password, $user->password);

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());
        self::assertFalse($user->isAdmin());
    }

    public function testVerify(): void
    {
        $user = User::register('name','email', 'password');

        $user->verify();

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());
    }

    public function testAlreadyVerified(): void
    {
        $user = User::register(
            $name = 'name',
            $email = 'email',
            $password = 'password'
        );

        $user->verify();

        $this->expectExceptionMessage('User is already verified.');
        $user->verify();
    }
}
