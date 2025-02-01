# Udhëzime për startimin e projektit

## 1. Instalimi i Docker Desktop

* Duhet të shkarkohet Docker Desktop:
  * Për Windows: https://www.docker.com/products/docker-desktop/
  * Për Mac: https://www.docker.com/products/docker-desktop/

* Pas instalimit të Docker Desktop, duhet të pritet derisa të startohet plotësisht.

## 2. Konfigurimi i Docker

* Duhet të hapet terminali dhe të shkohet te direktoriumi i projektit ku gjendet docker-compose.yml
* Duhet të ekzekutohet komanda:
  ```bash
  docker compose up -d
  ```

## 3. Instalimi i PHP

### Për Windows

* Duhet të shkarkohet XAMPP: https://www.apachefriends.org/
* Duhet të instalohet XAMPP
* Duhet të hapet terminali
* Duhet të shkohet te direktoriumi i projektit
* Duhet të ekzekutohet:
  ```bash
  php -S 127.0.0.1:8000
  ```

### Për Mac

* Duhet të instalohet Homebrew:
  ```bash
  /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
  ```
* Duhet të instalohet PHP:
  ```bash
  brew install php
  ```
* Duhet të shkohet te direktoriumi i projektit
* Duhet të ekzekutohet:
  ```bash
  php -S 127.0.0.1:8000
  ```

## 4. Verifikimi

* Databaza duhet të jetë e qasshme në `127.0.0.1:3306`
* Aplikacioni web duhet të jetë i qasshëm në `http://127.0.0.1:8000`

## Problemet e zakonshme që duhet të kontrollohen

* Docker Desktop duhet të jetë duke u ekzekutuar
* Portat 3306 dhe 8000 nuk duhet të jenë të zëna
* Të drejtat e administratorit duhet të jenë aktive në sistem