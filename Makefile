.PHONY: help init up down rebuild migrate fixture prepare-test-db test test-unit test-acceptance sms-test shell ps logs cs-fix phpstan

DC := docker compose

help:
	@printf "%s\n" \
		"Available targets:" \
		"  make init             - initialize and start the project from scratch" \
		"  make up               - build and start containers" \
		"  make down             - stop containers" \
		"  make rebuild          - rebuild and restart containers" \
		"  make migrate          - run Yii migrations" \
		"  make fixture          - load presentation fixtures" \
		"  make test             - run all tests" \
		"  make test-unit        - run unit tests" \
		"  make test-acceptance  - run acceptance tests" \
		"  make sms-test         - send SMSPilot template test request" \
		"  make shell            - open shell in app container" \
		"  make ps               - show containers status" \
		"  make logs             - tail app and db logs" \
		"  make cs-fix           - run php-cs-fixer" \
		"  make phpstan          - run phpstan static analysis"

init:
	@if [ ! -f .env ]; then cp .env.example .env; fi
	$(MAKE) up
	$(DC) exec app composer install
	$(MAKE) migrate
	$(MAKE) fixture

up:
	$(DC) up -d --build

down:
	$(DC) down

rebuild:
	$(DC) up -d --build --force-recreate

migrate:
	$(DC) exec app php protected/yiic.php migrate --interactive=0

fixture:
	$(DC) exec app php protected/yiic.php fixture

test: prepare-test-db
	$(DC) exec app vendor/bin/codecept run

test-unit: prepare-test-db
	$(DC) exec app vendor/bin/codecept run Unit

test-acceptance: prepare-test-db
	$(DC) exec app vendor/bin/codecept run Acceptance

prepare-test-db:
	$(DC) exec app php tests/bin/prepare_test_db.php

sms-test:
	$(DC) exec app php tests/bin/sms_pilot_test.php

shell:
	$(DC) exec app sh

ps:
	$(DC) ps

logs:
	$(DC) logs -f app db

cs-fix:
	$(DC) exec app vendor/bin/php-cs-fixer fix

phpstan:
	$(DC) exec app vendor/bin/phpstan analyze --ansi --memory-limit=1G
