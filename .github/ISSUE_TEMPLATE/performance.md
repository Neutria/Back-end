---
name: Performance Issue
about: Signaler ou améliorer les performances
title: '[PERF] '
labels: 'performance'
assignees: ''
---

## ⚡ Problème de performance

<!-- Description claire du problème de performance constaté -->

## 📊 Type de performance

- [ ] 🐌 Temps de réponse API lent
- [ ] 💾 Utilisation excessive de mémoire
- [ ] 🗄️ Requêtes base de données lentes
- [ ] 📦 Chargement de page lent
- [ ] 🔄 Traitement batch lent
- [ ] 🌐 Problème de mise en cache
- [ ] 📈 Scalabilité limitée

## 🔍 Localisation

**Endpoint / Fonctionnalité concerné(e) :**
<!-- Ex: GET /api/users, Page de dashboard, etc. -->

**Code concerné :**
- Fichier : `src/`
- Méthode / Fonction : 

## 📉 Métriques actuelles

**Temps de réponse :**
- Actuel : X ms / s
- Attendu : Y ms / s

**Utilisation ressources :**
- CPU : X %
- Mémoire : X MB
- Requêtes DB : X queries

**Volume de données :**
- Nombre d'enregistrements : 
- Taille des données : 

## ✅ Objectifs de performance

**Temps de réponse cible :** 
**Utilisation mémoire cible :**
**Autres métriques :**

## 🔬 Analyse / Profiling

```
# Résultats du profiling, slow query logs, etc.
```

**Outils utilisés :**
- [ ] Symfony Profiler
- [ ] Blackfire
- [ ] New Relic
- [ ] MySQL slow query log
- [ ] Chrome DevTools

## 💡 Solution proposée

<!-- Comment peux-tu améliorer les performances ? -->

**Approches possibles :**
- [ ] Optimisation des requêtes SQL
- [ ] Ajout d'index base de données
- [ ] Mise en cache (Redis, Memcached)
- [ ] Lazy loading
- [ ] Pagination
- [ ] Optimisation algorithme
- [ ] CDN pour assets statiques
- [ ] Compression
- [ ] Autre : 

## 🎯 Impact attendu

**Amélioration estimée :**
- Temps de réponse : -X%
- Utilisation mémoire : -Y%
- Requêtes DB : -Z%

## 🔄 Étapes pour reproduire

1. 
2. 
3. 
4. Observer la lenteur

## 🖥️ Environnement

- OS : 
- PHP : 
- Symfony : 
- Base de données : 
- Environnement : [ ] Dev [ ] Staging [ ] Production

## 📌 Tâches d'optimisation

- [ ] Analyser le problème avec profiler
- [ ] Identifier le goulot d'étranglement
- [ ] Implémenter la solution
- [ ] Mesurer l'amélioration
- [ ] Tester la non-régression
- [ ] Documenter l'optimisation

## ⚠️ Risques

<!-- Y a-t-il des risques avec l'optimisation proposée ? -->

## 💬 Contexte additionnel

<!-- Graphiques, screenshots du profiler, etc. -->