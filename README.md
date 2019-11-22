# Banking CQRS + Event Sourcing Workshop

This is a basic event sourced PHP application leveraging the EventSauce library.  
It was built live, from scratch, during my workshop on how to go from Event Modeling to BDD/TDD.

[Slide deck](slides/Building%20an%20Event%20Sourced%20System%20with%20Event%20Modeling%20and%20CQRS%20-%20Alex%20Gemmell.pdf)

## Docker Container Management

Build the PHP container or restart it the container was in a stopped state
```
> docker-compose up -d
```

## Install PHP Composer Packages
```
> docker exec -it workshop-php-box composer install
```

## Run Tests
```
> docker exec -it workshop-php-box php vendor/bin/phpunit tests/*
```
