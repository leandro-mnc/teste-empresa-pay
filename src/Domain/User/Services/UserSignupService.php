<?php

namespace App\Domain\User\Services;

use App\Application\Actions\User\Validate\UserSignupValidate;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Infrastructure\Persistence\Database;
use App\Infrastructure\Persistence\Repositories\BankAccountRepository;
use App\Infrastructure\Validate\ValidateException;
use App\Infrastructure\Persistence\Models\User;
use App\Infrastructure\Persistence\Models\BankAccount;
use Psr\Log\LoggerInterface;
use Throwable;

class UserSignupService
{
    public function __construct(
        private readonly Database $database,
        private readonly UserRepository $repository,
        private readonly BankAccountRepository $bankAccountRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    public function add(array $data): User
    {
        try {
            $this->database->beginTransaction();

            // Validate
            $this->validate($data);

            // Add user to database
            $user = $this->repository->add(
                $data['full_name'],
                $data['cpf_cnpj'],
                $data['email'],
                $data['password'],
                $data['type'],
            );

            if ($user === null) {
                throw new ValidateException(
                    [],
                    'Não foi possível cadastrar o usuário'
                );
            }

            // Add bank account
            $this->addBankAccount($user->id);

            $this->database->commit();

            return $user;
        } catch (ValidateException $ex) {
            $this->database->rollBack();
            throw $ex;
        } catch (Throwable $ex) {
            $this->database->rollBack();

            $this->logger->error($ex);

            throw $ex;
        }
    }

    private function validate(array $data): bool
    {
        $validate = new UserSignupValidate();

        $valid = $validate->validate($data);

        if ($valid === false) {
            throw new ValidateException(
                $validate->getErrors(),
                'Não foi possível cadastrar o usuário'
            );
        }

        return $valid;
    }

    private function addBankAccount(int $user_id, float $balance = 0.00): BankAccount
    {
        $bankAccount = $this->bankAccountRepository->add($user_id, $balance);

        if ($bankAccount === null) {
            throw new ValidateException(
                [],
                'Não foi possível cadastrar o usuário'
            );
        }

        return $bankAccount;
    }
}
