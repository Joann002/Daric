# Guide d'utilisation — Daric

**Daric** est votre gestionnaire de finances personnelles : comptes, transactions, budgets, dettes et plus, dans une interface claire en Ariary (Ar).

Ce guide couvre **toutes les fonctionnalités existantes**, puis propose des **évolutions futures**.

---

## Table des matières

1. [Premiers pas](#1-premiers-pas)
2. [Se repérer dans l'interface](#2-se-repérer-dans-linterface)
3. [Le tableau de bord](#3-le-tableau-de-bord)
4. [Comptes](#4-comptes)
5. [Catégories](#5-catégories)
6. [Transactions](#6-transactions)
7. [Transferts](#7-transferts)
8. [Budgets](#8-budgets)
9. [Transactions récurrentes](#9-transactions-récurrentes)
10. [Dettes & prêts](#10-dettes--prêts)
11. [Export des données](#11-export-des-données)
12. [Mon profil & sécurité](#12-mon-profil--sécurité)
13. [Mode sombre](#13-mode-sombre)
14. [Questions fréquentes](#14-questions-fréquentes)

---

## 1. Premiers pas

### Créer un compte utilisateur
1. Sur la page d'accueil, cliquez sur **Créer un compte gratuit**.
2. Renseignez **nom, e-mail, mot de passe** (et confirmation).
3. Vous êtes directement connecté et redirigé vers le tableau de bord.

### Se connecter / se déconnecter
- **Connexion** : e-mail + mot de passe. Option **Se souvenir de moi** pour rester connecté.
- **Déconnexion** : menu utilisateur (en haut à droite) → **Déconnexion**.

### Mot de passe oublié
Sur l'écran de connexion → **Mot de passe oublié ?** → saisissez votre e-mail pour recevoir un lien de réinitialisation.

### Ordre de mise en route recommandé
> 1. Créez vos **comptes** (espèces, banque, mobile money)
> 2. Vérifiez / ajoutez vos **catégories**
> 3. Saisissez vos **transactions**
> 4. Posez vos **budgets** et vos **récurrences**

---

## 2. Se repérer dans l'interface

- **Barre latérale (gauche)** : navigation entre les 8 sections — Tableau de bord, Comptes, Transactions, Transferts, Budgets, Récurrences, Dettes, Catégories.
- **Sur mobile** : la barre latérale devient un menu coulissant via l'icône ☰ en haut à gauche.
- **Barre du haut** : bouton de **bascule clair/sombre** et **menu utilisateur** (Profil, Déconnexion).
- **Montants** : tout est affiché en **Ariary (Ar)**.

---

## 3. Le tableau de bord

Vue d'ensemble de vos finances. En haut à droite, un **sélecteur de mois** recharge toutes les données pour la période choisie.

Il contient :
- **4 cartes de statistiques** avec mini-courbes (sparklines) de tendance sur 6 mois :
  - **Solde total** (somme de tous les comptes)
  - **Revenus du mois**
  - **Dépenses du mois**
  - **Balance du mois** (revenus − dépenses ; verte si positive, rouge si négative)
- **Mes comptes** : aperçu de chaque compte avec son solde et sa mini-courbe. Bouton **+ Nouveau compte**.
- **Dépenses par catégorie** : graphique en anneau (donut) du mois.
- **Évolution sur 6 mois** : courbe revenus vs dépenses.
- **Calendrier des transactions** : les opérations du mois positionnées par date ; un clic sur un événement ouvre le détail de la transaction.
- **Budgets du mois** : barres de progression par catégorie.
- **Dernières transactions** : les 10 plus récentes, avec lien **Voir toutes**.

---

## 4. Comptes

Un compte représente un endroit où se trouve votre argent.

| Champ | Détail |
|---|---|
| **Nom** | Ex. « Compte courant », « Orange Money » |
| **Type** | **Espèces**, **Banque**, **Mobile Money** |
| **Solde initial** | Montant de départ |
| **Devise** | Ariary (par défaut), Euro ou Dollar |

### Actions
- **Créer** : Comptes → **+ Nouveau compte**.
- **Voir** : page détaillée avec le solde actuel et les dernières transactions du compte.
- **Modifier** : nom, type, devise. ⚠️ Le **solde ne se modifie pas directement** — il évolue uniquement via les transactions et transferts.
- **Supprimer** : ⚠️ supprime aussi **toutes les transactions** liées à ce compte.

> 💡 Le solde de chaque compte est recalculé **automatiquement** à chaque transaction ou transfert.

---

## 5. Catégories

Les catégories classent vos opérations (Alimentation, Salaire, Transport…).

- **Type** : **Revenu** ou **Dépense**.
- **Couleur** : sélecteur de couleur (utilisée dans les graphiques et listes).
- **Catégories par défaut** : partagées et marquées « Par défaut » — utilisables par tous mais **non modifiables / non supprimables**.
- **Vos catégories** : créées par vous, modifiables et supprimables.

### Actions
Catégories → **+ Nouvelle catégorie** ; modification/suppression via les icônes sur chaque carte (uniquement pour vos catégories).

---

## 6. Transactions

Le cœur de l'application : chaque revenu ou dépense.

### Créer une transaction
1. Transactions → **+ Nouvelle transaction**.
2. Choisissez le **type** (Revenu / Dépense) — la liste des catégories s'adapte.
3. Sélectionnez le **compte**, la **catégorie**, le **montant**, la **date**, et une **description** optionnelle.
4. Validez : le **solde du compte est mis à jour** instantanément (revenu = +, dépense = −).

### Liste & filtres
La page Transactions propose des **filtres combinables** :
- par **compte**, par **catégorie**, par **type**, et une **recherche** sur la description.

La liste est **paginée** (20 par page) et les filtres sont conservés dans l'URL (partage / rafraîchissement).

### Modifier / supprimer
- **Modifier** : ajuste la transaction ; le solde est **recalculé** (l'ancien montant est annulé, le nouveau appliqué).
- **Supprimer** : annule son effet sur le solde.

---

## 7. Transferts

Déplacer de l'argent **d'un de vos comptes vers un autre** (ex. retrait banque → espèces).

1. Transferts → **+ Nouveau transfert**.
2. Choisissez **compte source**, **compte destination** (différents), **montant**, **date**, description.
3. À la validation : le compte source est **débité** et le compte destination **crédité**.

> Supprimer un transfert **restaure** les soldes des deux comptes. Un transfert n'est ni un revenu ni une dépense : il n'apparaît pas dans les statistiques de dépenses/revenus.

---

## 8. Budgets

Fixez un plafond de dépenses par **catégorie** et par **mois**.

- **Catégorie de dépense** + **Mois** + **Montant limite**.
- Un seul budget par couple **(catégorie, mois)** — les doublons sont bloqués.
- **Suivi visuel** de la progression (dépensé / limite) :
  - 🟢 vert : sous 80 %
  - 🟠 ambre : entre 80 % et 100 %
  - 🔴 rouge : dépassement
- Le « dépensé » additionne automatiquement vos transactions de **dépense** de la catégorie sur le mois.

Visibles sur la page Budgets **et** sur le tableau de bord.

---

## 9. Transactions récurrentes

Automatisez les opérations qui reviennent (salaire, loyer, abonnements).

| Champ | Détail |
|---|---|
| **Type / Compte / Catégorie / Montant** | comme une transaction |
| **Fréquence** | Quotidien, Hebdomadaire, Mensuel, Annuel |
| **Prochaine échéance** | date de la prochaine génération |
| **Active** | activez/mettez en pause sans supprimer |

> ⚙️ Chaque jour, le système génère automatiquement les transactions arrivées à échéance et reporte la prochaine date selon la fréquence. (Tâche planifiée quotidienne `transactions:process-recurring`.)

---

## 10. Dettes & prêts

Suivez l'argent que vous devez ou qu'on vous doit.

- **Personne** concernée.
- **Sens** : **Je dois** (dette) ou **On me doit** (prêt).
- **Montant** total + **déjà remboursé** → le **restant** et la **barre de progression** se calculent automatiquement.
- **Statut** : En attente, Partiellement réglé, Réglé.
- **Échéance** optionnelle.

---

## 11. Export des données

Depuis la page **Transactions** → bouton **Exporter** : télécharge un fichier **CSV** (séparateur `;`, compatible Excel) avec colonnes **Date, Compte, Catégorie, Type, Montant, Description**.

L'export respecte les **filtres de dates et de compte** actifs.

---

## 12. Mon profil & sécurité

Menu utilisateur → **Profil** :
- **Informations** : nom et e-mail.
- **Mot de passe** : changement sécurisé (mot de passe actuel requis).
- **Supprimer mon compte** : action **définitive** (confirmation par mot de passe).

---

## 13. Mode sombre

Bouton 🌙 / ☀️ dans la barre du haut. Votre choix est **mémorisé** sur l'appareil ; au premier usage, Daric suit le thème de votre système.

---

## 14. Questions fréquentes

**Pourquoi je ne peux pas modifier le solde d'un compte ?**
Le solde est toujours dérivé de vos opérations. Pour le corriger, créez une transaction (ou ajustez le solde initial à la création).

**Une catégorie « par défaut » refuse d'être modifiée.**
C'est normal : les catégories par défaut sont partagées. Créez votre propre catégorie si besoin.

**Mes données sont-elles isolées ?**
Oui : vous ne voyez et ne manipulez que **vos** comptes, transactions, budgets, etc.

**Quelle devise est utilisée ?**
L'**Ariary (Ar)** par défaut pour l'affichage.

---

# Propositions de nouvelles fonctionnalités

Classées par priorité / valeur. À discuter et planifier (chaque item mériterait sa propre conception).

## 🥇 Priorité haute (fort impact, effort raisonnable)

1. **Tableau de bord des dettes** — Totaux « Je dois » / « On me doit », solde net, et **enregistrement d'un remboursement** en un clic (qui met à jour `paid_amount` et le statut, voire crée la transaction associée).
2. **Filtres de dates sur les transactions** — Les bornes `start_date` / `end_date` existent déjà côté serveur et dans l'export ; il manque les champs dans l'interface (+ raccourcis « Ce mois », « 30 derniers jours »).
3. **Notifications / alertes de budget** — Avertir lorsqu'un budget atteint 80 % ou est dépassé (bannière dans l'app, puis e-mail).
4. **Recherche globale** — Barre de recherche dans la topbar (transactions, comptes, catégories, personnes).
5. **États vides plus actifs** — Sur un compte fraîchement créé, proposer directement « Ajouter une transaction ».

## 🥈 Priorité moyenne (vraie valeur, effort modéré)

6. **Rapports & analyses** — Page dédiée : comparaison mois/mois, top catégories, taux d'épargne, dépense moyenne par jour, export PDF.
7. **Objectifs d'épargne** — Définir un objectif (montant + échéance) et suivre la progression, avec contribution depuis un compte.
8. **Pièces jointes / reçus** — Joindre une photo de reçu à une transaction.
9. **Étiquettes (tags)** — En complément des catégories, pour des analyses transversales (ex. « vacances », « projet X »).
10. **Multi-devises réel** — Stocker un taux de change et convertir pour un solde total homogène (utile car EUR/USD sont déjà proposés).
11. **Transactions fractionnées** — Répartir une dépense sur plusieurs catégories (ex. un ticket de supermarché).
12. **Import CSV / relevé bancaire** — Importer des opérations en masse (complément naturel de l'export existant).

## 🥉 Confort & long terme

13. **Modèles de transactions** — Enregistrer des opérations fréquentes pour les ressaisir en un clic.
14. **PWA / mode hors-ligne** — Installation sur mobile, saisie hors connexion synchronisée ensuite.
15. **Comptes partagés / foyer** — Partager certains comptes/budgets entre plusieurs utilisateurs.
16. **Rappels d'échéances** — Notifications avant une dette à échéance ou une récurrence à venir.
17. **Personnalisation du tableau de bord** — Réorganiser / masquer les blocs.
18. **Sécurité renforcée** — Authentification à deux facteurs (2FA), verrouillage par code sur mobile.
19. **Catégorisation automatique** — Suggérer la catégorie d'après la description (règles, puis apprentissage).
20. **Vérification d'e-mail** — La base est présente (non activée) ; à activer pour fiabiliser les comptes et la réinitialisation de mot de passe.
