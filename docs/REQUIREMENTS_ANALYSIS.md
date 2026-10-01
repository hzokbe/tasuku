# Tasuku - Análise de Requisitos

## Requisitos funcionais

### Gerenciamento de tarefas

- **RF01** – O usuário deve poder criar tarefas com título, descrição e prioridade.
- **RF02** – O usuário deve poder editar e excluir tarefas.
- **RF03** – O usuário deve poder marcar uma tarefa como concluída ou reabri-la.
- **RF04** – O sistema deve pedir confirmação antes de excluir uma tarefa ou lista.

### Organização

- **RF05** – O usuário deve poder agrupar tarefas em listas.
- **RF06** – O usuário deve poder criar, editar e excluir listas, com confirmação antes da exclusão.
- **RF07** – O usuário deve poder atribuir etiquetas às tarefas.
- **RF08** – O usuário deve poder criar, renomear e excluir etiquetas.
- **RF09** – O sistema deve permitir filtrar tarefas por status, prioridade e etiqueta.
- **RF10** – O sistema deve permitir buscar tarefas por texto.
- **RF11** – O sistema deve permitir buscar listas por texto.
- **RF12** – O sistema deve permitir buscar etiquetas por texto.

### Conta e sincronização

- **RF13** – O usuário deve poder se cadastrar, autenticar e recuperar a senha.
- **RF14** – O usuário deve poder encerrar sua sessão.
- **RF15** – O usuário deve poder alterar seu username e e-mail, informando a senha atual para alterar o e-mail.
- **RF16** – O usuário deve poder alterar sua senha, informando a senha atual.
- **RF17** – O usuário deve poder excluir a própria conta, com confirmação antes da exclusão.
- **RF18** – O sistema deve sincronizar as tarefas entre dispositivos.

### Visualização

- **RF19** – O sistema deve exibir uma visão geral com todas as tarefas do usuário.

## Requisitos não funcionais

### Desempenho

- **RNF01** – As telas principais devem carregar em até 2 segundos em condições normais de rede.
- **RNF02** – Criar, editar ou concluir uma tarefa deve ter resposta visual imediata (atualização otimista), em menos de
  200 ms.
- **RNF03** – A busca e os filtros devem retornar resultados em até 1 segundo para até 10.000 tarefas por usuário.

### Disponibilidade e confiabilidade

- **RNF04** – O sistema deve ter disponibilidade mínima de 99,5% ao mês.
- **RNF05** – Em caso de conflito de sincronização entre dispositivos, a alteração mais recente deve prevalecer.

### Segurança

- **RNF06** – As senhas devem ser armazenadas com hash seguro.
- **RNF07** – Toda comunicação entre cliente e servidor deve usar HTTPS/TLS.
- **RNF08** – Cada usuário deve acessar apenas os próprios dados.
- **RNF09** – O sistema deve ser protegido contra ataques comuns, como SQL Injection, XSS e CSRF.

### Usabilidade

- **RNF10** – Um novo usuário deve conseguir criar sua primeira tarefa em até 1 minuto, sem tutorial.
- **RNF11** – A interface deve ser responsiva e seguir boas práticas básicas de acessibilidade.

### Compatibilidade e portabilidade

- **RNF12** – O sistema deve funcionar nos principais navegadores modernos, em desktop e celular.

### Manutenibilidade e escalabilidade

- **RNF13** – A arquitetura deve suportar até 10.000 usuários ativos sem reestruturação significativa.
- **RNF14** – O código deve ter cobertura de testes automatizados mínima de 70%.
- **RNF15** – O sistema deve registrar logs de erros para monitoramento.
