# Tasuku - Modelagem de Dados

## Modelo conceitual

### User

- id
- username
- email
- password_hash

### List

- id
- title
- description

### Task

- id
- title
- description
- priority
- completed

### Tag

- id
- title

## Modelo físico

### User (users)

| Campo         | Tipo         | Restrições       |
|---------------|--------------|------------------|
| id            | UUID         | PK               |
| username      | VARCHAR(16)  | NOT NULL, UNIQUE |
| email         | VARCHAR(254) | NOT NULL, UNIQUE |
| password_hash | VARCHAR(60)  | NOT NULL         |

### List (lists)

| Campo       | Tipo         | Restrições                                 |
|-------------|--------------|--------------------------------------------|
| id          | UUID         | PK                                         |
| title       | VARCHAR(128) | NOT NULL                                   |
| description | TEXT         | NULL                                       |
| user_id     | UUID         | NOT NULL, FK → users.id, ON DELETE CASCADE |

### Task (tasks)

| Campo       | Tipo         | Restrições                                      |
|-------------|--------------|-------------------------------------------------|
| id          | UUID         | PK                                              |
| title       | VARCHAR(128) | NOT NULL                                        |
| description | TEXT         | NULL                                            |
| priority    | VARCHAR(6)   | NOT NULL, valores permitidos: low, medium, high |
| completed   | BOOLEAN      | NOT NULL, DEFAULT FALSE                         |
| list_id     | UUID         | NOT NULL, FK → lists.id, ON DELETE CASCADE      |

### Tag (tags)

| Campo   | Tipo        | Restrições                                 |
|---------|-------------|--------------------------------------------|
| id      | UUID        | PK                                         |
| title   | VARCHAR(64) | NOT NULL                                   |
| user_id | UUID        | NOT NULL, FK → users.id, ON DELETE CASCADE |

### TaskTag (tag_task)

| Campo   | Tipo | Restrições                                      |
|---------|------|-------------------------------------------------|
| task_id | UUID | PK (composta), FK → tasks.id, ON DELETE CASCADE |
| tag_id  | UUID | PK (composta), FK → tags.id, ON DELETE CASCADE  |
