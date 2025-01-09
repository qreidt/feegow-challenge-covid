# Case Técnico Feegow

## Apresentação do problema

 A Feegow Clinic empresa do ramo de tecnologia para o segmento médico solicita um sistema web **desenvolvido na linguagem PHP ou Node.Js**, para cadastro e controle dos seus colaboradores que foram vacinados contra a COVID-19.
  O cadastro deve armazenar as seguintes informações:

### Funcionários

- CPF  (Chave única);
- Nome completo
- Data de nascimento
- Data da primeira dose
- Data da segunda dose
- Data da terceira dose
- Vacina Aplicada
- Portador de comorbidade?

### Vacinas

- Nome
- Lote
- Data de validade

> Sinta-se à vontade para definir os tipos e tamanhos dos campos, assim como normalizar as tabelas segundo seu julgamento de necessidade. Tenha em mente para isso que esta base de dados deveria manter a sua performance ótima mesmo chegando a centenas de milhares de registros.

## Tecnologias usadas

Os pré-requisitos para a aplicação:
- Use o PHP ou Node.js como linguagem backend.
- Usar o framework front-end de sua preferência.
- O Banco de dados deve ser relacional, damos preferência para MySQL/PostgreSQL.
- Documentação sucinta e explicativa de como rodar seu código e levantar os ambientes (vídeo explicativo em um link acessível é aceito desde que o áudio e o vídeo estejam em boa qualidade).

## Avaliação

Para nos enviar seu código, você poderá escolher **uma das duas** opções abaixo:
- Fazer um fork deste repositório e nos mandar uma pull-request;
- Dar acesso ao seu repositório no Github para FeegowWelcomeTech;
> Caso opte por fazer um pull-request, deixe ele explicativo **apontando tudo que precisa ser feito para rodar a sua aplicação**.

## Será indispensável para apresentação deste case

- Anexar o código SQL necessário para a criação da estrutura de banco de dados (sql padrão ANSII) e os inserts dos dados iniciais mínimos para funcionamento do sistema (serão aceitas as migrations laravel).
- Anexar as instruções e requisitos mínimos de sistema para que a aplicação seja executada.
- Instruções básicas de utilização do sistema.

## Requisitos específicos para nível sênior

- Anonimização do CPF, mostrando apenas os 3 primeiros dígitos
- Incluir caching para dados de vacinas
- Aplicação de SOLID
- Utilizar Fila para extração com Relatório de Não Vacinados com CPF e Nome

## Será considerado um diferencial neste case

- Validação lógica dos campos no lado do cliente **e** no lado do servidor.
- Usabilidade intuitiva e tratamento e exceções de sistema.
- Estruturação do banco de dados em sua **melhor forma normal**.
- Utilização de Docker.
