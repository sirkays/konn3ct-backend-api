#!/bin/sh
## Install php
sudo apt-get install software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update
sudo apt-get install -y php7.4
sudo apt install php7.4-fpm php7.4-common php7.4-mysql php7.4-xml php7.4-xmlrpc php7.4-curl php7.4-gd php7.4-imagick php7.4-cli php7.4-dev php7.4-imap php7.4-mbstring php7.4-opcache php7.4-redis php7.4-soap php7.4-zip -y
## Restart php service
sudo service php7.4-fpm restart

## Install composer
sudo curl -sS https://getcomposer.org/installer | php
## Make composer globally available
sudo mv composer.phar /usr/bin/composer
chmod +x /usr/bin/composer

## Make storage folder public
cd /var/www/laravel
sudo chmod 777 -R storage

cat <<EOF >> /var/www/laravel/.env
# konn3ct env
APP_NAME=konn3ct
APP_ENV=testing
APP_KEY=base64:qCH4YnnxsFbbq7fO5CFCNHmNaD5YkVQnmDCbg9CeyvA=
APP_DEBUG=true
APP_URL=http://dev.konn3ct.net

BBB_SECURITY_SALT=m4MCOwc9LgJe5LDN60Tuep3trHGWQzR2MyxvWDeVP4
BBB_SERVER_BASE_URL=https://dev.konn3ct.net/bigbluebutton/

RAVE_SEC_KEY=FLWSECK-8413a366026524dd44f3f7d0c94dc8df-X
RAVE_PUB_KEY=FLWPUBK-329ded1f22f31d9e12d7c32df9f9c514-X
PAYSTACK_PUB_KEY=pk_test_bd05eba1cce13b699d3caeced63436ec3d8b1862
PAYSTACK_PRV_KEY=sk_test_ddff4e03cae3738a930dabefcf39cd8b64d8a0f3

APP_DEPLOY_SECRET=changemenoworfacetheconsequences

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=konn3ct
DB_USERNAME=toor
DB_PASSWORD=passiword

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=database
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mail.konn3ct.com
MAIL_PORT=587
MAIL_USERNAME=info@konn3ct.com
MAIL_PASSWORD=Nf5cHLCaO7sm
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=info@konn3ct.com
MAIL_FROM_NAME=Konn3ct

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

EOF

sudo composer install
## Laravel is now ready to be served

## creating backup of config file
cp /etc/nginx/sites-available/bigbluebutton /etc/nginx/sites-available/bigbluebutton.bak

## Enabling konn3ct frontend --load index.php
sudo sed -i 's/index  index.html index.htm;/index index.php index.html index.htm;\ntry_files $uri $uri\/ \/index.php?$query_string;/' /etc/nginx/sites-available/bigbluebutton

## Add php fpm to nginx
<!-- sudo sed -i 's/#error_page  404  \/404.html;/location ~ \.php$ { \nroot   \/var\/www\/bigbluebutton-default; \nfastcgi_pass unix:\/var\/run\/php\/php7.4-fpm.sock; \nfastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name; \ninclude fastcgi_params; }/' /etc/nginx/sites-available/bigbluebutton -->

cat <<EOF >> /etc/bigbluebutton/nginx/php.nginx
# support php
location ~ .php$ {
  root   /var/www/bigbluebutton-default;
  fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
  fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
  include fastcgi_params;
}
EOF


## copy public files from laravel
rm -R /var/www/bigbluebutton-default/*
cp -R /var/www/laravel/public/* /var/www/bigbluebutton-default/

cd /var/www/bigbluebutton-default/assets/images
rm -R /var/www/bigbluebutton-default/favicon.ico
cp konn3cticon.ico /var/www/bigbluebutton-default/favicon.ico
echo "copying logo.png for player"
cp konn3ctIcon.png /var/bigbluebutton/playback/presentation/2.3/logo.png

cd /var/www/bigbluebutton-default/docs
rm -R /var/www/bigbluebutton-default/default.pdf
cp Whiteboard.pdf /var/www/bigbluebutton-default/default.pdf
rm /etc/bigbluebutton/bbb-conf/apply-config.sh
cp apply-config.sh /etc/bigbluebutton/bbb-conf/apply-config.sh
chmod +x /etc/bigbluebutton/bbb-conf/apply-config.sh

## Create folder for profile pix
cd /var/www/bigbluebutton-default
mkdir profile-photos

## give public permission to photos folder
chmod -R 777 profile-photos

## rewrite filesystem to public folder
sudo sed -i 's/app\/public/..\/..\/bigbluebutton-default\//' /var/www/laravel/config/filesystems.php

sudo sed -i 's/\/storage/\//' /var/www/laravel/config/filesystems.php

## Pointing to laravel folder
sudo sed -i 's/\/..\//\/..\/laravel\//' /var/www/bigbluebutton-default/index.php

# restart servers
sudo systemctl restart nginx

# install MySQL
sudo apt -y install mysql-server mysql-client libmysqlclient-dev;

