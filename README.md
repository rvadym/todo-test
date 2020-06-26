## Bootstrap

### Makefile commands

#### Fix prev setup

```
sudo chmod 777 -R var/mysql
```

#### Start docker setup

```
make build
make start
```

#### API

```
make ssh-back
composer install # (optional)
bin/doctrine-migrations migrate (optional)
make server # keep it running in this console
```

#### API testing

```
make ssh-back
composer install # (optional)
bin/doctrine-migrations migrate (optional)
make test
```

#### Stop docker setup

```
make stop
make clear # (optional)
```

## Development

### API

`http://localhost:18087`
