<?php

declare(strict_types=1);

namespace Tests\Domain\User\Feature;

use App\Infrastructure\Persistence\Models\User;
use App\Infrastructure\Persistence\Models\BankAccount;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        DB::table((new BankAccount())->getTable())->delete();
        DB::table((new User())->getTable())->delete();
    }

    /**
     * @dataProvider userProvider
     */
    public function testAddUser($fullName, $cpfCnpj, $email, $type, $password)
    {
        $app = $this->getAppInstance();

        $createdRequest = $this->createRequest('POST', '/user/signup');
        $request = $createdRequest->withParsedBody([
            'full_name' => $fullName,
            'cpf_cnpj' => $cpfCnpj,
            'email' => $email,
            'password' => $password,
            'type' => $type
        ]);

        $response = $app->handle($request);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function userProvider()
    {
        return [
            ['Marcia Toledo', '24541783037', '24541783037@yahoo.com', 'fisica', 'Senha123']
        ];
    }
}
