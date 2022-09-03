# Qualité du code

[Retour au sommaire](index.md)

*Disclaimer : Attention, l'utilisation des outils d'analyse statique présentés ci-dessous ne sont pas garant d'un code de qualité.*

## Analyse et autocorrection

Afin de vérifier en partie la qualité du code et de la configuration de Symfony, nous utiliserons les outils suivants :
* Composer : Permet de vérifier si les fichiers [`composer.json`](/composer.json) et [`composer.lock`](/composer.lock) sont bien synchronisés.
* PHP Stan : Permet de vérifier la cohérence du typage dans le code.

Lancer l'analyse avec la commande `make analyse`.

A été mis en place pour réparer des erreurs de style, l'utilisation de PHP CS Fixer, que vous pouvez exécuter avec la commande suivante : `make fix`.

Vous pouvez lancer ces deux commandes en une seule fois : `make qa`.