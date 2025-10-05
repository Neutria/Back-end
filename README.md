# Neutria API

API REST développée avec Symfony, API Platform et authentification JWT.

## 🚀 Technologies utilisées

- **Symfony 7.3** - Framework PHP
- **API Platform** - Framework pour créer des APIs REST et GraphQL
- **Doctrine ORM** - ORM pour la gestion de la base de données
- **LexikJWTAuthenticationBundle** - Authentification JWT
- **MariaDB 10.11** - Base de données
- **Docker & Docker Compose** - Containerisation

## 📋 Prérequis

- Docker
- Docker Compose
- Git

## 🔧 Installation

### 1. Cloner le projet

```bash
git clone <url-du-repo>
cd neutria-api
```

### 2. Configuration de l'environnement

Copier le fichier `.env` et ajuster les variables si nécessaire :

```bash
cp .env .env.local
```

Variables importantes à configurer :
- `APP_SECRET` : Clé secrète de l'application
- `DATABASE_URL` : URL de connexion à la base de données
- `JWT_PASSPHRASE` : Passphrase pour les clés JWT

### 3. Lancer les conteneurs Docker

```bash
docker-compose up -d
```

### 4. Installer les dépendances

```bash
docker-compose exec php composer install
```

### 5. Générer les clés JWT

```bash
docker-compose exec php php bin/console lexik:jwt:generate-keypair
```

### 6. Créer la base de données

```bash
docker-compose exec php php bin/console doctrine:database:create
docker-compose exec php php bin/console doctrine:migrations:migrate
```

### 7. (Optionnel) Charger les fixtures

```bash
docker-compose exec php php bin/console doctrine:fixtures:load
```

## 🎯 Utilisation

### Accéder à l'API

L'API est accessible à l'adresse : `http://localhost:8000`

La documentation interactive (Swagger) est disponible à : `http://localhost:8000/api`

### Authentification

#### Obtenir un token JWT

```bash
curl -X POST http://localhost:8000/api/login_check \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'
```

Réponse :
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
}
```

#### Utiliser le token

Ajouter le token dans le header `Authorization` :

```bash
curl -X GET http://localhost:8000/api/users \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
```

## 📁 Structure du projet

```
.
├── config/              # Configuration Symfony
│   ├── packages/        # Configuration des bundles
│   ├── routes/          # Configuration des routes
│   └── jwt/             # Clés JWT (private.pem, public.pem)
├── src/
│   ├── Command/         # Commandes console
│   ├── Controller/      # Contrôleurs
│   ├── Entity/          # Entités Doctrine
│   └── Repository/      # Repositories Doctrine
├── migrations/          # Migrations de base de données
├── var/                 # Cache et logs
├── vendor/              # Dépendances Composer
├── .env                 # Variables d'environnement
├── composer.json        # Dépendances PHP
├── docker-compose.yml   # Configuration Docker
└── Dockerfile           # Image Docker PHP
```

## 🛠️ Commandes utiles

### Doctrine

```bash
# Créer une nouvelle entité
docker-compose exec php php bin/console make:entity

# Créer une migration
docker-compose exec php php bin/console make:migration

# Exécuter les migrations
docker-compose exec php php bin/console doctrine:migrations:migrate

# Mettre à jour le schéma de la base de données
docker-compose exec php php bin/console doctrine:schema:update --force
```

### Cache

```bash
# Vider le cache
docker-compose exec php php bin/console cache:clear

# Réchauffer le cache
docker-compose exec php php bin/console cache:warmup
```

### Tests

```bash
# Lancer les tests
docker-compose exec php php bin/phpunit
```

### Utilisateurs

```bash
# Créer un utilisateur (si commande personnalisée créée)
docker-compose exec php php bin/console app:create-user
```

## 🔐 Sécurité

- Les clés JWT sont générées automatiquement et stockées dans `config/jwt/`
- **Ne jamais committer** les fichiers `.env.local`, `private.pem` et `public.pem`
- Utiliser des mots de passe forts en production
- Configurer CORS selon vos besoins dans `config/packages/nelmio_cors.yaml`

## 🔑 Variables d'environnement

| Variable | Description | Exemple |
|----------|-------------|---------|
| `APP_ENV` | Environnement de l'application | `dev`, `prod` |
| `APP_SECRET` | Clé secrète Symfony | `your-secret-key` |
| `DATABASE_URL` | URL de connexion BDD | `mysql://user:pass@host:3306/db` |
| `JWT_SECRET_KEY` | Chemin vers clé privée JWT | `%kernel.project_dir%/config/jwt/private.pem` |
| `JWT_PUBLIC_KEY` | Chemin vers clé publique JWT | `%kernel.project_dir%/config/jwt/public.pem` |
| `JWT_PASSPHRASE` | Passphrase des clés JWT | `your-passphrase` |
| `CORS_ALLOW_ORIGIN` | Origines CORS autorisées | `^https?://localhost(:[0-9]+)?$` |

## 🐛 Debugging

### Voir les logs

```bash
docker-compose logs -f php
```

### Accéder au conteneur PHP

```bash
docker-compose exec php bash
```

### Vérifier la configuration

```bash
docker-compose exec php php bin/console debug:config
docker-compose exec php php bin/console debug:router
```

## 📝 API Endpoints

### Authentification

- `POST /api/login_check` - Obtenir un token JWT

### Utilisateurs (exemple)

- `GET /api/users` - Liste des utilisateurs (authentification requise)
- `GET /api/users/{id}` - Détails d'un utilisateur
- `POST /api/users` - Créer un utilisateur
- `PUT /api/users/{id}` - Mettre à jour un utilisateur
- `DELETE /api/users/{id}` - Supprimer un utilisateur

## 🚀 Déploiement

### Production

1. Définir `APP_ENV=prod` dans `.env.local`
2. Compiler les assets : `composer dump-env prod`
3. Optimiser l'autoloader : `composer install --no-dev --optimize-autoloader`
4. Vider et réchauffer le cache : `php bin/console cache:clear && php bin/console cache:warmup`

## 🤝 Contribution

1. Fork le projet
2. Créer une branche pour votre feature (`git checkout -b feature/AmazingFeature`)
3. Commit vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence GNU General Public License v3.0 (GPL-3.0).

Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 👥 Auteurs

- Votre nom - [@votre-github](https://github.com/votre-username)

## 🙏 Remerciements

- Symfony
- API Platform
- LexikJWTAuthenticationBundle