.DEFAULT_GOAL := help

.PHONY: requirements
requirements: ## Install requirements
	@if [ ! -d "vendor" ]; then composer install --quiet; fi
	@if [ ! -d "node_modules" ]; then npm ci --silent --no-update-notifier; fi

.PHONY: up
up: ## Start environment
	@docker compose up --detach --remove-orphans

.PHONY: all full
all full: requirements up ## Build full environment
	@echo "Building full environment..."
	@if [ ! -f config.php ]; then cp config.dist.php config.php; fi
	@docker compose exec --user devilbox php node build --all
	@$(MAKE) import --no-print-directory
	@echo "Done."
	@echo "Open http://localhost:$(shell docker compose port httpd 80 | awk -F: '{print $$2}') in your browser."

.PHONY: extension package
extension package: requirements ## Build extension package
	node build --extension

.PHONY: copy
copy: requirements ## Copy extension
	@echo "Copying extension..."
	@node build --copy

.PHONY: rebuild rb
rebuild rb: ## Rebuild EspoCRM
	@echo "Rebuilding..."
	@docker compose exec --user devilbox php sh -c ' \
		cd site; php rebuild.php; \
	'
	@echo "Done."

.PHONY: clear-cache
clear-cache: ## Clear EspoCRM cache
	@echo "Clearing cache..."
	@docker compose exec --user devilbox php sh -c ' \
		cd site; php clear_cache.php \
	'
	@echo "Done."

.PHONY: cc
cc: copy clear-cache ## Copy extension and clear cache

.PHONY: cr
cr: copy rebuild ## Copy extension and rebuild

.PHONY: config
config: ## Merge config files
	@echo "Merging config files..."
	@if [ ! -f config.php ]; then cp config.dist.php config.php; fi
	@docker compose exec --user devilbox php sh -c ' \
		cd php_scripts; php merge_configs.php; \
	'
	@echo "Done."

.PHONY: import
import: ## Import test data
	@docker compose exec --user devilbox php sh -c ' \
		cd php_scripts; php import.php; \
	'

.PHONY: before-install
before-install: ## Run before install
	@docker compose exec --user devilbox php sh -c ' \
		cd php_scripts; php before_install.php; \
	'

.PHONY: after-install
after-install: ## Run after install
	@docker compose exec --user devilbox php sh -c ' \
		cd php_scripts; php after_install.php; \
	'

.PHONY: before-uninstall
before-uninstall: ## Run before uninstall
	@docker compose exec --user devilbox php sh -c ' \
		cd php_scripts; php before_uninstall.php; \
	'

.PHONY: ecs
ecs: requirements ## Fix PHP code style
	@composer exec ecs check -- --clear-cache --fix

.PHONY: phpstan
phpstan: requirements ## Run PHPStan
	@composer exec phpstan

.PHONY: clean
clean: ## Clean up
	@echo "Stop, remove containers and volumes, and clean up..."
	@docker compose down --volumes --remove-orphans
	@rm --recursive --force site
	@if [ -d .git ]; then git clean -fdX; fi
	@echo "Done."

.PHONY: help
help: ## Display this help screen
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
