## Étapes pour configurer un projet Laravel 9 existant sur un nouvel ordinateur

1. Installer Node.js et NPM (Node Package Manager)
   - Télécharger et installer Node.js à partir de https://nodejs.org/en/download/
   - Vérifier l'installation en exécutant `node -v` et `npm -v` dans le terminal.

2. Installer Composer
   - Télécharger et installer Composer à partir de https://getcomposer.org/download/
   - Vérifier l'installation en exécutant `composer -v` dans le terminal.

3. Installer les dépendances NPM et Composer du projet
   - Se déplacer dans le dossier du projet avec la commande `cd nom-projet`.
   - Exécuter la commande `npm install` pour installer toutes les dépendances NPM requises.
   - Exécuter la commande `composer install` pour installer toutes les dépendances Composer requises.

4. Configurer la base de données
   - Créer une nouvelle base de données pour votre projet.
   - Modifier le fichier `.env` pour refléter les informations de connexion à votre base de données.

5. Générer la clé de l'application
   - Dans le terminal, exécutez la commande `php artisan key:generate`.

6. Démarrer le serveur de développement
   - Exécuter la commande `php artisan serve` pour démarrer le serveur de développement.
   - Ouvrir un navigateur et accéder à l'URL `http://localhost:8000` pour vérifier que le projet est en cours d'exécution.

## Erreur possible
Si le serveur n'arrive pas a se lancer donner les droits d'écriture au dossier storage avec la commande `sudo chmod -R 777 storage` puis relancer le serveur.