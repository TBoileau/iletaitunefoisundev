# Installation

[Retour au sommaire](index.md)

## Git
Dans un premier temps, il est nécessaire de cloner le projet :
```
git clone git@github.com:incentive-factory/iletaitunefoisundev.git
cd domain
```

## Pré-requis
* PHP 8.1

## Installation
L'installation consiste installer les dépendances :
```
make install db_user=root db_password=password db_host=127.0.0.1 db_name=iletaitunefoisundev
```

*Note : N'oubliez pas de changer les options !*

C'est tout ! Il ne vous reste plus qu'à développer !
