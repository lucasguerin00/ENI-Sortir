# ENI-Sortir

# Sortir.com

## 📌 Contexte du projet

Ce projet est réalisé dans le cadre d’un travail scolaire par un groupe de 3 étudiants.
L’objectif est de développer une plateforme web avec le framework **Symfony**, alors que nous n’avons aucune expérience préalable avec ce framework.
Le projet est mené en **mode Agile**, avec des itérations courtes permettant de livrer rapidement des versions fonctionnelles.

## 🎯 Objectif

La plateforme **Sortir.com** permet aux stagiaires (actuels et anciens) de l’ENI de proposer et d’organiser des sorties (sportives, culturelles, sociales, etc.) et d’y participer.
Elle vise à :

* Favoriser la cohésion sociale entre stagiaires.
* Offrir un canal officiel pour proposer et gérer des sorties.
* Anticiper le nombre de participants, gérer les inscriptions et faciliter la logistique des événements.

## 👥 Parties prenantes

* **ENI** : souhaite offrir un outil à ses stagiaires.
* **Stagiaires ENI** : désirent organiser et participer à des sorties.
* **Équipe projet (nous)** : groupe de 3 étudiants, développeurs de la solution.

## ⚙️ Fonctionnalités principales

* **Gestion des participants** :

  * Inscription (administrée par un administrateur).
  * Profil avec nom, prénom, email et numéro de téléphone.
* **Gestion des sorties** :

  * Création d’une sortie par un organisateur (lieu, date, heure, durée, nombre de places, etc.).
  * Inscription/désinscription à une sortie.
  * Consultation des sorties par site géographique.
* **Rôles et permissions** :

  * **Administrateur** : gère les participants, peut annuler une sortie, désactiver un compte.
  * **Organisateur** : propose une sortie et en gère la logistique.
  * **Participant** : peut consulter et s’inscrire à une sortie.
* **Gestion des lieux et sites** : rattachement des sorties à un site (par ex. Saint-Herblain, La Roche-sur-Yon, etc.).

## 🛠️ Technologies

* **Backend** : Symfony (PHP 8+)
* **Base de données** : MySQL ou PostgreSQL
* **Frontend** : Twig (moteur de template Symfony), HTML, CSS, Bootstrap
* **Gestion de projet** : Git + GitHub, méthode Agile (sprints courts, backlog des fonctionnalités issues du document "Description-Produit").

## 🚀 Installation et lancement

1. Cloner le dépôt :

   ```bash
   git clone https://github.com/<utilisateur>/<projet>.git
   cd projet
   ```
2. Installer les dépendances :

   ```bash
   composer install
   ```
3. Configurer l’environnement :

   * Dupliquer le fichier `.env` en `.env.local`.
   * Renseigner vos identifiants de base de données.
4. Créer la base de données et exécuter les migrations :

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```
5. Lancer le serveur Symfony :

   ```bash
   symfony server:start
   ```
6. Accéder au site sur [http://localhost:8000](http://localhost:8000).

## 📅 Organisation du projet

* Travail en binôme/trinôme avec répartition des tâches (backend, base de données, intégration).
* Suivi des fonctionnalités via un backlog basé sur le fichier **Description-Produit.ods**.
* Développement incrémental (Agile).

## 📖 Documentation

* [Énoncé du projet](./Enonce.pdf)
* [Document de vision](./DocumentVision.pdf)
* [Description produit](./Description-Produit.ods)

## ✨ Résultat attendu

Une application web fonctionnelle permettant de :

* Créer, consulter et gérer des sorties.
* S’inscrire à des événements.
* Favoriser la mise en relation et la cohésion des stagiaires ENI.

---
