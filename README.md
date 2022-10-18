# rl
## Build example
```shell
version=0.0 make image.build
```

### Example https://github.com/Legion112/rl

## As CLI tool
```shell
docker run -w /tmp -v $(pwd):/tmp/ fearofcode/rl:0.0 replace docker-compose.yaml docker-compose-replaced.yaml
```
# Troubleshooting