.PHONY: tests install fixtures database prepare tests phpstan composer-valid doctrine fix analyse

DISABLE_XDEBUG=XDEBUG_MODE=off

install:
	composer install
	npm install
	make install-env env=dev db_user=$(db_user) db_password=$(db_password) db_name=$(db_name) db_host=$(db_host)
	make install-env env=test db_user=$(db_user) db_password=$(db_password) db_name=$(db_name) db_host=$(db_host)

install-env:
	cp .env.dist .env.$(env).local
	sed -i -e 's/DATABASE_USER/$(db_user)/' .env.$(env).local
	sed -i -e 's/DATABASE_PASSWORD/$(db_password)/' .env.$(env).local
	sed -i -e 's/DATABASE_HOST/$(db_host)/' .env.$(env).local
	sed -i -e 's/DATABASE_NAME/$(db_name)/' .env.$(env).local
	sed -i -e 's/ENV/$(env)/' .env.$(env).local
	make prepare env=$(env)

deploy:
	composer install
	npm install
	npm run build
	$(DISABLE_XDEBUG) php bin/console cache:clear

fixtures:
	$(DISABLE_XDEBUG) php bin/console doctrine:fixtures:load -n --env=$(env)

database:
	$(DISABLE_XDEBUG) php bin/console doctrine:database:drop --if-exists --force --env=$(env)
	$(DISABLE_XDEBUG) php bin/console doctrine:database:create --env=$(env)
	$(DISABLE_XDEBUG) php bin/console doctrine:query:sql "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));" --env=$(env)
	$(DISABLE_XDEBUG) php bin/console doctrine:schema:update --force --env=$(env)

update:
	$(DISABLE_XDEBUG) php bin/console doctrine:schema:update --force --env=$(env)

prepare:
	make database env=$(env)
	make fixtures env=$(env)

tests:
	php bin/phpunit --testdox

tests-wc:
	$(DISABLE_XDEBUG) php bin/phpunit

phpstan:
	$(DISABLE_XDEBUG) php -d memory_limit=-1 vendor/bin/phpstan analyse -c phpstan.neon

fix:
	$(DISABLE_XDEBUG) php vendor/bin/php-cs-fixer fix

composer-valid:
	composer valid

doctrine:
	$(DISABLE_XDEBUG) php bin/console doctrine:schema:valid --skip-sync

twig:
	$(DISABLE_XDEBUG) php bin/console lint:twig templates

yaml:
	$(DISABLE_XDEBUG) php bin/console lint:yaml config

container:
	$(DISABLE_XDEBUG) php bin/console lint:container

qa: fix twig yaml composer-valid container doctrine phpstan
