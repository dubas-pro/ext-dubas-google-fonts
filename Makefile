.DEFAULT_GOAL := help

IS_DOCKER := $(shell [ -f /.dockerenv ] && echo true)

.PHONY: full
full: ## Build full environment (will destroy existing data)
ifdef IS_DOCKER
	@echo "Error: This Makefile should not be run inside a Docker container."
	@exit 1
endif
	docker compose up --detach --remove-orphans --force-recreate --build && sleep 3
	docker exec --interactive --tty --user devilbox php sh -c ' \
		cp config.php.dist config.php; \
		node build --all; \
		espo import:test-data --skip-default; \
	'

.PHONY: package
package: ## Build extension package
	node build --extension

.PHONY: cc
cc: ## Copy extension and clear cache
	node build --copy
	docker exec --interactive --tty --user devilbox php sh -c ' \
		espo admin:clear-cache; \
	'

.PHONY: cr
cr: ## Copy extension and rebuild
	node build --copy
	docker exec --interactive --tty --user devilbox php sh -c ' \
		espo admin:rebuild; \
	'

.PHONY: ccr
ccr: ## Copy extension, clear cache and rebuild
	node build --copy
	docker exec --interactive --tty --user devilbox php sh -c ' \
		espo admin:clear-cache; \
		espo admin:rebuild; \
	'

.PHONY: clean
clean: ## Clean up
	docker compose down --volumes --remove-orphans
	git clean -fdX


.PHONY: help
help: ## Display this help screen
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
