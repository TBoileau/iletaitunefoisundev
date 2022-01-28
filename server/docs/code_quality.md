# Qualité du code

[Retour au sommaire](index.md)

*Disclaimer : Attention, l'utilisation des outils d'analayse statique présentés ci-dessous ne sont pas garant d'un code de qualité.*

## Analyse du back-end

Afin de vérifier en partie la qualité du code et de la configuration de Symfony, nous utiliserons les outils suivants :
* Composer - `make composer-valid` : Permet de vérifier si les fichiers [`composer.json`](/server/composer.jsoner.json) et [`composer.lock`](/server/composer.locker.lock) sont bien synchronisés.
* Lint container - `make container-lint` : Permet de vérifier les services définis dans le conteneur, notamment la bonne conformité des arguments injectés.
* PHP Copy Paste Detector - `make phpcpd` : Permet de vérifier la présence de code copier/coller.
* Churn PHP - `make curn-php` : Permet d'identifier des fichiers qui peuvent être refactorés.
* PHP Stan - `make phpstan` : Permet de vérifier la cohérence du typage dans le code.

A été mis en place pour réparer des erreurs de style, l'utilisation de PHP CS Fixer, que vous pouvez éxecuter avec la commande suivcante : `make php-cs-fixer`.
