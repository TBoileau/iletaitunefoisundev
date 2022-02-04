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

Et voilà il ne vous reste plus qu'à développer !
