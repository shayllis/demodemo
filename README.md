## Sistema de pagamento simples
API simples de simulação de transações financeiras

## Tecnologias escolhidas
Laravel 8 (Framework)
Laravel Passport (Autenticação OAuth 2.0)
Laravel Queue (Agendamento de processos)
MySQL 8 (Banco)

### Justificativa das escolhas
Laravel é hoje um dos frameworks PHP mais utilizados do mundo, assim como MySQL é também um dos bancos relacionais mais utilizados. Além disso, este framework fornece complemento de autenticação (Passaport - OAuth 2.0), e agendador de serviços.
Este projeto não apresenta front-end, apenas API.
Link para outros projetos com front-end:
[Blacktag formaturas](http://blacktagformaturas.com.br/)
[Blacktag - Nova Versão em desenvolvimento (Vue.JS)](http://blacktag.com.br/)
[CuidarTBM - Projeto para Toyota Brasil](http://cuidartdb.com.br/login)
[Opera Mundi - Site de notícias](https://operamundi.uol.com.br/)

## Estrutura do código
Arquivos da API estão em:..
/app/Http/Controllers (Arquivos de controller)  
/app/Http/Resources (Arquivos auxiliares de conversão para json)    
/app/Model/ (Arquivos de Models)  
/app/Jobs/ (Arquivos de tarefas agendadas)

## Configuração
Este projeto demo possui uma pré configuração de Docker.
```
$ docker build
$ docker up -d
```

Notas:
1. Testado em Linux. Usuários Mac e Windows podem não conseguir subir as máquinas devido a configurações adicionais necessárias no compartilhamento de pastas do sistema.
2. Para executar nativamente em Unix (Mac/Linux):
Requisitos:
* PHP 7.2+ (Recomendável 7.4)
* MySQL 5.7+ (Recomendável 8)
* Composer
Iniciando o projeto:
```
$ composer install
$ php artisan migrate:fresh
$ php artisan passport:install --uuids
$ php artisan migrate
$ php artisan db:seed --class=DatabaseSeeder
$ php artisan serve
```

Configure o arquivo ".env". Use o arquivo ".env.exemple" como exemplo.

### Usuários de demonstração
1. Comum
E-mail: comum@mail.com
CPF:
Senha: password
2. Lojista
E-mail: lojista@mail.com
CPF:
Senha: password

## Uso da API
Este Docker apresenta contém um client pré configurado para iniciar a utilização.

### Gerando token
O token de acesso é nesserário para utilizar os demais recursos desta API.

```
POST {HOST}/oauth/token
{
    "grant_type": "password",
    "client_id": "9208bf87-975f-473f-8458-e3e3b7025394",
    "client_secret": "OD1NI4eUGsXSlXvPdksA4u7IF89FURrw17MAi0Mb",
    "username": "comum@mail.com",
    "password": "password",
    "scope": "*"
}
Response {
    {
        "token_type": "Bearer",
        "expires_in": 604800,
        "access_token": "{ACCESS_TOKEN}",
        "refresh_token": "{REFRESH_TOKEN}"
    }
}
```

**Copie o Access Token para reutilizar nas demais requisições**

### Criando uma transferência
```
POST {HOST}/api/transactions
{
    "value": "1.00",
    "payee": 2
}
Response {
    "success": true,
    "data": {
        "id": "fbced86a-3875-4a8d-9106-cbc7a892f397",
        "amount": "1.00",
        "from": {
            "id": 1,
            "name": "Comum"
        },
        "to": {
            "id": 2,
            "name": "Lojista"
        }
    },
    "message": "Transaction has been created successfully."
}
```
**Nota**: A estrutura foi modificada, por segurança o "payer" será definido pelo usuário dono do Token em uso.

### Listando transferências
```
GET {HOST}/api/transactions
{
    "success": true,
    "data": [
        {
            "id": "{{Transaction UUID}}",
            "amount": 1,
            "date": "YYYY-MM-dd HH:ii:ss",
            "from": {
                "id": 1,
                "name": "Comum"
            },
            "to": {
                "id": 2,
                "name": "Lojista"
            }
        },
    }
}
```

### Notificações
Notificações são enviadas através de um worker que é executado a cada 5 minutos.
**Nota**: No mundo real seria feito em micro serviço agendado via client.

### Sugestão de melhorias:
* Adicionar lista de contatos e transacionar pelo código do contato (evitar expor ids ou dados pessoais)
* Utilizar dados do token para transacionar, ao invés de receber do cliente seu próprio código
* Armazenar dados de sessão e tarefas agendadas em Redis
* Integrar serviço de Log com StackOverflow e/ou outro serviço mensageiro de um responsável do time de dev

### Considerações
1. CPF e CPNJ são compreendidos como documento (document) na base de dados
1. Note que a lista apresenta o UUID, evitando export o id real da transação
1. As requisições feitas para "APIs" externas têm prevenção de erro
1. É possível reverter a operação usando status. Porém não foi implementada (falta detalhe do usuário responsável pela operação).
1. Testes nesse momento são feitos manualmente via Postman (PHPUnit aplicável -> não feito pois agenda conflitou com projeto de TCC).
