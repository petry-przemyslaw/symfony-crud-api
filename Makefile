.PHONY: help install phpunit phpstan coverage

# Domyślna pomoc
help:
	@echo ""
	@echo "Dostępne komendy:"
	@echo "  make install        - Instalacja zależności w kontenerze"
	@echo "  make phpunit        - Uruchomienie testów PHPUnit"
	@echo "  make phpstan        - Analiza kodu PHPStan"
	@echo "  make coverage       - Generowanie pokrycia kodu (HTML)"
	@echo ""

# Instalacja zależności composer
install:
	docker exec -it symfony_app composer install

# Uruchomienie testów
phpunit:
	docker exec -it symfony_app ./vendor/bin/phpunit

# Analiza PHPStan
phpstan:
	docker exec -it symfony_app ./vendor/bin/phpstan analyse

# Pokrycie kodu
coverage:
	docker exec -e XDEBUG_MODE=coverage -it symfony_app ./vendor/bin/phpunit --coverage-html var/coverage
	@echo "🔍 Otwórz var/coverage/index.html w przeglądarce."

