# 🧾 Mini ERP - Controle de Pedidos, Produtos, Cupons e Estoque

Este projeto é um mini ERP desenvolvido como parte de um teste técnico. Ele permite gerenciar produtos com variações, controlar estoque, aplicar cupons, simular compras com regras de frete, consultar endereço via CEP e realizar o envio de e-mails ao finalizar pedidos. O sistema também possui um webhook para atualização de status de pedidos.

## 🚀 Tecnologias Utilizadas

- PHP (puro ou CodeIgniter 3)
- MySQL
- Bootstrap 4/5
- HTML/CSS
- JavaScript (para integração com ViaCEP)

## 📦 Funcionalidades

### Produtos
- Cadastro de produto com:
  - Nome
  - Preço
  - Múltiplas variações
  - Controle de estoque por variação
- Atualização de produtos e estoques na mesma tela

### Carrinho de Compras
- Adição de produtos com variações ao carrinho (em sessão)
- Regras de frete:
  - Subtotal entre R$52,00 e R$166,59 → R$15,00
  - Subtotal > R$200,00 → Frete grátis
  - Outros casos → R$20,00
- Aplicação de cupons com regras:
  - Validade
  - Valor mínimo do carrinho

### Pedidos
- Finalização de pedido com:
  - CEP (consulta automática via [ViaCEP](https://viacep.com.br/))
  - Cálculo de frete
  - Desconto de cupom (se válido)
- Envio automático de e-mail com os dados do pedido e endereço
- Webhook para atualização ou cancelamento do pedido via ID + status

---

## 🛠️ Como Rodar Localmente

1. Clone o repositório:

```bash
git clone https://github.com/YuriDeiverson/ProjetoERP-teste
Crie um banco de dados MySQL e execute o script:

sql
Copiar
Editar
-- schema.sql (presente no repositório)
Configure o acesso ao banco de dados em config.php (ou no respectivo arquivo se estiver usando CodeIgniter):

php
Copiar
Editar
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mini_erp');
Inicie o servidor PHP:

bash
Copiar
Editar
php -S localhost:8000
Acesse: http://localhost:8000

📁 Estrutura de Pastas (Exemplo com PHP Puro)
bash
Copiar
Editar
/mini-erp
├── index.php
├── produtos.php
├── carrinho.php
├── finalizar.php
├── webhook.php
├── config.php
├── /controllers
├── /models
├── /views
├── /assets
│   ├── css/
│   └── js/
└── schema.sql
📩 Webhook
Endpoint: /webhook.php

Espera um JSON com:

json
Copiar
Editar
{
  "pedido_id": 123,
  "status": "cancelado"
}
Ações:

Se status == cancelado: remove o pedido

Caso contrário: atualiza o status do pedido


🧪 Teste este Projeto
Cadastre um produto com variações

Adicione ao carrinho

Aplique um cupom válido

Finalize a compra informando um CEP válido

Envie um webhook de cancelamento para testar

