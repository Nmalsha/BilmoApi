# BilmoApi

## Project 7 - Parcours développeur d'application PHP/Symfony

I build this project to learn to create API using symfony (using v 5.4).

### Environnement de développement

    -Linux
    -Composer 2.3.7
    -PHP 7.4.3
    -Apache2
    -MySQL
    -git

### Instalation

#Clonez le repository Github

    git clone https://github.com/ampueropierre/api-bilemo.git

Installer les dépendances

    composer install

#Créer la BDD

    php bin/console doctrine:database:create

#Créer les tables

    php bin/console doctrine:schema:create

#Installer la Fixture (démo de données fictives)

        php bin/console doctrine:fixture:load

#URL de la documentation

    http://localhost:8000/api/doc

Tester les requêtes avec un compte User

    login: admin@gmail.com

    password: admin123

Enjoy !
