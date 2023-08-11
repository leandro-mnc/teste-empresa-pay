<?php

declare(strict_types=1);

namespace Tests\Domain\User\Unit;

use App\Application\Actions\User\Validate\UserSignupValidate;
use Tests\TestCase;

class UserValidateTest extends TestCase
{
    /**
     * @dataProvider userProvider
     */
    public function testUserSignupValidate($fullName, $cpfCnpj, $email, $type, $password)
    {
        $validate = new UserSignupValidate();

        $this->assertTrue($validate->validate([
            'full_name' => $fullName,
            'cpf_cnpj' => $cpfCnpj,
            'email' => $email,
            'type' => $type,
            'password' => $password
        ]));
    }

    public function userProvider()
    {
        return [
            ['Maria da Silva', '97307308061', '97307308061@yahoo.com', 'fisica', 'Senha123'],
            ['Pedro dos Santos', '69393268000137', '69393268000137@yahoo.com', 'juridica', 'Senha123']
        ];
    }
}
