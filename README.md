# Scrapter Project

## Descrição
Este projeto é um crawler de moeda desenvolvido com Laravel, que busca dados de moedas a partir da página ISO 4217 da Wikipedia e os analisa para uso em uma aplicação financeira. O projeto é configurado para ser executado em um ambiente Dockerizado com Laravel Sail.

## Pré-requisitos
Antes de começar, você precisa ter o Docker instalado em seu sistema. Se você está usando Windows ou Mac, Docker Desktop é a opção recomendada.

## Configuração

### Clonando o Repositório

Clone o repositório no seu local de trabalho:

```bash
git clone git@github.com:andreazevedobauru/scrapter.git
cd scrapter
```

## Iniciando com Laravel Sail
Laravel Sail está pré-configurado no arquivo docker-compose.yml do projeto. Para iniciar o ambiente de desenvolvimento, primeiro copie o arquivo .env.example para .env:

```
cp .env.example .env
```

## Depois, inicie o Sail. Se esta for a primeira vez que você está rodando o Sail, ele também instalará todas as dependências do projeto:

```bash
./vendor/bin/sail up
```

Se você não tiver as dependências do Composer instaladas, você pode executar o Composer através do Sail:

```bash
./vendor/bin/sail composer install
```

## Geração de Chave
Gere a chave da aplicação Laravel através do Sail:
```bash
./vendor/bin/sail artisan key:generate
```

## Execução
Para acessar a aplicação, você pode usar o servidor web embutido do Sail. A aplicação estará disponível por padrão em http://localhost.

## Testes
Execute os testes utilizando o Sail:

```bash
./vendor/bin/sail artisan test
```

Ou diretamente via PHPUnit no container:
```bash
./vendor/bin/sail exec laravel.test vendor/bin/phpunit
```

## Estrutura do Projeto
Neste projeto optei por utilzar o padrão de Services, pois assim podemos encapsular a lógica de negócios. Utilizamos nos arquivos:

### CurrencyClientService

#### Separação de Concerns e Abstração de Complexidade
O CurrencyClientService abstrai todos os detalhes da comunicação HTTP. Consumidores deste serviço não precisam saber nada sobre como as requisições HTTP são feitas; eles apenas precisam de uma maneira de obter o HTML.

#### Facilidade de Teste 
Isolando a funcionalidade de fazer requisições HTTP, você pode facilmente mockar respostas HTTP nos testes, garantindo que o serviço possa ser testado sem dependência de recursos externos.

### CurrencyParserService
Este serviço lida com o processamento do HTML obtido para extrair informações sobre as moedas. Ele usa o Symfony DomCrawler para analisar o HTML e extrair dados necessários, como código da moeda, nome e número:

#### Separação de Concerns e Abstração de Complexidade
O CurrencyParserService encapsula toda a lógica de parsing do HTML, escondendo detalhes complexos sobre como os dados são extraídos do HTML.

#### Facilidade de Teste
A separação do parsing permite testar essa funcionalidade de forma isolada, usando HTML estático (mock) para verificar se o serviço extrai corretamente os dados.

### Como os services foram utilizados no projeto:
Os serviços foram integrados no CurrencyController, onde a ação de mostrar os dados das moedas é realizada. O controller coordena o processo:

1. CurrencyClientService é chamado para buscar a página.
2. CurrencyParserService recebe o HTML e extrai os dados necessários.
3. O controller então retorna esses dados como resposta JSON.

### Como o Cache foi utilizado no projeto
Quando o serviço CurrencyClientService faz uma requisição para obter a página HTML, antes de fazer a requisição ele verifica se uma versão cacheada da página está disponível no Redis.
Se a página não estiver no cache, o serviço procede com a requisição HTTP, obtém a resposta, e antes de retornar os dados, ele salva o registro no Redis. Isso é feito utilizando a chave de cache.
Como os dados dessa pagina não são constantemente modificados foi definido que os caches fiquem salvos por 12hr.


```bash

```

