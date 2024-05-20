
.PHONY: install deploy

deploy:

	ssh sharearticle 'cd public_html/sharearticle && git pull && php bin/console asset-map:compile && php bin/console cache:clear'
 
 
	# ssh sharearticle 'cd public_html/sharearticle && git pull && php ./composer.phar install --no-dev --optimize-autoloader && php bin/console asset-map:compile && php ./composer.phar dump-env prod && php bin/console cache:clear'
 



