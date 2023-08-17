<?php

namespace App\Infrastructure\Validate\User;

use App\Infrastructure\Validate\ValidateInterface;
use App\Infrastructure\Validate\Valitron;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Valitron\Validator;

class UserSignupValitron extends Valitron implements ValidateInterface
{
    private Validator $v;

    public function __construct(ContainerInterface $c)
    {
        parent::__construct($c->get(EntityManagerInterface::class));
    }

    public function validate(array $data)
    {
        $this->v = new \Valitron\Validator($data);
        $this->v->stopOnFirstFail(true);
        $this->v->rule('required', ['full_name', 'cpf_cnpj', 'email', 'password', 'type'])
                ->message('{field} é obrigatório');
        $this->v->labels([
            'full_name' => 'Nome Completo',
            'cpf_cnpj' => 'CPF / CNPJ',
            'email' => 'Email',
            'password' => 'Senha',
            'type' => 'Tipo de Conta'
        ]);
        $this->v->rule('lengthMin', 'password', 6)->message('A senha precisa ter no minímo {lengthMin} carácteres');
        $this->v->rule('in', 'type', ['fisica', 'juridica'])->message('Apenas "fisica" ou "juridica" está disponível');

        if ($this->v->validate() === false) {
            return false;
        }

        $this->v = new \Valitron\Validator($data);
        $this->v->stopOnFirstFail(true);
        if ($data['type'] === 'fisica') {
            $this->v->rule('user_cpf_not_exists', 'cpf_cnpj')->message('CPF já cadastrado na plataforma');
        } else {
            $this->v->rule('user_cnpj_not_exists', 'cpf_cnpj')->message('CNPJ já cadastrado na plataforma');
        }
        $this->v->rule('user_email_not_exists', 'email')->message('Email já cadastrado na plataforma');

        return $this->v->validate();
    }

    public function getErrors()
    {
        return $this->v->errors();
    }
}
