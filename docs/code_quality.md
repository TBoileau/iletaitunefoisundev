# Qualité du code

[Retour au sommaire](index.md)

*Disclaimer : Attention, l'utilisation des outils d'analyse statique présentés ci-dessous ne sont pas garant d'un code de qualité.*

## Analyse du back-end

Afin de vérifier en partie la qualité du code et de la configuration de Symfony, nous utiliserons les outils suivants :
* Composer : Permet de vérifier si les fichiers [`composer.json`](/server/composer.json) et [`composer.lock`](/server/composer.lock) sont bien synchronisés.
* Lint container : Permet de vérifier les services définis dans le conteneur, notamment la bonne conformité des arguments injectés.
* PHP Copy Paste Detector : Permet de vérifier la présence de code copier/coller.
* PHP Stan : Permet de vérifier la cohérence du typage dans le code.

Lancer l'analyse avec la commande `make analyse`.

A été mis en place pour réparer des erreurs de style, l'utilisation de PHP CS Fixer, que vous pouvez exécuter avec la commande suivante : `make fix`.
