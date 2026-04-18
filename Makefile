COMPOSER = composer
COMPOSER_PROD = composer2
DOCKER_COMPOSE = docker compose
ARTISAN = php artisan
NPM = npm

# Colors for output
BOLD = \033[1m
UNDERLINE = \033[4m
CLR_RESET = \033[0m
CLR_GREEN = \033[32m
CLR_YELLOW = \033[33m
CLR_RED = \033[31m

help:
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s$(CLR_RESET) %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Installation ——————————————————————————————————————————————————————————————

install: ## Install all dependencies (need php composer npm installed)
	@echo "$(CLR_YELLOW) Installing dependencies...$(CLR_RESET)"
	@$(COMPOSER) install
	@$(NPM) install
	@$(ARTISAN) key:generate

## —— Developpement ——————————————————————————————————————————————————————————————

dev: ## Run the development environment
	@echo "$(CLR_YELLOW) Launching development environment...$(CLR_RESET)"
	@$(DOCKER_COMPOSE) up -d mariadb
	@$(COMPOSER) run dev

clean: ## Clean the cache and compiled files
	@echo "$(CLR_YELLOW) Cleaning cache and compiled files...$(CLR_RESET)"
	@$(ARTISAN) optimize:clear
	@$(ARTISAN) config:clear
	@$(ARTISAN) route:clear
	@$(ARTISAN) view:clear
	@$(ARTISAN) cache:clear

fclean: ## Run database migrations
	@echo "$(CLR_YELLOW) Running database migrations...$(CLR_RESET)"
	@$(ARTISAN) migrate:fresh --seed
	@$(MAKE) clean


## —— Docker Utils ———————————————————————————————————————————————————————————————————

up: ## Start the production environment
	@echo "$(CLR_YELLOW) Starting production environment...$(CLR_RESET)"
	@docker compose up -d

down: ## Stop the production environment
	@echo "$(CLR_YELLOW) Stopping production environment...$(CLR_RESET)"
	@docker compose down

## —— Docker Production Deployment ————————————————————————————————————————————————————

build-frontend: ## Build frontend assets locally
	@echo "$(CLR_YELLOW)📦 Installing frontend dependencies...$(CLR_RESET)"
	@$(NPM) install 
	@echo "$(CLR_YELLOW)🏗️  Building frontend assets with environment variables...$(CLR_RESET)"
	@$(NPM) run build

build: ## Build Docker container images
	@echo "$(CLR_YELLOW)🐳 Building Docker container...$(CLR_RESET)"
	@$(DOCKER_COMPOSE) build --pull

deploy: ## Complete Docker deployment
	@echo "$(CLR_YELLOW)🔄 Building and starting production stack...$(CLR_RESET)"
	@$(DOCKER_COMPOSE) up -d --build
	@echo "$(CLR_YELLOW)🗄️  Running database migrations inside the app container...$(CLR_RESET)"
	@$(DOCKER_COMPOSE) exec -T app php artisan migrate --force
	@echo "$(CLR_YELLOW)⚡ Optimizing Laravel caches...$(CLR_RESET)"
	@$(DOCKER_COMPOSE) exec -T app php artisan optimize
	@echo "$(CLR_GREEN)✅ Deployment completed successfully!$(CLR_RESET)"

.PHONY: dev install migration prod deploy help clean fclean user build-frontend build deploy
