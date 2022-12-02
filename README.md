# Storie di Zoonosi
The Micro Epidemic One Health Project (Italian Ministry of Health Research Project: MEOH/2021-2022 - IZS UM 04/20 RC)
**https://storiedizoonosi.spvet.it**

## Before installation
Register a new free account on https://www.mtcaptcha.com/ 

## Installation
First clone this repository, install the dependencies, and setup your .env file.

```
git clone git@github.com:SP-Vet/storiedizoonosi.git stories
composer install
cp .env.example .env
```

Generate a new app encryption key

```
php artisan key:generate
```

Install the DBMS PostgreSQL on your machine [https://www.postgresql.org/download/]
and create a new database

## Configuration
In your copy of the new .env files add:<br>
PUBLIC_KEY_STRING = [string of sixteen characters with uppercase, lowercase and numbers]<br>
PRIVATE_KEY_STRING = [string of sixteen characters with uppercase, lowercase and numbers]<br>
NOME_SITO = [name of the project without empty spaces]<br>
MTCAPTCHA_PRIVATE = [private key released after the registration on mtcaptcha.com]<br>
MTCAPTCHA_PUBLIC = [public key released after the registration on mtcaptcha.com]<br>

In your copy of the new .env files modify:<br>
DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD with the parameters of the PostgreSQL database

## Import Database dump
After agreeing with the organization (**redazione-spvet@izsum.it**) install the database dump that will be provided
