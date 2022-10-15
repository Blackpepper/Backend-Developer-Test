help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  up                 Start all services"
	@echo "  down               Remove all services"
	@echo "  reset              Reset all services"
	@echo "  logs               Display and follow logs"
	@echo "  interactive        Connect to container app's shell"
	@echo "  docs               Generate API documentation"
	@echo "  test               Perform Unit and Feature tests"

init:
	@$(shell cp -n $(shell pwd)/.env.example $(shell pwd)/.env 2> /dev/null)

up: init
	@docker-compose up -d

down:
	@docker-compose down -v

reset: down up

logs:
	@docker-compose logs -f

interactive: up
	@docker-compose exec -ti app bash

docs: up
	@docker-compose exec app php artisan scribe:generate --force

test: up
	@docker-compose exec app php artisan test
