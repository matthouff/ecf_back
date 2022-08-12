# ECF Back Berthelot Matthias

## 1. Téléchargement ou clonage du projet
**Téléchargement du zip :**  
Vous pouvez télécharger le [zip du projet](https://github.com/matthouff/ecf_back/archive/refs/heads/main.zip).
<br><br>
**Cloner le projet via les lignes de commandes :**  
Pour cloner le fichier il vous suffit d'entrer la commande suivante à partir du fichier que vous voulez:  
```
git clone https://github.com/matthouff/ecf_back.git
```
<br>  

## 2. Pré-requis
Pour pouvoir passer à l'étape suivante il faut d'abord entrer les information de la base de donnée dans un fichier .env.local .  
1. Allez dans le fichier `.env.local` qui nosu permettra de nous relier à la base de donnée. 
   ```
    ###> doctrine/doctrine-bundle ###
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
    ###< doctrine/doctrine-bundle ###
   ```
   ***Attention**: Il y a plusieurs critères à prendre en comptes:  
   1. Modifier le port en `127.0.0.1:8889` si vous êtes sur mac *(sinon gardez celui que vous avez)*
   2. Remplacez `db_user` par l'identifiant de votre base de donnée
   3. Remplacez `db_password` par le mot de passe de votre base de donnée *(Ne mettez rien si vous n'avez pas de mot de passe)*
   4. Remplacez `db_name` par le nom que vous voulez donner à votre base de donnée

## 3. Instalations
Pour instaler tout le necessaire pour le bon fonctionnement de l'application, il faudra entrer quelques lignes de commandes:  
<sub>*(Faites attention de bien être dans le bon dossier sinon l'instalation de se fera pas)*</sub>  
```
composer install
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migration:migrate
symfony serve
```

## 4. Ajouter du faux contenu


Tout d'abord, il faut aussi relier la base de donnée pour l'environnement de test.  
Pour se faire il faut aller dans le fichier `env.test.local` et ajouter cette ligne:
```
DATABASE_URL="mysql://root:root@127.0.0.1:8889/tests_ecf?serverVersion=5.7"
```
***Attention**, si vous êtes sur mac il faudra changer le port en `127.0.0.1:8889`

<br>

Pour ajouter du faux contenu il suffira simplement de taper dans la commande:
```
symfony console doctrine:fixtures:load
```

<br>

Pour ce qui est des identifiants de connexion test, on a :  
### ROLE_USER:
- login: testUser
- mdp: 1234
### ROLE_ADMIN:
- login: matth
- mdp: 1234

<br><br>
Vous êtes maintenant prêt à vous servir de l'application
