# Challenge Controle de Vacinação

## Database ER
```mermaid
erDiagram
    vaccines {
        int id PK
        string name
        string slug
    }
    
    vaccine_lots {
        int id PK
        int vaccine_id FK
        string lot_id UK
        date expiration_date
    }

    vaccines || -- o{ vaccine_lots : has-many
    
    employees {
        int id PK
        string name
        string cpf UK
        date birh_date
        bool has_comorbity
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    employee_vaccines {
        int employee_id PK,FK
        int dose_number PK
        int vaccine_id FK
        int vaccine_lot_id FK
        date applied_at
        timestamp created_at
    }

    employees || -- o{ employee_vaccines : has-many
    vaccines |o -- o{ employee_vaccines : has-many
    vaccine_lots |o -- o{ employee_vaccines : has-many
    
    reports {
        int id PK
        string name
        string type
        string file_path
        timestamp ready_at
        timestamp created_at
        timestamp updated_at
    }
```

### Vacinas
- Cada vacina pode ter vários lotes.
- Cada lote de vacina possui apenas uma data de validate.

## Funcionários
- Cada CPF de funcionário é único.
- Um funcionário pode receber até 3 doses de vacina.
- Cada vacina aplicada no funcionário pode ser de marca diferente
e possuir lotes e datas de validades diferentes entre elas.

## Relatórios
Ao criar um relatório, primeiro é salvo um registro apenas com seu id e timestamps
enquanto um job para processamento assíncrono é processado por workers.
Após a finalização do processamento do relatório, suas informações são salvas e é emitido
um evento para atualizar a página de relatórios em tempo real.
