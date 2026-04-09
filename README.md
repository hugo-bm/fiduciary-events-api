# 📊 Sistema de Governança de Eventos Fiduciários (SGEF)

## 📌 Visão Geral

O SGEF é uma API RESTful desenvolvida para apoiar a atuação de agentes fiduciários, garantindo:

* 📑 Controle de obrigações
* 🔐 Segurança de acesso por perfil (ACL)
* 🧾 Rastreabilidade completa (Audit Log)
* 📊 Monitoramento inteligente de risco (Dashboard)

---

## 🏗️ Arquitetura

O projeto segue o padrão:

* MVC (Controllers, Models, Requests)
* Camada de Services (regras de negócio)
* Resources (padronização de resposta)
* Middlewares (Auth + ACL)
* Testes automatizados (Feature)

---

## ⚙️ Tecnologias

* PHP 8+
* Laravel 12
* PostgreSQL (compatível com SQLite para testes)
* PHPUnit

---

## 🔐 Controle de Acesso (ACL)

| Recurso     | Ação             | Auditor | Analyst | Admin |
| ----------- | ---------------- | ------- | ------- | ----- |
| Issuers     | Criar/Editar     | ❌       | ❌       | ✅     |
| Obligations | Criar            | ❌       | ❌       | ✅     |
| Obligations | Atualizar Status | ❌       | ✅       | ✅     |
| Dashboard   | Visualizar       | ✅       | ✅       | ✅     |
| Audit Logs  | Visualizar       | ✅       | ❌       | ✅     |

---

## 📊 Lógica de Risco (Semáforo)

* 🔴 **CRITICAL**: vencido ou vence hoje
* 🟡 **WARNING**: vence em até 14 dias
* 🟢 **NORMAL**: acima de 14 dias

---

## 🧾 Audit Log (Não-repúdio)

Todas as ações críticas são registradas com:

* Usuário responsável
* Estado anterior
* Estado posterior

---

## 🔐 Gerenciamento de Chaves de API

Quando um usuário é criado, uma chave de API é gerada e retornada **apenas uma vez** na resposta.

⚠️ Importante:

* A chave de API é armazenada criptografada no banco de dados.

* Ela não pode ser recuperada posteriormente.

* O usuário deve armazená-la em local seguro no momento da criação do usuário.

Essa abordagem garante a segurança e está alinhada às melhores práticas para o tratamento de credenciais sensíveis.

---

## 🚀 Como Executar

```bash
git clone <repo>
cd projeto

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate
php artisan serve
```

---

## 🧪 Testes

```bash
php artisan test
```

---

## 📡 Exemplos de Uso

### 🔐 Header obrigatório

```bash
X-API-KEY: SUA_API_KEY
```

---

## 📌 Criar Issuer

```bash
curl -X POST http://localhost:8000/api/issuers \
-H "X-API-KEY: SUA_API_KEY" \
-H "Content-Type: application/json" \
-d '{
  "name": "Empresa X",
  "cnpj": "12345678901234"
}'
```

---

## 📌 Criar Obligation

```bash
curl -X POST http://localhost:8000/api/obligations \
-H "X-API-KEY: SUA_API_KEY" \
-H "Content-Type: application/json" \
-d '{
  "operation_id": 1,
  "title": "Pagamento de Juros",
  "due_date": "2026-05-01"
}'
```

---

## 📌 Atualizar Status

```bash
curl -X PATCH http://localhost:8000/api/obligations/1/status \
-H "X-API-KEY: SUA_API_KEY" \
-H "Content-Type: application/json" \
-d '{
  "status": "DELIVERED"
}'
```

---

## 📌 Dashboard

```bash
curl http://localhost:8000/api/dashboard/summary \
-H "X-API-KEY: SUA_API_KEY"
```

---

## 🧠 Boas Práticas Aplicadas

* Clean Code
* Separation of Concerns
* Princípios REST
* Segurança por padrão (API Key + Hash)
* Logs segregados (Sistema vs Negócio)
* Testes automatizados (~80% cobertura)

---

## 📈 Status do Projeto

✅ MVP completo
✅ Testado

---

## 👨‍💻 Autor

Projeto desenvolvido como demonstração de arquitetura profissional backend por Hugo Moreno
