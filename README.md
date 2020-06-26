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

#### Admin mock

```
make ssh-admin
make admin-mock # keep it running in this console
```

#### Admin

```
make ssh-admin
yarn install (optional)
make admin-front # keep it running in this console
```

#### Admin testing

1. Make sure admin folder got .env.testing file with
```
VUE_APP_ENV_API_URL=http://localhost:7113/api/v1
```
2. Start mock server
```
make ssh-admin
make admin-mock
```
3. Open new terminal and start the project in testing mode
```
make admin-testfront
```
4. Open new terminal and run testcafe tests
```
make ssh-admin
make admin-test
```

#### Stop docker setup

```
make stop
make clear # (optional)
```

## Development

### API

`http://localhost:18087`

### Frontend

#### UI

`http://localhost:14200`

#### Karma tests

`http://localhost:19876`
