# Daric - Gestionnaire de Finances Personnelles

Application complète de gestion de finances personnelles construite avec **Laravel 13** et **Vue.js 3** (Inertia.js).

## 🎯 Fonctionnalités

### Version 1 (MVP) ✅
- ✅ **Authentification** complète (inscription, connexion, réinitialisation mot de passe)
- ✅ **CRUD Comptes** - Gérez vos comptes (cash, banque, mobile money) avec soldes multi-devises
- ✅ **CRUD Catégories** - Catégories par défaut + catégories personnalisées avec couleurs et icônes
- ✅ **CRUD Transactions** - Ajout, modification, suppression avec mise à jour automatique des soldes
- ✅ **Filtres avancés** - Filtrer par compte, catégorie, période, type
- ✅ **Tableau de bord dynamique** avec :
  - Solde total et statistiques mensuelles
  - Graphiques (camembert dépenses, courbes évolution)
  - Liste des dernières transactions
  - Vue d'ensemble des comptes

### Version 2+ (Fonctionnalités avancées) ✅
- ✅ **Transferts entre comptes** avec gestion automatique des soldes
- ✅ **Budgets mensuels** par catégorie avec barres de progression colorées
- ✅ **Transactions récurrentes** (abonnements) générées automatiquement
- ✅ **Graphiques interactifs** (Chart.js) :
  - Camembert des dépenses par catégorie
  - Courbe d'évolution mensuelle (6 derniers mois)
- ✅ **Suivi de dettes/prêts** entre personnes avec statut de paiement
- ✅ **Multi-devises** (XAF, EUR, USD, GBP, etc.)
- ✅ **Export CSV** des relevés de transactions

## 🛠️ Technologies utilisées

### Backend
- **Laravel 13** - Framework PHP moderne
- **Laravel Breeze** - Authentification avec Inertia
- **SQLite** - Base de données (facilement remplaçable par MySQL/PostgreSQL)
- **Eloquent ORM** - Gestion de la base de données
- **Observers** - Mise à jour automatique des soldes
- **Form Requests** - Validation robuste
- **Policies** - Autorisation granulaire
- **Task Scheduling** - Génération automatique des transactions récurrentes

### Frontend
- **Vue.js 3** - Framework JavaScript réactif
- **Inertia.js** - SPA sans API
- **Tailwind CSS** - Framework CSS utilitaire
- **Chart.js + vue-chartjs** - Graphiques interactifs
- **Vite** - Build tool moderne et rapide

## 📦 Installation

### Prérequis
- PHP 8.2+
- Composer
- Node.js 18+ & NPM
- SQLite (ou MySQL/PostgreSQL)

### Étapes d'installation

```bash
# 1. Cloner le projet
git clone <votre-repo>
cd Daric

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances JavaScript
npm install

# 4. Copier le fichier d'environnement
cp .env.example .env

# 5. Générer la clé d'application
php artisan key:generate

# 6. Créer la base de données et exécuter les migrations + seeders
php artisan migrate:fresh --seed

# 7. Compiler les assets
npm run build  # ou npm run dev pour le développement

# 8. Lancer le serveur
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

### Compte de test
```
Email: test@example.com
Mot de passe: password
```

## 🗄️ Structure de la base de données

### Tables principales

**users** - Utilisateurs
- id, name, email, password, timestamps

**accounts** - Comptes financiers
- id, user_id, name, type (cash|banque|mobile_money), balance, currency
- Relation: belongsTo User, hasMany Transactions

**categories** - Catégories de transactions
- id, user_id (nullable pour les catégories par défaut), name, type (income|expense), color, icon
- Relation: belongsTo User, hasMany Transactions

**transactions** - Transactions financières
- id, account_id, category_id, type, amount, date, description
- Relation: belongsTo Account, belongsTo Category
- **Observer actif** : Met à jour automatiquement le solde du compte

**transfers** - Transferts entre comptes
- id, from_account_id, to_account_id, amount, date, description
- Relation: belongsTo Account (x2)
- **Observer actif** : Met à jour les soldes des deux comptes

**budgets** - Budgets mensuels
- id, user_id, category_id, month (YYYY-MM), limit_amount
- Relation: belongsTo User, belongsTo Category
- Attributs calculés: spent_amount, progress_percentage

**recurring_transactions** - Transactions récurrentes
- id, account_id, category_id, type, amount, frequency (daily|weekly|monthly|yearly), next_date, is_active
- Relation: belongsTo Account, belongsTo Category

**debts** - Dettes et prêts
- id, user_id, person_name, amount, direction (je_dois|me_doit), status, paid_amount, due_date
- Relation: belongsTo User
- Attribut calculé: remaining_amount

## 🚀 Fonctionnalités clés

### 1. Observers Eloquent
Les **TransactionObserver** et **TransferObserver** gèrent automatiquement la mise à jour des soldes :
- **Création** : Ajoute/soustrait le montant
- **Modification** : Annule l'ancien montant puis applique le nouveau
- **Suppression** : Reverse la transaction

### 2. Tâche planifiée (Scheduler)
```php
// routes/console.php
Schedule::command('transactions:process-recurring')->daily();
```

Pour activer le scheduler en production, ajoutez au crontab :
```bash
* * * * * cd /path/to/daric && php artisan schedule:run >> /dev/null 2>&1
```

Ou exécutez manuellement :
```bash
php artisan transactions:process-recurring
```

### 3. Export de données
Export CSV des transactions avec filtres :
```
GET /export/transactions/csv?start_date=2024-01-01&end_date=2024-12-31&account_id=1
```

### 4. Graphiques dynamiques
- **Dépenses par catégorie** : Camembert avec couleurs personnalisées
- **Évolution mensuelle** : Courbe revenus vs dépenses sur 6 mois

## 📁 Structure du projet

```
app/
├── Console/Commands/
│   └── ProcessRecurringTransactions.php  # Commande pour générer les transactions récurrentes
├── Http/
│   ├── Controllers/
│   │   ├── AccountController.php          # CRUD Comptes
│   │   ├── TransactionController.php      # CRUD Transactions + filtres
│   │   ├── CategoryController.php         # CRUD Catégories
│   │   ├── BudgetController.php           # CRUD Budgets
│   │   ├── TransferController.php         # Transferts entre comptes
│   │   ├── RecurringTransactionController.php
│   │   ├── DebtController.php             # Gestion des dettes
│   │   ├── DashboardController.php        # Tableau de bord avec stats
│   │   └── ExportController.php           # Export CSV/PDF
│   ├── Requests/
│   │   ├── StoreAccountRequest.php        # Validation création compte
│   │   ├── StoreTransactionRequest.php
│   │   └── ... (autres Form Requests)
│   └── Policies/
│       └── AccountPolicy.php              # Autorisation CRUD comptes
├── Models/
│   ├── User.php
│   ├── Account.php
│   ├── Transaction.php
│   ├── Category.php
│   ├── Budget.php                         # Avec attributs calculés
│   ├── Transfer.php
│   ├── RecurringTransaction.php
│   └── Debt.php
└── Observers/
    ├── TransactionObserver.php            # Mise à jour auto du solde
    └── TransferObserver.php               # Mise à jour des 2 comptes

resources/js/
├── Components/
│   ├── StatCard.vue                       # Carte statistique
│   ├── AccountCard.vue                    # Carte compte
│   ├── TransactionList.vue               # Liste de transactions
│   ├── PieChart.vue                       # Graphique camembert
│   ├── LineChart.vue                      # Graphique courbe
│   └── BudgetProgress.vue                # Barre de progression budget
├── Pages/
│   ├── Dashboard.vue                      # Page d'accueil
│   ├── Accounts/
│   │   ├── Index.vue
│   │   ├── Create.vue
│   │   ├── Edit.vue
│   │   └── Show.vue
│   └── Transactions/
│       ├── Index.vue                      # Avec filtres
│       ├── Create.vue
│       └── Edit.vue

database/
├── migrations/
│   ├── create_accounts_table.php
│   ├── create_categories_table.php
│   ├── create_transactions_table.php
│   ├── create_transfers_table.php
│   ├── create_budgets_table.php
│   ├── create_recurring_transactions_table.php
│   └── create_debts_table.php
└── seeders/
    └── CategorySeeder.php                 # 16 catégories par défaut
```

## 🎨 Catégories par défaut

### Revenus
- 💰 Salaire
- 💼 Freelance
- 📈 Investissement
- 🎁 Cadeau reçu
- 💵 Autre revenu

### Dépenses
- 🍔 Alimentation
- 🚗 Transport
- 🏠 Logement
- ⚕️ Santé
- 📚 Éducation
- 🎮 Loisirs
- 👕 Vêtements
- 📱 Téléphone/Internet
- 📺 Abonnements
- 🎁 Cadeau offert
- 💸 Autre dépense

## 🔐 Sécurité

- ✅ **Authentification** Laravel Breeze
- ✅ **Autorisation** avec Policies (vérifie user_id)
- ✅ **Validation** avec Form Requests
- ✅ **Protection CSRF** automatique
- ✅ **Hashing** des mots de passe avec Bcrypt
- ✅ **Sanitisation** des entrées utilisateur

## 🧪 Tests

```bash
# Exécuter les tests
php artisan test

# Avec couverture
php artisan test --coverage
```

## 📊 Points d'apprentissage clés

### Laravel
1. **Observers** - Automatisation de la logique métier
2. **Task Scheduling** - Tâches planifiées avec cron
3. **Form Requests** - Validation centralisée
4. **Policies** - Autorisation granulaire
5. **Eloquent Relations** - Relations complexes entre modèles
6. **Query Builder** - Requêtes optimisées avec agrégations

### Vue.js / Frontend
1. **Inertia.js** - SPA sans API REST
2. **Chart.js** - Visualisation de données
3. **Composition API** - Réactivité moderne de Vue 3
4. **Props & Emit** - Communication entre composants
5. **Tailwind CSS** - Styling utilitaire

## 🚧 Évolutions futures possibles

- [ ] Budget familial partagé (multi-utilisateurs)
- [ ] Notifications (email/push) pour budgets dépassés
- [ ] Export PDF avec graphiques
- [ ] API REST pour app mobile
- [ ] Import bancaire automatique (CSV, OFX)
- [ ] Rappels pour les dettes à échéance
- [ ] Objectifs d'épargne
- [ ] Analyse intelligente des dépenses (IA)

## 📝 Licence

MIT

## 👨‍💻 Auteur

Projet d'apprentissage Laravel + Vue.js

---

**Note** : Ce projet est une excellente base pour apprendre Laravel et Vue.js. Tous les concepts essentiels sont couverts : CRUD, relations, observers, validation, autorisation, graphiques, tâches planifiées, et bien plus !
