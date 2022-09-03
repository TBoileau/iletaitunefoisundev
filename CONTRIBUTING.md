# Contribution

Veuillez prendre un moment pour prendre connaissance de ce document afin de suivre facilement le processus de contribution.

## Issues
[Issues](https://github.com/incentive-factory/domain/issues) est le canal idéal pour les rapports de bug, les nouvelles fonctionnalités ou pour soumettre une `pull requests`, cependant veillez à bien respecter les restrictions suivantes :
* N'utiliser par ce canal pour vos demandes d'aide personnelles (utilisez [Stack Overflow](http://stackoverflow.com/)).
* Il est interdit d'insulter ou d'offenser d'une quelconque manière en commentaire d'un `issue`. Respectez les opinions des autres et rester concentré sur la discussion principale.
* Quand vous rédigez une issue, sélectionnez le label `En attente`. Un maintainer s'occupera de valider l'issue afin de commencer à la traiter.
* Chaque issue est symbolisée par les labels suivants :
    * Priorité
    * Complexité
    * Types

## Rapport de bug
Un bug est une erreur concrète, causée par le code présent dans ce `repository`.

Guide :
1. Assurez-vous de ne pas créer un rapport déjà existant, pensez à faire une recherche avant de publier une issue.
2. Vérifiez que le bug est corrigé, en essayant sur la dernière version du code sur la branche `production` ou `develop`.
3. Isolez le problème permet de créer un scénario de test simple et identifiable.

## Nouvelle fonctionnalité
Il est toujours apprécié de proposer de nouvelles fonctionnalités. Cependant, prenez le temps de réfléchir, assurez-vous que cette fonctionnalité correspond bien aux objectifs du projet.

C'est à vous de présenter des arguments solides pour convaincre les développeurs du projet des bienfaits de cette fonctionnalité.

## Pull request
De bonnes `pull requests` sont d'une grande aide. Elles doivent rester dans le cadre du projet et ne doit pas contenir de `commits` non lié au projet.

Veuillez demander avant de poster votre `pull request`, autrement vous risquez de passer gaspiller du temps de travail, car l'équipe projet ne souhaite pas intégrer votre travail.

Suivez ce processus afin de proposer une `pull request` qui respecte les bonnes pratiques :
1. Forkez le repository sur votre compte
2. Créez une nouvelle branche qui contiendra votre fonctionnalité, modification ou correction :
    * Pour une nouvelle fonctionnalité ou modification :
        ```
        git checkout develop
        git checkout -b feature/<feature-name>
        ```
    * Pour une nouvelle correction :
        ```
        git checkout production
        git checkout -b hotfix/<feature-name>
        ```
   *Vous pouvez aussi utiliser [git-flow](https://danielkummer.github.io/git-flow-cheatsheet/index.fr_FR.html) pour simplifier la gestion de vos branches :*
    * Pour une nouvelle fonctionnalité ou modification :
        ```
        git flow feature start <feature-name>
        ```
    * Pour une nouvelle correction :
        ```
        git flow hotfix start <hotfix-name>
        ```
3. `Commit` vos changements, veillez à respecter la convention de nommage de vos `commits` de la manière suivante :
    ```
    <type>: <subject>
    <BLANK LINE>
    <body>
    <BLANK LINE>
    <footer>
    ```
   L'en-tête est obligatoire.

   Types :
    * **build** : Changements qui ont un effet sur le système (installation de nouvelles dépendances, composer, npm, environnements, ...)
    * **ci** : Configuration de l'intégration continue
    * **cd** : Configuration du déploiement continu
    * **docs** : Modifications de la documentation
    * **feat** : Nouvelle fonctionnalité
    * **fix** : Correction (`hotfix`)
    * **perf** : Modification du code qui optimise les performances
    * **refactor** : Toute modification du code dans le cadre d'un refactoring
    * **style** : Corrections propres au coding style (PSR-12)
    * **test** : Ajout d'un nouveau test ou correction d'un test existant

   Exemple :
   ```
   feat (security): inscription (#ISSUE_ID)
   
   * Implémentation de UserController
   * Creation register.html.twig
   * Ajout de la méthode `register` dans UserRepository
   ```
4. Tests et analyses du code :
   Avant de **push** sur Github, pensez à éxecuter une analyse statique des fichiers ainsi que l'ensemble des tests.

5. Poussez votre branche sur votre `repository` :
    ```
    git push origin <branch-name> 
    ```
6. Ouvrez une nouvelle `pull request` avec un titre et une description précise.

## Versionnement
Respecter `Semantic Versioning 2` :
> Given a version number MAJOR.MINOR.PATCH, increment the:
>
> MAJOR version when you make incompatible API changes,
>
> MINOR version when you add functionality in a backwards-compatible manner, and
>
> PATCH version when you make backwards-compatible bug fixes.