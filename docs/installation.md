# Installation

[Retour au sommaire](index.md)

## Git
Dans un premier temps, il est nécessaire de cloner le projet :
```
git clone git@github.com:incentive-factory/iletaitunefoisundev.git
cd iletaitunefoisundev
```


## Paramétrage de l'environnement
L'installation consiste à paramétrer notre environnement de développement.
```
make i env=test|dev|prod
```

1. Sélectionnez le driver de votre base de données
```
Please select your the database driver (defaults to mysql)
  [mysql     ] MySQL
  [sqlite    ] SQLite
  [postgresql] PostgreSQL
  [oci8      ] Oracle
> 
```

2. Si vous utilisez le driver `sqlite`, saisissez le chemin du fichier SQLite
```
Please provide the relative path of SQLite file : |
``` 

3. Sinon, saisissez les codes d'accès ainsi que le nom de la base de données 
```
Please provide the database user name (default to root) : |
Please provide the database user password (default to password) : |
Please provide the database name (default to iletaitunefoisundev) : |
```

## Remise à zér de la base de données et chargement des fixtures

```
make p env=test|dev
```