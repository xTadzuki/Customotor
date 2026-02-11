🚗 Customotor

Application web professionnelle pour garage automobile spécialisé en performance & préparation moteur.
Projet réalisé dans le cadre du Titre Professionnel Développeur Web & Web Mobile (DWWM).

📌 Présentation:

Customotor est une application web complète développée en PHP 8 avec architecture MVC personnalisée.

Le projet simule un site réel de garage automobile proposant :

Services de performance moteur

Galerie Lookbook

Prise de rendez-vous

Système d’avis clients

Interface administrateur sécurisée

L’objectif était de produire une application :

Structurée

Sécurisée

Responsive

Professionnelle

Exploitable en production

🧱 Architecture:

customotor/
│
├── app/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   ├── helpers/
│
├── config/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   ├── img/
│
├── schema.sql
├── seed.sql
└── README.md


Architecture MVC avec séparation stricte :

Controller → logique métier

Model → accès base de données

View → affichage

🛠️ Stack technique:

🔹 Front-end

HTML5 sémantique

CSS3 (variables globales, Flexbox, Grid)

JavaScript Vanilla

Responsive Design (mobile-first)

UI orientée univers automobile (dark / neon red)

🔹 Back-end

PHP 8 orienté objet

Architecture MVC personnalisée

MySQL

PDO (requêtes préparées)

Sessions sécurisées

CSRF Token

🔹 Outils

XAMPP

VS Code

Git / GitHub

🔐 Sécurité implémentée:

✔ Requêtes préparées PDO
✔ Protection CSRF
✔ Hashage des mots de passe (password_hash)
✔ Gestion des rôles (admin / utilisateur)
✔ Contrôle d’accès aux routes sensibles
✔ Validation des données côté serveur

👤 Fonctionnalités:

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

Modération des avis

🎨 Identité visuelle:

Design immersif inspiré de l’univers performance automobile :

Noir profond

Rouge néon (#e10600)

Effets lumineux dynamiques

Interface moderne et immersive

Focus sur :

Expérience utilisateur

Cohérence graphique

Performance visuelle

⚙️ Installation en local:

1️⃣ Cloner le projet
git clone https://github.com/ton-compte/customotor.git

2️⃣ Placer dans htdocs
C:/xampp/htdocs/customotor

3️⃣ Créer la base de données

Créer une base :

customotor

Importer :

schema.sql

seed.sql

4️⃣ Lancer
http://localhost/customotor/public

📈 Compétences démontrées:

Conception architecture MVC

Développement application full-stack PHP

Sécurisation d’application web

Gestion base de données relationnelle

Structuration projet professionnel

Développement interface responsive moderne

👩‍💻 Auteur:

Marie Bouvier
Développeur Web & Web Mobile
Projet réalisé dans le cadre du Titre Professionnel DWWM