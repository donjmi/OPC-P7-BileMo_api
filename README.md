[![Codacy Badge](https://app.codacy.com/project/badge/Grade/d342ce162e3445c89dac5a26e759db02)](https://app.codacy.com/gh/donjmi/OPC-P7-BileMo_api/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

# **SP7 - BileMo API**

Projet 7 du parcours PHP / Symfony sur OpenClassrooms.

Créez un web service exposant une API

**Configuration:**

- Symfony 5.4.3 (framework MVC libre écrit en PHP)
- PHP 7.4.3
- Bundle ApiPlatform

**Installation**

1. Clonez ou telechargez le repository.
   https://github.com/donjmi/OPC-P7-BileMo_api.git

2. Modifiez le fichier .env (ou créer un env.local) avec vos parametres de BDD et d'email.

3. Composer install -> pour installer toutes les dependances.

4. Modifier fichier env. pour préparer la bdd et Lancer la commande pour installer/configurer/naviguer sur le projet :
   **composer prepare**
ces commandes vont être lancés pour configurer le site:

- php bin/console doctrine:database:drop --if-exists --force
- php bin/console doctrine:database:create
- php bin/console doctrine:schema:update --force
- php bin/console doctrine:fixtures:load -n

5. Générer vos clés pour l'utilisation de JWT Token
 - symfony console lexik:jwt:generate-keypair

 ou avec open ssl
    $ mkdir -p config/jwt

    $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    
    $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

    vérifier les paramétre dans votre fichier .env :
    ###> lexik/jwt-authentication-bundle ###
        JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
        JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
        JWT_PASSPHRASE=VotrePassePhrase
        ###< lexik/jwt-authentication-bundle ###

6. Tester l'api avec les utilisateurs :
 admin : admin@admin.fr , pass = admin
 Client : bilemo@bilemo.fr, pass = admin


