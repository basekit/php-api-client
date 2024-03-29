build: lint test

lint: vendor
	./vendor/bin/phpcs  --standard=PSR12 src/
	./vendor/bin/phpcs  --standard=PSR12 tests/

test: vendor
	./vendor/bin/phpunit

vendor:
	composer install

.PHONY: build lint test
