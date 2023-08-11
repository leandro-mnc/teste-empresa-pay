<?php

declare(strict_types=1);

namespace Tests\Domain\Transaction\Unit;

use App\Application\Actions\Transaction\Validate\TransferPayerToPayeeValidate;
use App\Infrastructure\Persistence\Models\BankAccountTransaction;
use App\Infrastructure\Persistence\Models\BankAccount;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Database\Capsule\Manager as DB;
use Tests\TestCase;

class TransferPayerToPayeeValidateTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        DB::table((new BankAccountTransaction())->getTable())->delete();
        DB::table((new BankAccount())->getTable())->delete();
        DB::table((new User())->getTable())->delete();
    }

    public function testValidateTransferPayer()
    {
        $validate = new TransferPayerToPayeeValidate();

        $users = $this->addUsers();

        $this->assertTrue($validate->validate([
                    'payer' => $users[0],
                    'payee' => $users[1],
                    'value' => 100.0
        ]));
    }

    public function addUsers()
    {
        $users = [
            ['Maria da Silva', '97307308061', '97307308061@yahoo.com', 'fisica', 'Senha123', 100.00],
            ['Pedro dos Santos', '69393268000137', '69393268000137@yahoo.com', 'juridica', 'Senha123', 0.00]
        ];

        $result = [];

        foreach ($users as $user) {
            $userModel = new User([
                'full_name' => $user[0],
                'cpf_cnpj' => $user[1],
                'email' => $user[2],
                'type' => $user[3],
                'password' => $user[4],
            ]);
            $userModel->save();

            (new BankAccount([
                'user_id' => $userModel->id,
                'balance' => $user[5]
                    ]))->save();

            $result[] = $userModel->id;
        }

        return $result;
    }
}
