<?php

// Read .env
$env = json_decode(file_get_contents('.env.lapin'));

// A config example for ThinkPHP
file_put_contents('.env', <<<EOF
APP_NAME=Twt_Survey
APP_ENV=local
APP_KEY=base64:wYxpGSEhdhi1bnFuJWP71VA70Sk8g7trLH91zlzPPvY=
APP_DEBUG=false
APP_LOG_LEVEL=debug
APP_URL=https://survey.twtstudio.com
DB_CONNECTION=mysql
DB_HOST={$env->DB_HOST}
DB_PORT=3306
DB_DATABASE={$env->DB_NAME}
DB_USERNAME={$env->DB_USER}
DB_PASSWORD={$env->DB_PASS}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.qq.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=


EOF
);

// Remove the script
unlink(__FILE__);
unlink('.env.lapin');
