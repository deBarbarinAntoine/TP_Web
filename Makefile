# This Makefile was done using 'domake'
# Generated at 20/01/2025

include .envrc

# =================================================================================== #
# HELPERS
# =================================================================================== #

## help: print this help message
.PHONY: help
help:
	@echo 'Usage:'
	@sed -n 's/^##//p' ${MAKEFILE_LIST} | column -t -s ':' | sed -e 's/^/ /'

.PHONY: confirm
confirm:
	@echo -n 'Are you sure? [y/N] ' && read ans && [ $${ans:-N} = y ]

# =================================================================================== #
# COMMANDS
# =================================================================================== #

## run: Run the Php server in development environment
.PHONY: run
run: 
	@DB_PASSWORD='${DB_PASSWORD}' DB_HOST='${DB_HOST}' DB_PORT='${DB_PORT}' DB_NAME='${DB_NAME}' DB_USERNAME='${DB_USERNAME}'\
 		php -S 10.100.10.10:3030
	

## db/psql: Connect to the database
.PHONY: db/psql
db/psql: 
	@psql ${DB_DSN}
	

## db/migrations/new: Create a new migration file for ${name}
.PHONY: db/migrations/new
db/migrations/new: 
	@echo 'Creating migration files for ${name}...'
	migrate create -seq -ext .sql -dir ./migrations ${name}
	

## db/migrations/up: Run the migrations up
.PHONY: db/migrations/up
db/migrations/up:  confirm
	@echo 'Running up migrations...'
	@migrate -path ./migrations -database ${DB_DSN} up
	

## db/migrations/drop: Drop the migrations
.PHONY: db/migrations/drop
db/migrations/drop:  confirm
	@echo 'Dropping migrations...'
	@migrate -path ./migrations -database ${DB_DSN} drop
	

