# tawba_zehaf_package
1. Schéma de la Base de Données (ERD)

Le projet repose sur un schéma de base de données relationnelle décrivant :

Entités principales :
auteurs : Cette table gère les auteurs avec des informations uniques comme leur email.
packages : Contient des informations de base sur les packages, incluant une description et une date d'ajout.
auteurs_packages : Gère la relation many-to-many entre auteurs et packages.
versions : Stocke les détails des versions associées à un package spécifique.
users : Gère les utilisateurs du système, avec un rôle défini.
2. Diagramme UML (Cas d’Utilisation)

Un diagramme de cas d’utilisation illustre les interactions entre les acteurs et le système. Voici les éléments principaux :

Acteurs :

Utilisateur : Peut rechercher des packages et consulter les informations.

Interactions :

Ajouter un nouveau package ou une nouvelle version ou nouveau auteur.

Rechercher un package par mot-clé ou auteur.

Afficher la liste des packages ou auteurs.
3. Configuration de l’Environnement

Prérequis :

Serveur local : laragon.

Langage backend : PHP.

Base de données : MySQL.

Frontend : HTML/CSS, JavaScript.

Éditeur de code : VS Code
4. Scripts SQL

Création de la Base de Données et des Tables :

Packages : ID, nom, description.

Auteurs : ID, nom, email.

Versions : ID, numéro de version, date de sortie, package_id.
auteurs_packages:

Opérations :

Insertion : Ajouter des auteurs, packages ou versions.

Lecture : Lister les packages ou auteurs.

Jointures : Requête pour afficher les packages avec leurs auteurs.
5. Fonctionnalités en PHP

Formulaires d’Ajout :

Formulaire pour ajouter un auteur.

Formulaire pour ajouter un package ou une version.

Affichage des Données :

Liste des packages avec leurs versions.

Liste des auteurs et leurs contributions.
§§documentation§§

# JS PACKAGES Management System

## Description
Un système de gestion de packages utilisant PHP et MySQL, configuré pour fonctionner avec Laragon. Ce projet permet aux utilisateurs d'ajouter, de gérer et de suivre des packages, des versions et des auteurs.

## Prérequis
- [Laragon](https://laragon.org/) (recommandé pour l'environnement local)
- Navigateur web moderne

## Installation

1. Clonez ce dépôt :
   ```bash
   git clone https://github.com/Youcode-Classe-E-2024-2025/tawba_zehaf_package.git
Déplacez le projet dans le répertoire Laragon :
C:\laragon\www\jspackages
Configurez la base de données :
Créez une base de données dans phpMyAdmin ou HeidiSQL (nom recommandé : jspackages).
Importez le fichier sql/database.sql pour créer les tables.
Configurez le fichier php/db.php avec vos informations MySQL :
php

$host = "localhost";
$username = "root";
$password = ""; // Par défaut pour Laragon
$database = "jspackages";
Lancez Laragon et accédez au projet dans votre navigateur :
http://localhost/jspackages/php/


Fonctionnalités
Gestion des auteurs
Gestion des packages
Gestion des versions
Authentification (connexion/déconnexion)