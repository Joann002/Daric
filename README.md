# Daric - Gestionnaire de Finances Personnelles

Application web moderne de gestion de finances personnelles.

## ✨ Fonctionnalités

- 💳 **Gestion multi-comptes** (espèces, banque, mobile money)
- 💰 **Transactions** avec catégories personnalisables
- 📊 **Tableau de bord** avec graphiques et statistiques
- 🎯 **Budgets mensuels** avec suivi en temps réel
- 🔄 **Transferts** entre comptes
- 📈 **Analyses** et rapports détaillés
- 💾 **Export** de données
- 🌍 **Multi-devises**

## 🛠️ Technologies

- **Backend**: Laravel
- **Frontend**: Vue.js + Inertia.js
- **Styling**: Tailwind CSS
- **Graphiques**: Chart.js
- **Base de données**: SQLite / MySQL / PostgreSQL

## 📦 Installation

### Prérequis
- PHP 8.2+
- Composer
- Node.js & NPM
- Base de données (SQLite, MySQL ou PostgreSQL)

### Installation

```bash
# Cloner le projet
git clone <repository-url>
cd daric

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate --seed

# Compiler les assets
npm run build

# Lancer le serveur
php artisan serve
```

Accédez à l'application sur **http://localhost:8000**

## � Captures d'écran

*À venir...*

## 🎯 Utilisation

### Créer un compte
1. Connectez-vous à l'application
2. Accédez à "Comptes"
3. Créez vos différents comptes (espèces, banque, etc.)

### Ajouter des transactions
1. Cliquez sur "Transactions"
2. Créez une nouvelle transaction
3. Le solde se met à jour automatiquement

### Suivre votre budget
1. Définissez des budgets mensuels par catégorie
2. Suivez votre progression en temps réel
3. Recevez des alertes visuelles

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou une pull request.

## 📝 Licence

Ce projet est sous licence MIT.

## 👨‍💻 Auteur

Joann Michel
