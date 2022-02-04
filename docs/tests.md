# Tests

[Retour au sommaire](index.md)

## Pyramide de tests

1. **Test unitaire** : l'objectif est d'isoler un morceau de code afin d'en valider son comportement. 
2. **Test de composant** : le but est de valider le paramétrage de l'application (mapping, validation, etc...).
3. **Test d'intégration** : l'intérêt de ces tests est de s'assurer que les adapteurs avec les infrastructures sont fonctionnels.
4. **Test fonctionnel** : l'objectif est de simuler des requêtes (HTTP, commandes, ...) afin de s'assurer que la réponse est bien traitée.

## Executer les tests

* Tests unitaires : `make unit-tests`
* Tests de composant : `make component-tests`
* Tests d'intégration : `make integration-tests`
* Tests fonctionnels : `make functional-tests`

Vous pouvez aussi lancer tous les tests avec la commande `make tests`. 
