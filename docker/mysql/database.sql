CREATE TABLE user (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `full_name` VARCHAR(100) NOT NULL,
    `cpf_cnpj` CHAR(14) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(100) NOT NULL,
    `type` CHAR(1) DEFAULT 'F',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL
);

CREATE UNIQUE INDEX `user_cpf_cnpj_idx` ON user (`cpf_cnpj`);
CREATE UNIQUE INDEX `user_email_idx` ON user (`email`);

CREATE TABLE bank_account (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `balance` DECIMAL(12, 2) DEFAULT 0.00,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL
);

ALTER TABLE bank_account ADD CONSTRAINT bank_account_user_id FOREIGN KEY (`user_id`) REFERENCES user (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION;

CREATE TABLE bank_account_transaction (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `bank_account_id` INT UNSIGNED NOT NULL,
    `amount` DECIMAL(12, 2) DEFAULT 0.00,
    `description` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL
);

ALTER TABLE bank_account_transaction ADD CONSTRAINT payer_bank_account_id 
FOREIGN KEY (`bank_account_id`) REFERENCES bank_account (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION,
ADD CONSTRAINT payee_bank_account_id 
FOREIGN KEY (`bank_account_id`) REFERENCES bank_account (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION;
