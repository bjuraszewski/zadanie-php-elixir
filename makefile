up:
	docker compose up -d --build

up-dev:
	docker compose -f docker-compose.dev.yaml up -d --build

stop:
	docker compose stop

stop-dev:
	docker compose -f docker-compose.dev.yaml stop

down:
	docker compose down