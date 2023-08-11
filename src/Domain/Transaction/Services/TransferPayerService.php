<?php

namespace App\Domain\Transaction\Services;

use App\Infrastructure\Persistence\Database;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Infrastructure\Persistence\Repositories\BankAccountRepository;
use App\Infrastructure\Persistence\Repositories\BankAccountTransactionRepository;
use App\Infrastructure\Validate\ValidateException;
use App\Infrastructure\Helper\NumberHelper;
use Psr\Log\LoggerInterface;
use Throwable;
use Exception;

class TransferPayerService
{
    public function __construct(
        private readonly Database $database,
        private readonly UserRepository $userRepository,
        private readonly BankAccountRepository $bankAccountRepository,
        private readonly BankAccountTransactionRepository $bankAccountTransactionRepository,
        private readonly TransferAuthorizeService $transferAuthorizeService,
        private readonly TransferNotificationService $transferNotificationService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function payerToPayee(int $payer, int $payee, float $amount): bool
    {
        try {
            $this->database->beginTransaction();

            // Payer not authorized to transfer
            if ($this->isPayerAuthorizatedToTransfer() === false) {
                throw new ValidateException([], 'A transferência não foi autorizada.');
            }

            // Make a transfer to payee
            $increaseStatus = $this->bankAccountRepository->increaseBalance($payee, $amount);

            // Debit the transfer value from payer
            $decreaseStatus = $this->bankAccountRepository->decreaseBalance($payer, $amount);

            if ($increaseStatus !== 1 || $decreaseStatus !== 1) {
                throw new ValidateException(
                    [],
                    'A transferência não pode ser realizada, tente novamente.'
                );
            }

            // Transaction History
            $this->addTransactionHistory($payer, $payee, $amount);

            // Notify Payee
            $this->notifyPayee($payer, $payee, $amount);

            // Commit changes
            $this->database->commit();

            return true;
        } catch (ValidateException $ex) {
            $this->database->rollBack();

            throw $ex;
        } catch (Exception | Throwable $ex) {
            $this->database->rollBack();

            $this->logger->error($ex);

            return false;
        }
    }

    private function addTransactionHistory(int $payer, int $payee, float $amount): void
    {
        $userPayer = $this->userRepository->get($payer);
        $userPayee = $this->userRepository->get($payee);

        $payerAccount = $this->bankAccountRepository->getByUserId($payer);
        $payeeAccount = $this->bankAccountRepository->getByUserId($payee);

        // Payer Transaction
        $descriptionPayer = 'Transferência para ' . $userPayee->full_name;
        $this->bankAccountTransactionRepository->add(
            $payerAccount->id,
            $amount * -1,
            $descriptionPayer
        );

        // Payee Transaction
        $descriptionPayee = 'Transferência recebida de ' . $userPayer->full_name;
        $this->bankAccountTransactionRepository->add(
            $payeeAccount->id,
            $amount,
            $descriptionPayee
        );
    }

    private function isPayerAuthorizatedToTransfer(): bool
    {
        return $this->transferAuthorizeService->isPayerAuthorizatedToTransfer();
    }

    private function notifyPayee(int $payer, int $payee, float $amount)
    {
        $userPayer = $this->userRepository->get($payer);
        $userPayee = $this->userRepository->get($payee);

        $description = 'Você recebeu uma transferência de %s no valor de %s';

        $this->transferNotificationService->notifyPayee(
            $userPayee->full_name,
            $userPayee->email,
            sprintf($description, $userPayer->full_name, NumberHelper::currencyFormat($amount))
        );
    }
}
