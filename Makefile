
.PHONY: install deploy

deploy:

	ssh sharearticle 'cd public_html/sharearticle && git pull && php ./composer.phar install --no-dev --optimize-autoloader && php bin/console importmap:install && php bin/console asset-map:compile && php ./composer.phar dump-env prod && php bin/console cache:clear'
 
 




