USER_ID=$(shell id -u)

DC = @USER_ID=$(USER_ID) docker compose
DC_RUN = ${DC} run --rm text_magic_test
DC_EXEC = ${DC} exec text_magic_test

PHONY: help
.DEFAULT_GOAL := help

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

init: down build install up db-wait db-migrate fixtures-load console

build: ## Build services.
	${DC} build $(c)

up: ## Create and start services.
	${DC} up -d $(c)

stop: ## Stop services.
	${DC} stop $(c)

start: ## Start services.
	${DC} start $(c)

down: ## Stop and remove containers and volumes.
	${DC} down -v $(c)

restart: stop start ## Restart services.

console: ## Login in console.
	${DC_EXEC} /bin/bash

install: ## Install dependencies without running the whole application.
	${DC_RUN} composer install

db-migrate: ## Run migrations
	$(DC_RUN) php bin/console doctrine:migrations:migrate -n

db-wait: ## Wait for database to start
	$(DC_RUN) /bin/bash -c "until nc -z database 5432; do echo 'Waiting for PostgreSQL to start...'; sleep 1; done"

fixtures-load:
	$(DC_RUN) php bin/console doctrine:fixtures:load -n