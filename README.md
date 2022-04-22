### API de Transferência de Valores

## Ferramentas
- Lumen v8
- Banco de dados MySQL
- PHP 7.3

## Estrutura

A arquitetura adotada no desenvolvimento do projeto segue os padrões de MVC com a adição de uma camada extra, denominada Serviços. Junto à esta camada, é implementada a camada Repository onde é tratada a comunicação com o banco de dados.

Ao ser realizada a requisição, o pedido chega na camada Controller através das rotas definidas no arquivo **routes/web.php**. No Controller são validadas os dados recebidos através de uma função nativa do Lúmen (validate).

Após realizada a validação, o controller envia os dados para a camada de Services, onde são chamados os métodos da camada Repository que implementadas as regras de negócio.

A camada Repository é reponsável por realizar as chamadas de persistência de dados configurados na camada Model, utilizando recursos da biblioteca.

Todas operações são registradas utilizando a função Log do Lúmen, e os arquivos ficam armazenados na pasta **storage/logs/** e o padrão de nome do arquivo é "lumen-DATA.log",

> **Exemplo**: lumen-2022-04-22.log

## Detalhes de Comunicação

Toda comunicação entre camadas, são feitas com base em interfaces e implementações dessas interfaces. Seguindo como exemplo, a interface **PaymentOrdersService** e a sua implementação **PaymentOrdersServiceImpl**.

Seguindo essa idéia de implementação de interfaces para padronização de comunicação, é utilizada uma classe padrão DTO (Data Transfer Object)  para construção das mensagens de retorno para os usuários.

## Configurações

Inicialmente é necessário a utilização do composer para instalação do Lúmen, que durante a instalação configura um ambiente para a execução da aplicação.

Além disso, após a instalação deve ser configurado o acesso ao banco de dados no arquivo **config/database.php**

A execução do projeto é feita através do comando **php -S localhost:8000 -t public **  que irá inicializar o servidor da aplicação.

## Detalhes de Execução
### Efetuar liquidação da transferência ( POST)
#### PATH: /paymentOrders/
Endpoint: http://localhost:8000/paymentOrders

O request esperado é:

>{
"externalId": "1",
"amount": "100000",
"expectedOn" : "dd-mm-yyyy",
"dueDate" : "dd-mm-yyyy"
}

São obrigatórios **externalId**, **amount** e **expectedOn**.
O **dueDate** se trata de uma chave opcional, portanto seu uso é facultativo.

Após a chamada passar por todas as validações do Repository, a API realizará a inserção do registro no banco de dados, porém com algumas regras.

Por exemplo, o sistema verifica se o formato da data de liquidação e da data de vencimento(quando houver), estão no formato esperado, no caso dd-mm-yyyy.
Caso não estejam, será retornada uma mensagem de rejeição:

Para data de liquidação:

> * 'internalId': ' Internal error in transfer service | Incorrectly formatted expectedOn. Enter in format dd-mm-yyyy
    'status': 'REJECTED'
    **status code = 500**

Para data de vencimento (quando houver):

> * 'internalId': ' Internal error in transfer service | Incorrectly formatted dueDate. Enter in format dd-mm-yyyy
    'status': 'REJECTED'
    **status code = 500**


A API não aceitará transferências com datas de vencimento anteriores ao dia atual, devolvendo então uma resposta de rejeição assim como a descrita abaixo.

>    * 'internalId': Business Error - Transfer Overdue
       'status': 'REJECTED'
       **status code = 405**

Assim como também verifica se o valor informado está em centavos. Caso não esteja, o retorno será o seguinte:

> * 'internalId': 'Internal error in transfer service | Invalid value! type in cents
    'status': 'REJECTED'
    **status code = 500**

O sistema fará também verificações do externalId. Se já foi realizado um registro que possui a mesma externalId, a aplicação retornará o erro:

> * 'internalId': 'Internal error in transfer service | A record with this externalId already exists
    'status': 'REJECTED'
    **status code = 500**

A API conta também com a validação da data de liquidação. Não serão aceitas transferências com data inferior a atual, informando assim a resposta de rejeição, assim como a validação de data de vencimento (primeiro exemplo).

Já nos casos de datas de liquidação iguais ou superiores à data atual, o sistema realizará a inserção do registro, informando o número do internalId do registro inserido, além das seguintes mensagens:

Para data de liquidação igual à hoje:

> * 'internalId': ' 1 | Transfer Created and Approved
    'status': 'APPROVED'
    **status code = 201**

Para data de liquidação superior à atual:
> * 'internalId': ' 2 | Transfer Created and Sheduled
    'status': 'APPROVED'
    **status code = 201**

### Efetuar consulta da liquidação da transferência (GET)
#### PATH: /paymentOrders/{internalId}

Para realizar a consulta da liquidação da transferência, basta informar o internalId (código retornado no response, após a inserção do registro ) no endpoint.
Exemplo: http://localhost:8000/paymentOrders/1

Caso a aplicação encontre o registro, a mesma retornará:

>{
"internalId": 1,
"amount": 100000,
"expectedOn": "2022-04-22",
"dueDate": null,
"externalId": 1,
"status": "APPROVED"
}

Ou caso não haja nenhum registro com o internalId informado, o retorno será:
>[ ]




## Versão

1.0


## Autor

* **JEAN CARLO DOS SANTOS PANDOLFI JÚNIOR**



