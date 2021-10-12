.DEFAULT_GOAL := up

up:
	docker-compose up -d;

down:
	docker-compose down;

build:
	docker-compose build; \
	docker-compose exec php-fpm bash -c "composer install";

ssh:
	docker-compose exec php-fpm bash;

test:
	docker-compose exec php-fpm bash -c "./bin/phpunit --testdox"