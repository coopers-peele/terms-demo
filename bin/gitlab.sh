git checkout develop
git pull
ln -s /home/gitlab_ci_runner/.symfony/CPTermsDemo.yml app/config/parameters.yml
bin/composer.phar install
php app/console cache:clear --no-warmup --env=dev
php app/console propel:model:build
php app/console propel:migration:migrate
php app/console assets:install --symlink
setfacl -Rm u:gitlab_ci_runner:rwx,g:gitlab_ci_runner:rwx,d:u::rwx,d:g::rwx app/cache
chmod -R 777 app/cache
chmod -R 777 app/logs
chmod -R ug+s app/cache
chmod -R ug+s app/logs
