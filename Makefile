bl:
	docker-compose build

ul:
	docker-compose up -d

dl:
	docker-compose down

dump-autoload:
	composer dump-autoload

test:
	composer test

test-cov-text:
	composer exec --verbose phpunit tests -- --coverage-text

test-cov-html:
	composer exec --verbose phpunit tests -- --coverage-html coverage
