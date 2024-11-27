include .env

build:
	docker-compose -f ./docker/docker-compose.yml up -d --build
	docker-compose -f ./docker/docker-compose.yml exec php-fpm sh -c "cd controllers/ && composer install"
start:
	cd docker
	docker-compose -f ./docker/docker-compose.yml up -d
down:
	cd docker
	docker-compose -f ./docker/docker-compose.yml down
