#!/bin/bash

# Script de démarrage rapide pour Daric
# Ce script configure et lance l'application

echo "🚀 Démarrage de Daric - Gestionnaire de Finances"
echo "================================================="
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis la racine du projet"
    exit 1
fi

# Vérifier les dépendances
echo "📦 Vérification des dépendances..."

if ! command -v php &> /dev/null; then
    echo "❌ PHP n'est pas installé"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    echo "❌ Composer n'est pas installé"
    exit 1
fi

if ! command -v node &> /dev/null; then
    echo "❌ Node.js n'est pas installé"
    exit 1
fi

if ! command -v npm &> /dev/null; then
    echo "❌ NPM n'est pas installé"
    exit 1
fi

echo "✅ Toutes les dépendances sont installées"
echo ""

# Installation
if [ ! -d "vendor" ]; then
    echo "📥 Installation des dépendances PHP..."
    composer install --no-interaction
    echo "✅ Dépendances PHP installées"
    echo ""
fi

if [ ! -d "node_modules" ]; then
    echo "📥 Installation des dépendances JavaScript..."
    npm install
    echo "✅ Dépendances JavaScript installées"
    echo ""
fi

# Configuration
if [ ! -f ".env" ]; then
    echo "⚙️ Configuration de l'environnement..."
    cp .env.example .env
    php artisan key:generate
    echo "✅ Environnement configuré"
    echo ""
fi

# Base de données
if [ ! -f "database/database.sqlite" ]; then
    echo "🗄️ Création de la base de données..."
    touch database/database.sqlite
    php artisan migrate:fresh --seed --force
    echo "✅ Base de données créée et initialisée"
    echo ""
else
    echo "✅ Base de données déjà existante"
    echo ""
fi

# Compilation des assets
echo "🎨 Compilation des assets..."
npm run build
echo "✅ Assets compilés"
echo ""

# Informations
echo "================================================="
echo "✅ Installation terminée avec succès !"
echo "================================================="
echo ""
echo "📝 Compte de test:"
echo "   Email: test@example.com"
echo "   Mot de passe: password"
echo ""
echo "🚀 Pour démarrer l'application:"
echo "   php artisan serve"
echo ""
echo "   Puis ouvrir: http://localhost:8000"
echo ""
echo "📚 Documentation:"
echo "   - README.md : Vue d'ensemble et installation"
echo "   - TECHNICAL_GUIDE.md : Guide technique détaillé"
echo "   - CHANGELOG.md : Liste des fonctionnalités"
echo ""
echo "🔁 Pour générer les transactions récurrentes:"
echo "   php artisan transactions:process-recurring"
echo ""
echo "================================================="
