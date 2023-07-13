php app/console doctrine:schema:update --force --dump-sql
php app/console assetic:dump
php app/console assetic:dump --env=prod
php app/console assets:install
php app/console assets:install --env=prod
php app/console cache:clear
php app/console cache:clear --env=prod
chmod 777 -R app/cache app/logs
chown -R avcc:www-data app/cache app/logs
