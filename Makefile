image.build:
	docker build . -f .docker/Dockerfile.prod -t fearofcode/rl:$(version)
image.publish:
	docker buildx build . --platform linux/arm64/v8,linux/amd64  --tag fearofcode/rl:$(version) -f .docker/Dockerfile.prod
image.push:
	docker push fearofcode/rl:$(version)