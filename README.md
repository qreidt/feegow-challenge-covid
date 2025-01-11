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
        datetime created_at
        datetime updated_at
        datetime deleted_at
    }
```

### Vacinas
Cada vacina pode ter vários lotes.
Cada lote de vacina possui apenas uma data de validate.

# Funcionários
Cada CPF de funcionário é único.
