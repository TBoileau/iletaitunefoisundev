# Installation

[Retour au sommaire](index.md)

## Git
Dans un premier temps, il est nécessaire de cloner le projet :
```
git clone git@github.com:incentive-factory/iletaitunefoisundev.git
cd iletaitunefoisundev
```

## Pré-requis
* Docker
* Docker-composer

## Installation
L'installation consiste préparer et lancer les containers Docker :
```
make install
```

## Remise à zéro
Vous souhaitez recharger les containers docker, c'est simple :
```
make reset
```

## Initialiser les environnements de `dev` et de `test`:
```
make initialize
```

## Lancement du serveur du client (angular)
```
make client
```

Et voilà il ne vous reste plus qu'à développer !

## Sans docker

Si vous n'utilisez pas Docker, voici la marche à suivre.

### Pré-requis
* Node LTS (16)
* PHP 8.1
* MySQL 8
* Neo4J 3


### Server

Créer un fichier `.env.$APP_ENV.local` dans `server` :
```dotenv
DATABASE_URL="mysql://root:password@127.0.0.1:3306/iletaitunefoisundev?serverVersion=8.0"
NEO4J_URL=bolt://user:password@127.0.0.1:7687
```

```
cd server
composer install
php bin/console lexik:jwt:generate-keypair --overwrite -n --env=APP_ENV
php bin/console doctrine:database:drop --if-exists --force --env=APP_ENV
php bin/console doctrine:database:create --env=APP_ENV
php bin/console doctrine:schema:update --force --env=APP_ENV
php bin/console app:neo4j:delete-nodes --env=APP_ENV
php bin/console doctrine:fixtures:load -n --env=APP_ENV
```

Lancer le serveur de Symfony `symfony serve`. Assurez vous que le serveur se lance bien sur le port **8000**.

### Client
```
npm install -g @angular/cli@latest
cd client
npm install
```

