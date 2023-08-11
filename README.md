# Teste para empresa Pay

[![Tests](https://github.com/leandro-mnc/teste-empresa-pay/actions/workflows/tests.yml/badge.svg)](https://github.com/leandro-mnc/teste-empresa-pay/actions/workflows/tests.yml)

### Configuração

Primeiro passo é fazer uma cópia do arquivo **.env.example** para **.env**.

Feito a cópia, inicie o container:

```
docker-compose up -d
```

Você pode acompanhar o log dos containers com o comando abaixo:

```
docker-compose logs --follow
```

### Postman

Na raiz do projeto existe o arquivo do **postman_collection.json**, que deve ser utilizado com o software [Postman](https://www.postman.com/downloads/).

### Testes

Para executar os tests, execute o comando abaixo na raiz do projeto:

```
docker-compose exec slim composer run test
```