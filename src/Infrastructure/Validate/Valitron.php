<?php

namespace App\Infrastructure\Validate;

use Illuminate\Database\Capsule\Manager as DB;

class Valitron
{
    public function __construct()
    {
        $this->initCustomValidation();
    }

    private function initCustomValidation()
    {
        $this->customValidateUserCommon();

        $this->customValidateUserCompany();

        $this->customValidateUserExists();

        $this->customValidateCpfExists();

        $this->customValidateCnpjExists();

        $this->customValidateUserEmailExists();

        $this->customValidateUserNotExists();

        $this->customValidateUserCpfNotExists();

        $this->customValidateUserCnpjNotExists();

        $this->customValidateUserEmailNotExists();

        $this->customValidateBankAccountBalance();
    }

    private function customValidateUserCommon()
    {
        \Valitron\Validator::addRule('user_common_exists', function ($field, $value, array $params, array $fields) {
            $user = DB::table('user')->where('id', $value)->first();

            if ($user === null) {
                return false;
            }

            return $user->type === 'F';
        }, 'User does not exists');
    }

    private function customValidateUserCompany()
    {
        \Valitron\Validator::addRule('user_company_exists', function ($field, $value, array $params, array $fields) {
            $user = DB::table('user')->where('id', $value)->first();

            if ($user === null) {
                return false;
            }

            return $user->type === 'J';
        }, 'User does not exists');
    }

    private function customValidateUserExists()
    {
        \Valitron\Validator::addRule('user_exists', function ($field, $value, array $params, array $fields) {
            $total = DB::table('user')->where('id', $value)->count();
            return $total > 0;
        }, 'Usuário não encontrado');
    }

    private function customValidateCpfExists()
    {
        \Valitron\Validator::addRule('user_cpf_exists', function ($field, $value, array $params, array $fields) {
            $total = DB::table('user')->where('cpf_cnpj', $value)->count();
            return $total > 0;
        }, 'CPF não cadastrado na plataforma');
    }

    private function customValidateCnpjExists()
    {
        \Valitron\Validator::addRule('user_cnpj_exists', function ($field, $value, array $params, array $fields) {
            $total = DB::table('user')->where('cpf_cnpj', $value)->count();
            return $total > 0;
        }, 'CNPJ não cadastrado na plataforma');
    }

    private function customValidateUserEmailExists()
    {
        \Valitron\Validator::addRule('user_email_exists', function ($field, $value, array $params, array $fields) {
            $total = DB::table('user')->where('email', $value)->count();
            return $total > 0;
        }, 'Email não cadastrado na plataforma');
    }

    private function customValidateUserNotExists()
    {
        \Valitron\Validator::addRule('user_not_exists', function ($field, $value, array $params, array $fields) {
            $total = DB::table('user')->where('id', $value)->count();
            return $total < 1;
        }, 'Usuário não existe na plataforma');
    }

    private function customValidateUserCpfNotExists()
    {
        \Valitron\Validator::addRule('user_cpf_not_exists', function ($field, $value, array $params, array $fields) {
            $total = DB::table('user')->where('cpf_cnpj', $value)->count();
            return $total < 1;
        }, 'CPF não existe na plataforma');
    }

    private function customValidateUserCnpjNotExists()
    {
        \Valitron\Validator::addRule('user_cnpj_not_exists', function ($field, $value, array $params, array $fields) {
            $total = DB::table('user')->where('cpf_cnpj', $value)->count();
            return $total < 1;
        }, 'CNPJ já cadastrado na plataforma');
    }

    private function customValidateUserEmailNotExists()
    {
        \Valitron\Validator::addRule('user_email_not_exists', function ($field, $value, array $params, array $fields) {
            $total = DB::table('user')->where('email', $value)->count();
            return $total < 1;
        }, 'Email já cadastrado na plataforma');
    }

    private function customValidateBankAccountBalance()
    {
        \Valitron\Validator::addRule('bank_account_balance', function ($field, $value, array $params, array $fields) {
            $data = $fields[$field];
            $bankAccount = DB::table('bank_account')->where('user_id', $data['user_id'])->first();

            if ($bankAccount === null) {
                return false;
            }

            return $bankAccount->balance >= $data['amount'];
        }, 'Saldo Insuficiente');
    }
}
