# ApiBileMo
Installation 1 - 
Git clone the project

https://github.com/marialalij/ApiBileMo.git 2 - Install libraries

php bin/console composer install 3 - Create database

a) Update DATABASE_URL .env file with your database configuration. DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

b) Create database: php bin/console doctrine:database:create

c) php bin/console doctrine:migration:migrate

d) Insert data php bin/console doctrine:fixtures:load

4 - Create private and public keys with OpenSSL

mkdir -p config/jwt openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
JWT_PASSPHRASE=yourpassphrase in 
 .env file 
