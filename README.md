# Customotor — Site vitrine & back-office (PHP MVC)

**Customotor** est une application web orientée **garage / préparation automobile** : vitrine des prestations, mise en avant d’un **Lookbook** de réalisations, gestion des demandes client et des rendez-vous, et une **interface d’administration** pour piloter le contenu.

🎯 Objectifs du projet :
- livrer un site **impactant visuellement** (identité “racing”, pages immersives, carrousels, animations),
- proposer un back-office **structuré et sécurisé** (CRUD, statuts, gestion des médias, CSRF…).

🔗 Dépôt GitHub : https://github.com/xTadzuki/Customotor

---

## ✨ Fonctionnalités

### Côté public
- Pages vitrines : Accueil, Performance (services), Lookbook, Contact
- Lookbook : liste de projets + page détail
- Services regroupés par catégories (prix “à partir de”, descriptions, etc.)
- UI responsive + animations (effets d’apparition, carrousels…)

### Côté administration
- Dashboard (vision globale)
- Gestion Lookbook : création / modification / suppression de projets
- Gestion des images de projets (ordre/tri d’affichage)
- Gestion des demandes (statuts : `new` / `in_progress` / `done` / `archived`)
- Gestion des rendez-vous (statuts : `pending` / `confirmed` / `cancelled`)
- Gestion des avis (statuts : `pending` / `approved` / `rejected`)
- **CRUD Services** : ajouter / modifier / supprimer des services (nom, catégorie, description, prix “à partir de”…)

---

## 🧠 Architecture & logique (MVC)

Le projet suit une architecture **MVC** “maison”, pensée pour rester lisible et maintenable :

- **Controllers** : reçoivent la requête, appellent les modèles, préparent les données, retournent la vue.
- **Models** : accès base de données via PDO (requêtes préparées), logique métier simple, méthodes réutilisables.
- **Views** : templates PHP, composants UI, intégration CSS/JS, et utilisation de `BASE_URL` pour éviter les chemins cassés quand le projet est servi depuis un sous-dossier.


### Routage
Point d’entrée : `public/index.php` (Front Controller).  
Exemples de routes :
- `/lookbook` → liste des projets
- `/lookbook/{id}` → détail projet
- `/admin` → dashboard
- `/admin/projects` → admin lookbook
- `/admin/services` → admin services

> Les liens et assets utilisent `BASE_URL` pour fonctionner correctement en local sous XAMPP (`/customotor/public/...`).

---

## 🧰 Stack technique

- **Back-end** : PHP 8.x (architecture MVC)
- **Base de données** : MySQL / MariaDB
- **Data access** : PDO + requêtes préparées
- **Front-end** : HTML5, CSS3, JavaScript (vanilla)
- **Outils** : XAMPP (Apache/MySQL), Git/GitHub

---

## 🔐 Sécurité (principes appliqués)

- **CSRF** : token par formulaire + vérification côté serveur
- **XSS** : affichage via `htmlspecialchars()`
- **SQL injection** : requêtes préparées PDO
- **Statuts** : valeurs contrôlées via listes blanches (évite états invalides + classes CSS “injectées”)

---

## 🚀 Installation (XAMPP)

### Prérequis
- XAMPP (Apache + MySQL)
- PHP 8.x
- Extension PDO MySQL activée

### Étapes
1. Cloner le repo dans `htdocs` :
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/xTadzuki/Customotor.git customotor
Démarrer Apache et MySQL dans le panneau XAMPP.
Créer la base de données dans phpMyAdmin (ex: customotor) puis importer :
database/schema.sql
database/seed.sql
Configurer la connexion DB dans app/models/Database.php (hôte, nom de DB, user, mdp).
Accéder au site :
Public : http://localhost/customotor/public
Admin : http://localhost/customotor/public/admin

---

## 🔑 Accès administration (démo)

Email : admin@customotor.local
Mot de passe : admin.1234!

## 🧪 Données & contenus

Le projet inclut des scripts SQL pour :
créer la structure (services, catégories, projets, images, demandes, rendez-vous, avis…)
injecter des données de démonstration (seed) pour tester rapidement l’affichage.

## 🗺️ Améliorations possibles

Authentification complète + gestion de rôles
Upload d’images sécurisé (MIME, taille, miniatures, nettoyage des fichiers)
Recherche/filtrage avancé du lookbook
Journal d’activité admin
Tests (PHPUnit) + CI (GitHub Actions)
Version API (JSON) pour une future extension

## 🧩 Choix techniques (et pourquoi)

Cette section explique les décisions d’architecture prises sur Customotor, avec une logique “projet pro” : **lisibilité, maintenabilité, sécurité, et évolutivité**.

### 1) Architecture MVC “maison”
**Pourquoi :**
- Séparer clairement les responsabilités :
  - **Controller** = orchestration (requête → données → vue)
  - **Model** = accès DB + logique métier
  - **View** = rendu HTML + composants UI
- Faciliter la maintenance : modifier une vue sans toucher au SQL, ou refactor un modèle sans casser le front.
- Préparer l’évolution : ajout de nouvelles features (ex: CRUD services, images lookbook, statuts) sans “effet domino”.

**Ce que ça apporte concrètement :**
- fichiers plus courts, plus testables, plus faciles à relire en contexte “dossier pro”
- logique métier centralisée côté modèles (réutilisable dans plusieurs contrôleurs)

---

### 2) Front Controller + routage
**Pourquoi :**
- Avoir un **point d’entrée unique** (`public/index.php`) :
  - centraliser le bootstrapping (autoload, config, session, sécurité)
  - uniformiser le traitement des routes (public/admin)
- Simplifier l’URL design : routes propres, cohérentes, et alignées avec la structure des contrôleurs.

**Bénéfices :**
- code plus cohérent (pas de logique dispersée dans 15 fichiers “accessibles directement”)
- plus simple de sécuriser (ex: middleware/check admin à un endroit)

---

### 3) Accès base de données via PDO + requêtes préparées
**Pourquoi :**
- **Sécurité** : les requêtes préparées protègent des injections SQL.
- **Robustesse** : gestion d’erreurs propre via `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`.
- **Qualité** : standard PHP, portable, clair en contexte pro.

**Bonnes pratiques appliquées :**
- `prepare()` + `execute()` avec paramètres
- `fetch()` / `fetchAll()` selon le besoin
- conversions/validations simples côté serveur (id > 0, types numériques, etc.)

---

### 4) CSRF Tokens sur les formulaires sensibles
**Pourquoi :**
- Les actions admin (create/update/delete) doivent être protégées contre les requêtes forgées.
- Le back-office manipule des données critiques (projets, images, services, statuts) → CSRF obligatoire.

**Principe :**
- un token est généré côté serveur, stocké en session
- injecté dans les formulaires
- vérifié à la réception avant toute mutation

---

### 5) Protection XSS (échappement en sortie)
**Pourquoi :**
- Toutes les données affichées (services, titres projet, alt d’image, descriptions…) peuvent contenir du texte “utilisateur/admin”.
- Le front ne doit jamais interpréter du HTML inattendu.

**Approche :**
- échappement systématique au rendu (`htmlspecialchars()`), sauf cas maîtrisés.

---

### 6) Statuts contrôlés via listes blanches (whitelists)
**Pourquoi :**
- Les statuts (demandes, rendez-vous, avis) servent à :
  - filtrer/organiser côté admin
  - afficher des badges CSS
- Sans whitelist : risque d’état incohérent en DB + classes CSS invalides (voire injection via classes).

**Approche :**
- tableaux de valeurs autorisées (ex: `new`, `in_progress`, `done`, `archived`)
- fallback vers une valeur par défaut si valeur inattendue

---

### 7) `BASE_URL` pour des chemins stables (XAMPP / sous-dossier)
**Pourquoi :**
- En local, le projet est servi sous un chemin du type :
  - `http://localhost/customotor/public`
- Sans `BASE_URL`, les liens peuvent pointer au mauvais endroit (`/assets/...` ou `http://localhost/...`) et provoquer des 404.

**Bénéfices :**
- tous les `href/src` restent corrects :
  - assets (`/assets/css`, `/assets/js`, images…)
  - navigation (public/admin)
- plus simple lors d’un futur déploiement (il suffit d’ajuster `BASE_URL` selon l’environnement)

---

### 8) Conventions UI / CSS (admin vs public)
**Pourquoi :**
- Le site public et le back-office n’ont pas les mêmes contraintes UX :
  - public = branding, immersion, animations
  - admin = densité d’info, lisibilité, efficacité

**Approche :**
- classes dédiées admin (ex: préfixes `cm-admin-*`) pour éviter conflits CSS
- composants réutilisables (badges, boutons, grilles)
- styles “propres” (éviter inline) pour maintenir un CSS stable

---

### 9) Git / GitHub (traçabilité)
**Pourquoi :**
- suivre l’évolution du projet (refactors, ajout CRUD services, corrections routing)
- présenter le travail proprement dans un dossier pro

**Bénéfices :**
- historique clair
- versionnage fiable
- partage/évaluation facilité

## 👤 Auteur

Marie — Développeuse web full stack & UI designer

Projet réalisé dans le cadre d’un dossier professionnel (objectif : démontrer la maîtrise d’un CRUD complet, d’une architecture MVC et d’une intégration front soignée).
