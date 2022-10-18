# rl
## Build example
```shell
version=0.0 make image.build
```

### Example https://github.com/Legion112/rl

## As CLI tool
```shell
docker run -it  -v $(pwd)/:/tmp/  -v /var/run/docker.sock:/var/run/docker.sock fearofcode/rl:0.0 replace -vvv /tmp/docker-compose.yml /tmp/docker-compose.yml
```
# Troubleshooting