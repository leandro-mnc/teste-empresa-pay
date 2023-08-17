<?php

namespace App\Infrastructure\Validate;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Valitron\Validator;

class Valitron
{
    private UserRepository $userRepository;
    
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->userRepository = $this->entityManager->getRepository(User::class);
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
        Validator::addRule('user_common_exists', function ($field, $value, array $params, array $fields) {
            /** @var User $user */
            $user = $this->userRepository->get($value);

            if ($user === null) {
                return false;
            }

            return $user->getType() === 'Fisíca';
        }, 'User does not exists');
    }

    private function customValidateUserCompany()
    {
        Validator::addRule('user_company_exists', function ($field, $value, array $params, array $fields) {
            /** @var User $user */
            $user = $this->userRepository->get($value);

            if ($user === null) {
                return false;
            }

            return $user->type === 'Jurídica';
        }, 'User does not exists');
    }

    private function customValidateUserExists()
    {
        Validator::addRule('user_exists', function ($field, $value, array $params, array $fields) {
            /** @var User $user */
            $user = $this->userRepository->get($value);

            return $user instanceof User;
        }, 'Usuário não encontrado');
    }

    private function customValidateCpfExists()
    {
        Validator::addRule('user_cpf_exists', function ($field, $value, array $params, array $fields) {
            /** @var User $user */
            $exists = $this->userRepository->cpfCnpjExists($value);

            return $exists;
        }, 'CPF não cadastrado na plataforma');
    }

    private function customValidateCnpjExists()
    {
        Validator::addRule('user_cnpj_exists', function ($field, $value, array $params, array $fields) {            
            /** @var User $user */
            $exists = $this->userRepository->cpfCnpjExists($value);

            return $exists;
        }, 'CNPJ não cadastrado na plataforma');
    }

    private function customValidateUserEmailExists()
    {
        Validator::addRule('user_email_exists', function ($field, $value, array $params, array $fields) {
            /** @var User $user */
            $exists = $this->userRepository->emailExists($value);

            return $exists;
        }, 'Email não cadastrado na plataforma');
    }

    private function customValidateUserNotExists()
    {
        Validator::addRule('user_not_exists', function ($field, $value, array $params, array $fields) {
            /** @var User $user */
            $user = $this->userRepository->get($value);

            return $user === null;
        }, 'Usuário não existe na plataforma');
    }

    private function customValidateUserCpfNotExists()
    {
        Validator::addRule('user_cpf_not_exists', function ($field, $value, array $params, array $fields) {
            /** @var User $user */
            $exists = $this->userRepository->cpfCnpjExists($value);
            
            return $exists === false;
        }, 'CPF não existe na plataforma');
    }

    private function customValidateUserCnpjNotExists()
    {
        Validator::addRule('user_cnpj_not_exists', function ($field, $value, array $params, array $fields) {
            /** @var User $user */
            $exists = $this->userRepository->cpfCnpjExists($value);

            return $exists === false;
        }, 'CNPJ já cadastrado na plataforma');
    }

    private function customValidateUserEmailNotExists()
    {
        Validator::addRule('user_email_not_exists', function ($field, $value, array $params, array $fields) {
            /** @var User $user */
            $exists = $this->userRepository->emailExists($value);

            return $exists === false;
        }, 'Email já cadastrado na plataforma');
    }

    private function customValidateBankAccountBalance()
    {
        Validator::addRule('bank_account_balance', function ($field, $value, array $params, array $fields) {
            $data = $fields[$field];
            $bankAccount = DB::table('bank_account')->where('user_id', $data['user_id'])->first();

            if ($bankAccount === null) {
                return false;
            }

            return $bankAccount->balance >= $data['amount'];
        }, 'Saldo Insuficiente');
    }
}
