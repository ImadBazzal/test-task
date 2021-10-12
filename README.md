# Test task. Time breakdowns

### Description

Task took around 2 hours, I used standard Symfony 5 installation with PHP 8.0

3rd-party libraries are: `myclabs/php-enum` and `nesbot/carbon` 

As a persistent storage I use symfony cache with filesystem driver.

### Requirements:

- Docker
- Docker-compose

How to:

- run `make build` to build containers and install dependencies
- run `make up` to start containers
- run `make test` to run phpunit tests
- `curl` directory contains files with curl requests to test application 
- if you use phpstorm - feel free to run [Breakdown.http](Breakdown.http)