<?php

namespace App\Infrastructure\Validate\Transaction;

use App\Infrastructure\Validate\ValidateInterface;
use App\Infrastructure\Validate\Valitron;
use Valitron\Validator;

class TransferPayerToPayeeValitron extends Valitron implements ValidateInterface
{
    private Validator $v;

    public function __construct()
    {
        parent::__construct();
    }

    public function validate(array $data): bool
    {
        $this->v = new \Valitron\Validator($data);
        $this->v->stopOnFirstFail(true);
        $this->v->rule('required', ['payer', 'payee', 'value'])->message('{field} é obrigatório');
        $this->v->rule('numeric', 'value')->message('Valor inválido');

        if ($this->v->validate() === false) {
            return false;
        }

        $mergedData = array_merge(
            $data,
            ['account_balance' =>
                [
                    'user_id' => $data['payer'],
                    'amount' => $data['value']
                ]
            ]
        );

        $this->v = new \Valitron\Validator($mergedData);
        $this->v->stopOnFirstFail(true);
        $this->v->rule('user_common_exists', 'payer')->message('Apenas pessoa fisíca pode fazer uma transferência');
        $this->v->rule('user_exists', 'payee')->message('Usuário não existe');
        $this->v->rule('bank_account_balance', 'account_balance')->message('Sem saldo para fazer a transferência');

        return $this->v->validate();
    }

    public function getErrors(): array | bool | null
    {
        return $this->v->errors();
    }
}
