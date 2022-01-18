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
make install
```

1. Sélectionnez votre environnement
```
Please select your environment (defaults to dev)
  [dev ] dev
  [test] test
  [prod] prod
> 
```

2. Sélectionnez le driver de votre base de données
```
Please select your the database driver (defaults to mysql)
  [mysql     ] MySQL
  [sqlite    ] SQLite
  [postgresql] PostgreSQL
  [oci8      ] Oracle
> 
```

3. Si vous utilisez le driver `sqlite`, saisissez le chemin du fichier SQLite
```
Please provide the relative path of SQLite file : |
``` 

4. Sinon, saisissez les codes d'accès ainsi que le nom de la base de données 
```
Please provide the database user name (default to root) : |
Please provide the database user password (default to password) : |
Please provide the database name (default to iletaitunefoisundev) : |
```