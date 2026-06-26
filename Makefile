## Project task runner.
##
## Run `make help` to see all available targets.
## Default target is `help` so a bare `make` is always safe.

# ---- Configuration ------------------------------------------------------

PNPM     ?= pnpm
COMPOSER ?= composer
ZIP      ?= zip

# Theme metadata — derived from this directory and style.css.
# Override on the command line if needed: `make zip THEME_SLUG=custom`
# Use a shell basename (not Make's $(notdir)) so paths with spaces work — e.g.
# the "Local Sites" folder Local by Flywheel creates by default on macOS.
THEME_SLUG    ?= $(shell basename "$(CURDIR)")
THEME_VERSION ?= $(shell awk '/^Version:/ { print $$2; exit }' style.css)
DIST_DIR      ?= dist
ZIP_NAME      ?= $(THEME_SLUG)-$(THEME_VERSION).zip

# Fail fast with a clear message if a required tool is missing, instead of
# letting the recipe fall through to "command not found".
REQUIRE_PNPM     = @command -v $(PNPM) >/dev/null 2>&1 || { echo "✗ '$(PNPM)' not found. Run 'corepack enable' (Node 18+ ships with Corepack)."; exit 1; }
REQUIRE_COMPOSER = @command -v $(COMPOSER) >/dev/null 2>&1 || { echo "✗ '$(COMPOSER)' not found. See https://getcomposer.org/download/"; exit 1; }
REQUIRE_ZIP      = @command -v $(ZIP) >/dev/null 2>&1 || { echo "✗ '$(ZIP)' not found. Install it via your package manager (apt install zip / brew install zip)."; exit 1; }

# ---- Default target -----------------------------------------------------

.DEFAULT_GOAL := help

# ---- Targets ------------------------------------------------------------

.PHONY: help install update watch build zip check check-php fix fix-php

help: ## Show this help
	@awk 'BEGIN { FS = ":.*?## "; printf "\nUsage: make \033[36m<target>\033[0m\n\nTargets:\n" } \
	     /^[a-zA-Z_-]+:.*?## / { printf "  \033[36m%-18s\033[0m %s\n", $$1, $$2 } \
	     /^##@ / { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) }' $(MAKEFILE_LIST)
	@echo ""

##@ Setup

install: ## Install all dependencies (pnpm + composer)
	$(REQUIRE_PNPM)
	$(REQUIRE_COMPOSER)
	$(PNPM) install
	$(COMPOSER) install

update: ## Update all dependencies within declared version ranges (pnpm + composer)
	$(REQUIRE_PNPM)
	$(REQUIRE_COMPOSER)
	$(PNPM) update --interactive
	$(COMPOSER) update

##@ Development

watch: ## Run the Vite dev server with live-reload
	$(REQUIRE_PNPM)
	$(PNPM) run dev

build: ## Build production assets via Vite
	$(REQUIRE_PNPM)
	$(PNPM) run build

##@ Release

zip: build ## Build then package a deployable theme zip into ./dist
	$(REQUIRE_COMPOSER)
	$(REQUIRE_ZIP)
	@echo "→ Installing production-only PHP dependencies..."
	$(COMPOSER) install --no-dev --optimize-autoloader --quiet
	@echo "→ Packaging $(THEME_SLUG) v$(THEME_VERSION)..."
	@mkdir -p "$(DIST_DIR)"
	@rm -f "$(DIST_DIR)/$(ZIP_NAME)"
	@cd .. && $(ZIP) -rq "$(CURDIR)/$(DIST_DIR)/$(ZIP_NAME)" "$(THEME_SLUG)" \
	    -x "$(THEME_SLUG)/node_modules/*" \
	    -x "$(THEME_SLUG)/$(DIST_DIR)/*" \
	    -x "$(THEME_SLUG)/src/*" \
	    -x "$(THEME_SLUG)/.github/*" \
	    -x "$(THEME_SLUG)/.idea/*" \
	    -x "$(THEME_SLUG)/.vscode/*" \
	    -x "$(THEME_SLUG)/Makefile" \
	    -x "$(THEME_SLUG)/README.md" \
	    -x "$(THEME_SLUG)/package.json" \
	    -x "$(THEME_SLUG)/pnpm-lock.yaml" \
	    -x "$(THEME_SLUG)/composer.json" \
	    -x "$(THEME_SLUG)/composer.lock" \
	    -x "$(THEME_SLUG)/phpcs.xml" \
	    -x "$(THEME_SLUG)/.eslintrc.json" \
	    -x "$(THEME_SLUG)/vite.config.js" \
	    -x "*/.git/*" \
	    -x "*/.gitignore" \
	    -x "*/.DS_Store" \
	    -x "*/Thumbs.db" \
	    -x "*.swp"
	@echo "→ Restoring dev PHP dependencies..."
	@$(COMPOSER) install --quiet
	@echo "✓ Created $(DIST_DIR)/$(ZIP_NAME)"

##@ Linting & formatting

check: check-php ## Run every checker

check-php: ## Check PHP code with WP Coding Standards
	$(REQUIRE_COMPOSER)
	vendor/bin/phpcs

fix: fix-php ## Run every fixer

fix-php: ## Auto-fix PHP code with WP Coding Standards
	$(REQUIRE_COMPOSER)
	vendor/bin/phpcbf
