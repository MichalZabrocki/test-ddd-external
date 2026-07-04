current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

composer-install: ## Composer install
	docker compose exec php composer install

start:
	@if [ ! -f .env.local ]; then echo '' > .env.local; fi
	UID=${shell id -u} GID=${shell id -g} docker compose up --build -d

stop:
	UID=${shell id -u} GID=${shell id -g} docker compose stop

destroy:
	UID=${shell id -u} GID=${shell id -g} docker compose down

rebuild:
	docker compose build --pull --force-rm --no-cache
	make install
	make start
