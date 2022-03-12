<?php

namespace Entity\User;

use App\Entity\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    public function testCreateByAdmin()
    {
        $user = User::createByAdmin(
            $name = 'name',
            $email = 'email'
        );

        self::assertNotEmpty($user);

        self::assertEquals($name, $user->name);
        self::assertEquals($email, $user->email);
        self::assertNotEmpty($user->password);

        self::assertTrue($user->isActive());
        self::assertFalse($user->isAdmin());
    }
}
