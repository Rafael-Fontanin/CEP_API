
# Trabalho de PWIII - API REST 

Rafael Fontanin e Nicolas Maruyama | DS3 - A

# Requisitos

Ter o xampp baixado.

# Instalação
Acesse : https://www.apachefriends.org/download.html caso não tenha o xampp instalado e clique Download na versão mais recente disponével para seu sistema operacional (8.2.12 / PHP 8.2.12 para Windows e Linux ou 8.2.4 / PHP 8.2.4 para MAC OS).

--------------------------------------------------------------------------------------------------------
# Estrutura de Arquivos

Cep_API/
|---Controller/
|   |---CepAPI.php
|
|---Model/
|   |---Conexao.php
|   |---Endereco.php
|
|---View/
|   |---index.html
|   |---listagem.php
|   |---script.js
|
|---banco.sql
--------------------------------------------------------------------------------------------------------

# 1. Configurar o banco de dados

Abra o seu xampp e dê start no MySQL e no Apache;
Clique em admin no MySQL, abrindo o localhost/phpMyAdmin/
Na barra presente na esquerda de sua tela, clique em "Novo"
No campo "Nome da base de dados", escreva "pwiiib" e após isso clique em criar.
Na barra esquerda, clique no nome do banco que acabou de ser criado.
Após isso clique em importar no topo do site (ícone de papel com uma seta vermelha).
Clique no botão "Escolher arquivo" e selecione o arquivo banco.sql (está fora de pastas para facilitar a visualização).
Desça a tela e clique no botão importar.

Se ao você clicar no nome do banco na barra esquerda, aparecer a tabela enderecos, funcionou.

--------------------------------------------------------------------------------------------------------

# 2. Configurar a conexão

Edite `model/conexao.php` com suas credenciais nessa parte:

private string $host     = 'localhost';
private string $dbName   = 'db_cep';
private string $username = 'root';
private string $password = '';

É bem provável que você não precise alterar essa parte, mas existe a possibilidade

--------------------------------------------------------------------------------------------------------

# 3. Configurar o servidor web

Copie a pasta `CEP_API/` para `xampp/htdocs/` e acesse:

http://localhost/CEP_API/View/index.html

--------------------------------------------------------------------------------------------------------

