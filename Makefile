start: up install clear welcome

clear:
	docker compose -f devops/docker-compose.yaml exec playground bin/console c:c

up:
	docker compose -f devops/docker-compose.yaml build --pull && \
	docker compose -f devops/docker-compose.yaml up -d --force-recreate

install:
	docker compose -f devops/docker-compose.yaml exec playground sh -c 'if [ ! -d vendor ]; then composer install; fi'

down:
	docker compose -f devops/docker-compose.yaml down

ssh:
	docker compose -f devops/docker-compose.yaml exec playground sh

welcome:
	@echo ""
	@echo "🎉 Project is up and running!"
	@echo "🔗 Open your browser and visit: http://playground.localhost"
	@echo "🚀 Happy coding!"
	@echo ""