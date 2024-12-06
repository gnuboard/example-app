### .env

```
APP_NAME=라라벨11
APP_ENV=local
APP_KEY=base64:0d0SwF5E9eypYb7tXY51X8NM4VbZg5ZywlVhE2clR98=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://127.0.0.1:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=지메일아이디@gmail.com
MAIL_PASSWORD=idqslufewjibjxyi
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="지메일아이디@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

# Google OAuth 설정
# https://console.cloud.google.com/apis
GOOGLE_CLIENT_ID=***
GOOGLE_CLIENT_SECRET=***
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# GitHub OAuth 설정
# https://github.com/settings/developers
GITHUB_CLIENT_ID=***
GITHUB_CLIENT_SECRET=***
GITHUB_REDIRECT_URI="${APP_URL}/auth/github/callback"

# Kakao OAuth 설정
# https://developers.kakao.com/console/app
# https://github.com/SocialiteProviders/Kakao
# REST API 키
KAKAO_CLIENT_ID=***
# 제품 설정 > 카카오 로그인 > 보안
KAKAO_CLIENT_SECRET=***
KAKAO_REDIRECT_URI="${APP_URL}/auth/kakao/callback"

# Naver OAuth 설정
# https://developers.naver.com/apps/#/myapps
# https://buchet.tistory.com/55
NAVER_CLIENT_ID=***
NAVER_CLIENT_SECRET=***
NAVER_REDIRECT_URI="${APP_URL}/auth/naver/callback"
```
