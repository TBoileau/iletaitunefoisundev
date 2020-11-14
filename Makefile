build:
	$(MAKE) prepare-test
	$(MAKE) analyze
	$(MAKE) tests

.PHONY: translations
translations:
	php bin/console translation:update --force fr

.PHONY: tests
tests:
	php bin/phpunit

analyze:
	npm audit
	composer valid
	php bin/console doctrine:schema:valid --skip-sync --env=test
	php bin/phpcs

prepare-dev:
	npm install
	npm run dev
	composer install --prefer-dist
	php bin/console doctrine:database:drop --if-exists -f --env=dev
	php bin/console doctrine:database:create --env=dev
	php bin/console doctrine:schema:update -f --env=dev
	php bin/console doctrine:fixtures:load -n --env=dev

prepare-test:
	npm install
	npm run dev
	composer install --prefer-dist
	php bin/console cache:clear --env=test
	php bin/console doctrine:database:drop --if-exists -f --env=test
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine:schema:update -f --env=test
	php bin/console doctrine:fixtures:load -n --env=test
