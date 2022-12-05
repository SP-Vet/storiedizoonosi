# Storie di Zoonosi
The Micro Epidemic One Health Project (Italian Ministry of Health Research Project: MEOH/2021-2022 - IZS UM 04/20 RC)

<div align="center">
  <a href="https://storiedizoonosi.spvet.it/">
    <img src="public/images/logo_zoonosi.png" alt="LOGO Storie di zoonosi" width="80" height="80"><br>
    <b>https://storiedizoonosi.spvet.it</b>
  </a>
</div>
<div align="center">
    <a href="https://izsum.it/">
        <img src="public/images/logo.gif" alt="LOGO IZSUM" width="80" height="80"><br>
        <b>https://izsum.it</b>
    </a><br>
    <a href="https://spvet.it/">
        <img src="public/images/logo_zoodiac_new.png" alt="LOGO SPVET.it" width="80" height="80"><br>
        <b>https://spvet.it</b>
    </a>
</div>

<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
      <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#before-installation">Before Installation</a>
    </li>
    <li><a href="#installation">Installation</a></li>
    <li><a href="#configuration">Configuration</a></li>
    <li><a href="#import-database">Import Database</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contacts">Contacts</a></li>
  </ol>
</details>

## About The Project
The Micro Epidemic One Health Project, aims to create an open repository of narratives concerning zoonoses; diseases transmitted from animals to humans and vice versa. These zoonoses tales, derive from the recording of interviews or free narrations of privileged witnesses (Veterinarians, Healthcare Professionals, Researchers) who really faced and experienced them in their professional activity. The narratives - case studies, are made freely available to readers through a Web hypertext accessible from the Internet and a Smartphone App. Texts, annotated through crowdsourcing, are linked to peer reviewed scientific literature by experts and documentalists. They represent valid teaching material for young doctors and veterinarians, as well as an informational instrument for the civilian population. So that way, the Micro Epidemic One Health Project will contribute to promote a greater sharing of scientific knowledge on zoonoses making it easier to detect them early and contrast them in the appropriate ways.

### Built With
* [![Laravel][Laravel.com]][Laravel-url]
* [![Bootstrap][Bootstrap.com]][Bootstrap-url]
* [![JQuery][JQuery.com]][JQuery-url]
* [![Javascript][Javascript.com]][Javascript-url]

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

## Import Database
After agreeing with the organization (**redazione-spvet@izsum.it**) install the database dump that will be provided

##License
Distributed under the Apache 2.0 License. See `LICENSE.txt` for more information.

## Contacts
Redazione SPVET - redazione-spvet@izsum.it
Raoul Ciappelloni - r.ciappelloni@izsum.it
Eros Rivosecchi - e.rivosecchi@izsum.it