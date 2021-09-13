#!/usr/bin/env sh
docker-compose up -d
docker-compose exec php composer install
docker-compose exec php bin/console doctrine:database:drop -f
docker-compose exec php bin/console doctrine:database:create --if-not-exists
docker-compose exec php bin/console doctrine:migration:migrate -n
docker-compose exec php bin/console doctrine:fixtures:load -n
docker-compose exec php bin/console cache:clear
docker-compose exec php bin/console cache:clear --env=prod
