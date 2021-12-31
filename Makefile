## —— JWT 🕸 ———————————————————————————————————————————————————————————————————
BEARER    = `cat ./config/jwt/bearer.txt`
BASE_URL  = http://127.0.0.1
PORT      = :8030

init:
	docker-compose build --force-rm --no-cache
	make up

up:
	docker-compose up -d
	echo "Application is running at http://127.0.0.1:8030"

ssh:
	docker exec -it guess bash

schema-update:
	docker exec -it guess /home/guess/bin/console doctrine:database:create --if-not-exists
	docker exec -it guess /home/guess/bin/console doctrine:schema:update --force

test-coverage:
	docker exec -it guess /home/guess/vendor/bin/phpunit --coverage-html html tests

load-fixtures:
	docker exec -it guess /home/guess/bin/console doctrine:fixtures:load

alice-load:
	docker exec -it guess /home/guess/bin/console hautelook:fixtures:load

cc:
	docker exec -it guess /home/guess/bin/console cache:clear

test-jwt:
	curl -X POST -H "Content-Type: application/json" ${BASE_URL}${PORT}/login_check -d '{"username":"user","password":"test"}'

jwt-create-ok: ## Create a JWT for a valid test account (you can use the "lexik:jwt:generate-token" command now)
	@curl -s POST -H "Content-Type: application/json" ${BASE_URL}${PORT}/login_check -d '{"username":"player","password":"test"}'

jwt-create-nok: ## Login attempt with wrong credentials
	@curl -s POST -H "Content-Type: application/json" ${BASE_URL}${PORT}/login_check -d '{"username":"foo","password":"bar"}'

jwt-test: ## Test a JWT token to access an API Platform resource
	@curl -s GET -H 'Cache-Control: no-cache' -H "Content-Type: application/json" -H "Authorization: Bearer ${BEARER}" ${BASE_URL}${PORT}/api/games/1
