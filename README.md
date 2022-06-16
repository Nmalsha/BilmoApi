# BilmoApi

## Project 7 - Parcours développeur d'application PHP/Symfony

I build this project to learn symfony (using v 5.4).

### Configuration jwt

    Jwt Generating the Public and Private Key

composer require lexik/jwt-authentication-bundle

    Generating the Public and Private Key

$ mkdir config/jwt
$ openssl genrsa -out config/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
Password jwt: 543565376YYHD1947

    Configuring the Bundle

lexik_jwt_authentication:
private_key_path: %kernel.root_dir%/../var/jwt/private.pem
public_key_path: %kernel.root_dir%/../var/jwt/public.pem
pass_phrase: %jwt_key_pass_phrase%
token_ttl: 3600

    Test jwt console

$ bin/console debug:container jwt

    Get a JWT Token:

$ curl -X POST -H "Content-Type: application/json" http://localhost:8000/login_check -d '{"username":"johnmack","password":"test"}'
-> { "token": "[TOKEN]" }

    Example of accessing secured routes:

$ curl -H "Authorization: Bearer [TOKEN]" http://localhost:8000/api
-> Logged in as johnmack
