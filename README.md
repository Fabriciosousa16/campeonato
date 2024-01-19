Ferramentas Utilizadas.

Angular 15.2.10 
Laravel 8
Node JS versão 18.13.0
NPM versão 8.2

IDE utilizada foi o visual Studio
Banco de Dados Mysql
Postman - Realizar collections para validação.
_____________________________________________________________________________________________
arquivo .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campeonatodb
DB_USERNAME=root
DB_PASSWORD= rootsrfghtfs0!9*7

Ajuste de acordo com nome seu nome do usuaio e senha do seu banco de dados

_____________________________________________________________________________________________

criar um banco de dados (No nosso exemplo utilizamos o mysql)

CREATE DATABASE campeonatodb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

Definir horario de brasilia.

SET time_zone = '-03:00';
_____________________________________________________________________________________________

Para efetuar os comandos a sequier é necessário está na pasta campeonato/api-campeonato

Comando para Rodar as migrates no banco de dados

php artisan migrate

Comando para registrar os seeders iniciais do sistema.

php artisan db:seed --class=TorneiosTableSeeder
php artisan db:seed --class=StatusTableSeeder
php artisan db:seed --class=FasesTableSeeder

_______________________________________________________________________________________________

Utilização do Postman para registrar novo usuário.

Método Post

http://127.0.0.1:8000/api/auth/register

name -
email -
password -

http://127.0.0.1:8000/api/auth/register?name=admin&email=admin@admin.com&password=admin@admin.com

Senha é necessário ter no minimo 8 caracters, contendo letras, numeros e simbolos.

Para realizar qualquer funcionalidade no sistema, é necessário realizar login no sistema.

Comando para rodar os servidores 

_______________________________________________________________________________________________
http://localhost:4200/

Na pasta campeonato/api-campeonato rode o comando para rodar rodar o servidor laravel

php artisans serve

Na pasta campeonato/front-campeonato rode o comando para rodar rodar o servidor angular
ng serve

_______________________________________________________________________________________________
Tela Dashboard

Mostrar informações referentes aos campeonatos.
 
_______________________________________________________________________________________________
Campeonato

Listar Campeonatos
Editar Campeonatos
Adicionar Campeonatos.

Campeonatos com o mesmo nome não podem ser adicionados.
Campeonatos com equipes não podem ser deletados.

------------------------------
Times

Listar Campeonatos
Editar Campeonatos
Adicionar Campeonatos.

Maximo de 8 times por campeonato
Times com mesmo nome não podem ser escritos no mesmo campeonato
Times cujo campeonato se encontra com status Em Andamento ou Concluido não podem ser deletados.

------------------------------
Simulação

Listar as Simulações com status Aberto ou Em Andamento.

Cada simulação tem 3 pontos onde vai poder simular a competição.


------------------------------
Histórico

Vai mostrar o histórico de competições, através de um select.
Apenas competições com status finalizado serão mostrados no historico.
Ao clicar o select vai no botão filtrar que vai mostrar o histórico.

------------------------------
Logout

Sair do Sistema.

------------------------------


