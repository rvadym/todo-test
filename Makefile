.PHONY : help
help : Makefile
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

##
##- On host computer ------------------------------------------------------------------------------------------

##
##-- Docker

build:                                             ## Builds images
	docker-compose build

start:                                             ## Starts entire setup
	docker-compose up -d

stop:                                              ## Stops entire setup
	docker-compose down

logs:                                              ## Attaches to containers logs
	docker-compose logs -f

clear:                                             ## Clear docker system and volumes with no prompt
	echo "y" | docker system prune
	echo "y" | docker volume prune


##
##-- SSH

ssh-back:                                          ## SSH into backend container
	docker-compose exec todotest_back bash

ssh-db:                                            ## SSH into DB container
	docker-compose exec todotest_db mysql -uroot -pexample todotest

##
##- Inside containers -----------------------------------------------------------------------------------------

##
##-- Back server

server:                                            ## Starts back server
	cd ./public && php -S 0.0.0.0:8888 -t ./ ./index.php

test:                                              ## Run PHPUnit tests
	bin/phpunit $(f)

test-coverage-html:                                ## Run PHPUnit tests with coverage
	bin/phpunit --coverage-html=./coverage $(f)

xdebug-enable:                                     ## Enable xDebug
	sed -i -r 's@^;zend_extension=xdebug.so@zend_extension=xdebug.so@' /etc/php7/conf.d/xdebug.ini

xdebug-disable:                                    ## Disable xDebug
	sed -i -r 's@^zend_extension=xdebug.so@;zend_extension=xdebug.so@' /etc/php7/conf.d/xdebug.ini

xdebug-status:                                     ## Displays xDebug status and configs
	php -i | grep xdebug

phan:                                              ## Runs PHAN against entire PHP codebase
	bin/phan

apbacki-ci-local:                                  ## Local CI for back
	make xdebug-disable
	make phan
	make xdebug-enable
	make test-coverage-html


##
##- CI -----------------------------------------------------------------------------------------------------------------

