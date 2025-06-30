.PHONY: help install phpunit phpstan coverage

# Domyślna pomoc
help:
	@echo ""
	@echo "Dostępne komendy:"
	@echo "  make up             - Uruchomienie środowiska Docker Compose w tle"
	@echo "  make down           - Zatrzymanie i usunięcie środowiska Docker Compose"
	@echo "  make install        - Instalacja zależności w kontenerze"
	@echo "  make phpunit        - Uruchomienie testów PHPUnit"
	@echo "  make phpstan        - Analiza kodu PHPStan"
	@echo ""

up:
	docker compose up -d

down:
	docker compose down
# Instalacja zależności composer
install:
	docker exec -it symfony_app composer install

# Uruchomienie testów
phpunit:
	docker exec -it symfony_app ./vendor/bin/phpunit

# Analiza PHPStan
phpstan:
	docker exec -it symfony_app ./vendor/bin/phpstan analyse

