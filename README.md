# SetUp
- Copy `.env.example` as `.env` and replace the values as desired
  - `GID` & `UID` meant for linux users to avoid file permission errors, you can use `id -u` & `id -g` to find yours
- Run `docker compose up -d`
- Enter the container `docker compose exec -it fpm bash`
  - Run `compser install`
  - Run `php bin/console doctrine:migrations:migrate`
    - To consume a log file that is found in the `{GIT_PROJECT_ROOT}/data` directory
        - Run `php bin/console logs:aggregate /project/src/data/{FILENAME}`
