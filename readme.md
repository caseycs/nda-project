# nda project =)

## HowTos

### Run

`docker-compose up`

Wait a bit for composer install to be complete and open http://docker-machine

### Test

```
docker-compose -f docker-compose.yml up composer
docker-compose -f docker-compose.yml -f docker-compose.test.yml up phpunit
```

## Assumptions/Questions

* There are sometimes legacy percentages in csv, I made extra map to convert them to steps
* Sometimes percentage is missing, I report such cases to logs
