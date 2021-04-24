#!/bin/sh

EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]
then
    >&2 echo 'ERROR: Invalid installer checksum'
    rm composer-setup.php
    exit 1
fi

php composer-setup.php --quiet
RESULT=$?
rm composer-setup.php

composer self-update

sudo cp /vagrant/vagrant/lemurengine.conf /etc/nginx/sites-available/lemurengine.conf
sudo ln -s /etc/nginx/sites-available/lemurengine.conf /etc/nginx/sites-enabled/lemurengine.conf
sudo service nginx restart

exit $RESULT
