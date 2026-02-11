# Dossier-Pro---Customotor

Application web de garage automobile – Performance & Préparation moteur
📌 Description du projet

Customotor est une application web développée dans le cadre du Titre Professionnel Développeur Web Fullstack (DWWM).

Le projet représente le site d’un garage automobile spécialisé en :

Préparation moteur

Optimisation performance

Reprogrammation

Projets lookbook

Gestion de rendez-vous

L’objectif était de développer une application web complète avec :

Front-end moderne et responsive

Architecture back-end MVC sécurisée

Système d’authentification

Interface d’administration

Gestion dynamique des contenus

🧱 Architecture technique

Le projet est développé en PHP 8 avec une architecture MVC personnalisée.

customotor/
│
├── app/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   ├── helpers/
│
├── config/
│
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   ├── img/
│
├── schema.sql
├── seed.sql
└── README.md

🛠️ Technologies utilisées
Front-end

HTML5 sémantique

CSS3 (Flexbox, Grid, variables CSS)

JavaScript Vanilla

Responsive design (mobile-first)

Back-end

PHP 8 orienté objet

Architecture MVC

MySQL

PDO (requêtes préparées)

Sessions sécurisées

Tokens CSRF

Environnement

XAMPP (Apache / MySQL)

VS Code

Git / GitHub

🔐 Sécurité

Le projet intègre plusieurs mécanismes de sécurité :

Requêtes préparées PDO (protection SQL injection)

Protection CSRF sur les formulaires

Hashage des mots de passe (password_hash)

Gestion des rôles (admin / utilisateur)

Contrôle d’accès aux routes sensibles

Validation des données côté serveur

👤 Fonctionnalités principales
Utilisateur

Inscription / Connexion

Consultation des services

Consultation du lookbook

Prise de rendez-vous

Dépôt d’avis

Administrateur

Dashboard sécurisé

CRUD Services

CRUD Projets Lookbook

Gestion des rendez-vous

Validation / modération des avis

🎨 Identité visuelle

Le design est orienté univers automobile performance :

Noir profond

Rouge néon (#e10600)

Effets lumineux

Typographie moderne

Interface immersive

Une attention particulière a été portée à :

L’UX

La cohérence visuelle

L’expérience mobile

⚙️ Installation en local
1️⃣ Cloner le projet
git clone https://github.com/ton-compte/customotor.git

2️⃣ Placer dans le dossier htdocs (XAMPP)
C:/xampp/htdocs/customotor

3️⃣ Créer la base de données

Ouvrir phpMyAdmin

Créer une base nommée : customotor

Importer :

schema.sql

seed.sql

4️⃣ Configurer la connexion

Modifier :

app/models/Database.php

5️⃣ Lancer le projet
http://localhost/customotor/public

📈 Objectifs pédagogiques

Ce projet m’a permis de valider les compétences suivantes :

Développer la partie front-end d’une application web sécurisée

Développer la partie back-end d’une application web sécurisée

Concevoir une architecture MVC

Sécuriser une application PHP

Structurer un projet professionnel

📌 État du projet

🟢 Projet fonctionnel
🟢 Architecture stable
🟢 Interface admin opérationnelle
🟢 Sécurité implémentée

👩‍💻 Auteur

Marie Bouvier
Titre Professionnel Développeur Web et Web Mobile
