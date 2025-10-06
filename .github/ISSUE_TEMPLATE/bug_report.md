---
name: Bug Report
about: Signaler un bug ou un problème
title: '[BUG] '
labels: 'bug, needs-triage'
assignees: ''
---

## 🐛 Description du bug

<!-- Une description claire et concise du problème -->

## 🔴 Sévérité

- [ ] 🔥 Critique (application inutilisable, perte de données)
- [ ] 🔴 Haute (fonctionnalité majeure cassée)
- [ ] 🟠 Moyenne (fonctionnalité mineure cassée)
- [ ] 🟡 Basse (problème cosmétique, workaround possible)

## 📋 Étapes pour reproduire

1. Aller sur '...'
2. Cliquer sur '...'
3. Faire défiler jusqu'à '...'
4. Observer l'erreur

## ✅ Comportement attendu

<!-- Description claire de ce qui devrait se passer normalement -->

## ❌ Comportement actuel

<!-- Description de ce qui se passe réellement -->

## 📸 Captures d'écran

<!-- Si applicable, ajouter des captures d'écran pour illustrer le problème -->

## 🔧 Environnement

**Backend :**
- OS : [ex: Ubuntu 22.04, macOS 14.0, Windows 11]
- PHP : [ex: 8.2.0]
- Symfony : [ex: 7.3.0]
- Base de données : [ex: MariaDB 10.11.2]
- Docker : [ex: 24.0.0]

**Frontend (si applicable) :**
- Navigateur : [ex: Chrome 120, Firefox 115, Safari 17]
- Appareil : [ex: Desktop, iPhone 15, iPad]
- Résolution : [ex: 1920x1080]

**Environnement :**
- [ ] Développement (local)
- [ ] Staging
- [ ] Production

## 📊 Logs et messages d'erreur

```
Coller ici les logs, stack traces ou messages d'erreur pertinents
```

**Fichiers de logs concernés :**
- `var/log/dev.log`
- `var/log/prod.log`
- Console navigateur

## 🔍 Informations supplémentaires

### Requête API (si applicable)

**Endpoint :**
```http
POST /api/endpoint
Authorization: Bearer token...
Content-Type: application/json

{
  "data": "example"
}
```

**Réponse :**
```json
{
  "error": "Message d'erreur",
  "code": 500
}
```

### Code concerné (si identifié)

**Fichier :** `src/Controller/ExampleController.php`
**Ligne :** 45

```php
// Code problématique
```

## 🔄 Fréquence de reproduction

- [ ] Se produit à chaque fois (100%)
- [ ] Se produit souvent (>50%)
- [ ] Se produit parfois (<50%)
- [ ] Se produit rarement
- [ ] S'est produit une seule fois

## 🛠️ Solutions de contournement

<!-- Y a-t-il un moyen temporaire de contourner ce problème ? -->

## 🔗 Issues liées

<!-- Lien vers d'autres issues similaires ou connexes -->
<!-- - #123 -->
<!-- - #456 -->

## 📌 Tâches de résolution

- [ ] Identifier la cause racine
- [ ] Écrire un test qui reproduit le bug
- [ ] Corriger le bug
- [ ] Vérifier que les tests passent
- [ ] Tester manuellement
- [ ] Ajouter des tests de non-régression
- [ ] Mettre à jour la documentation si nécessaire
- [ ] Déployer le correctif

## 💡 Contexte additionnel

<!-- Toute autre information utile : quand le bug est apparu, impact utilisateur, données affectées, etc. -->