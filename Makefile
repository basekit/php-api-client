build: lint test

lint: vendor
	./vendor/bin/phpcs  --standard=PSR2 src/
	./vendor/bin/phpcs  --standard=PSR2 tests/

test: vendor
	./vendor/bin/phpunit

vendor:
	php composer.phar install

.PHONY: build lint test
