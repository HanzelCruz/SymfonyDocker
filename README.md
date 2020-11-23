# Docker Symfony (REST API - PHP - NGINX - MySQL)

Docker-symfony gives you everything you need for developing Symfony application. 

## Installation

1. Create a `.env` from the `.env.dist` file. Adapt it according to your symfony application

    ```bash
    $ cp .env.dist .env
    ```

2. Installing dependencies

    ```bash
    $  (cd symfony && composer install)
    ```

3. Build/run containers with (with and without detached mode), root folder.

    ```bash
    $ docker-compose build
    $ docker-compose up -d
    ```

3. Run test, make sure you have a `.env.test` DATABASE_URL setup.

    ```bash
    $  (cd symfony && php bin/phpunit)
    ```

4. Enjoy :-)
