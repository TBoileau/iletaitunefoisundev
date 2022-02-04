# Architecture technique

[Retour au sommaire](index.md)

## Stack technique
### Langages et outils
- PHP >= 8.1
- Mysql >= 8
- NodeJS >= 16

### Frameworks :
- Symfony >= 6
- Angular >= 13

### Autres :
- EasyAdmin >= 4
- API Platform >= 2.6

## Arborescence
* [.github](../.github) : Dossier contenant les fichiers de configuration du workflow (Github actions).
* [client](../client) : Dossier contenant les fichiers sources du client (Frontend) sous Angular.
* [docker](../docker) : Dossier contenant la configuration des différents containers.
* [docs](../docs) : Dossier contenant la documentation du projet.
* [server](../server) : Dossier contenant les fichiers sources du serveur (Backend) sous Symfony.

## Backend

### Domaine
Les sources du projets sont séparés par domaine pour séparer dans la mesure du possible les différentes modules.

* Admin : Contient les classes nécessaires à la gestion du Back-office avec Easy Admin.
* Adventure : Vous y trouverez toutes les fonctionnalités lié à l'aventure (quête, maps, joueur, ...)
* Content : Contient l'ensemble des classes pour modéliser le contenu du site, comme les cours/quiz/exercices...
* Security : Retrouvez l'ensemble des classes et fonctionnalités liés à la sécurité, comme l'entité User et l'inscription.
* Shared : Contient les classes qui n'appartiennent à aucun domaine ou qui permet d'implémenter un comportement globale à l'application.

### API
L'application est une API qui sera consommé par le front sous Angular. Pour cela, nous utilisons API Platform pour faciliter la modélisation de l'API.

Par défaut, APIP permet de mettre en place rapidement une API avec des endpoints classiques respectant le Niveau 2 de Richardson.
Cependant, nous avons l'ambition d'aovi rune API orientée métier, pour la partie *lecture* des données, nous laisserons API Platform faire son travail.

Par contre, pour la partie *traitement*, nous utiliserons le composant Messenger et notamment **CRS** pour à l'avenir gérer la manière dont seront traités certaines données.
Dans ce cas là, nous n'utiliserons pas les endpoints classiques fournit par API Platform.
Exemple : L'inscription
Nous n'utilisons pas l'endpoint `/api/users` en méthode `POST`, mais plutôt `/api/security/users/register`. C'est bien plus parlant.
