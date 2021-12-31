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

cc:
	docker exec -it guess /home/guess/bin/console cache:clear
