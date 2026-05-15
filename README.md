# 🔨 Enchères Secrètes

Plateforme d'enchères en ligne développée avec Symfony 7.
Les utilisateurs placent des offres secrètes sur des objets rares publiés par un administrateur.
L'admin dispose d'un back Office où il peux gérer les enchères et les utilisateurs.

## 🛠️ Technologies

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-000000?style=flat&logo=symfony&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=flat&logo=bootstrap&logoColor=white)

## ✨ Fonctionnalités

### 👤 Utilisateur
- Consultation des objets disponibles avec tri par catégorie
- Détail d'un objet (titre, description, prix de départ, statut)
- Placement d'une enchère secrète (1 offre max par objet)
- Offre obligatoirement supérieure au prix de départ

### 🔐 Administrateur
- Gestion complète des objets (ajout, modification, suppression)
- Publication et dépublication d'un objet
- Clôture des enchères → attribution automatique au meilleur enchérisseur
- Visualisation de toutes les offres par objet

### 🛡️ Sécurité
- Authentification Symfony (ROLE_USER / ROLE_ADMIN)
- Protection CSRF
- Contrôle des accès par rôle

## 🚀 Installation
### 1. Prérequis — Docker Desktop

> Docker Desktop est nécessaire pour lancer le projet.

**Windows :**
- Téléchargez Docker Desktop : https://www.docker.com/products/docker-desktop
- Lancez l'installeur et suivez les étapes
- Redémarrez votre machine si demandé
- Vérifiez l'installation :
```bash
docker --version
```

**Linux (Ubuntu) :**
```bash
sudo apt update
sudo apt install docker.io docker-compose -y
sudo systemctl start docker
```
```bash
git clone https://github.com/MohandBir/Encheres_secrete.git
cd encheres-secretes
composer install
```

Configurez votre `.env` :
```env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/encheres_secretes"
```
Lancer le projet avec Docker
```bash
docker compose up -d
```
Installer les dépendances et initialiser la base de données
```bash
docker exec -it encheres_php sh
docker compose exec php composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

### 6. Accéder à l'application
Ouvrez votre navigateur : http://localhost:8080

## 📁 Structure

```
src/
├── Controller/    # ItemController, OfferController, AdminController
├── Entity/        # Item, Category, Offer, User
├── Form/
└── Repository/
```