<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <!-- Lien vers le CSS de Bootstrap 5 depuis un CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Barre de navigation avec Bootstrap 5 -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">InventQR</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="menu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gestiondestocks.php">Gestion des stocks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="referenceproduit.php">Référence Produit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Page Menu</h1>
        <p>Bienvenue sur la page d'accueil du système de gestion de produits. Vous pouvez accéder aux différentes sections ci-dessous.</p>

        <div class="row mt-5">
            <!-- Bouton vers la page Gestion des stocks -->
            <div class="col-md-4 mb-3">
                <a href="gestiondestocks.php" class="btn btn-primary w-100">
                    <h4>Gestion des stocks</h4>
                    <p>Gérer l'inventaire des produits, ajouter, modifier et supprimer des articles.</p>
                </a>
            </div>
            <!-- Bouton vers la page Référence Produit -->
            <div class="col-md-4 mb-3">
                <a href="referenceproduit.php" class="btn btn-success w-100">
                    <h4>Référence Produit</h4>
                    <p>Visualiser les références des produits avec leurs QR codes et gérer les informations.</p>
                </a>
            </div>
            <!-- Bouton vers une page Contact (actuellement non fonctionnelle) -->
            <div class="col-md-4 mb-3">
                <a href="#" class="btn btn-info w-100">
                    <h4>Contact</h4>
                    <p>Nous contacter pour toute question ou support.</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Lien vers le JS de Bootstrap 5 depuis un CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
