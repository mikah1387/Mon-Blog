
.PHONY: install deploy 

deploy:
	cd public_html/sharearticle && git pull && make install
 

install:
	php ./composer.phar install --no-dev --optimize-autoloader
	php bin/console importmap:install
	php bin/console asset-map:compile 
	php ./composer.phar dump-env prod 
	php bin/console cache:clear 


