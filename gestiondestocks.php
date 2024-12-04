<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "InventQR";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if (isset($_POST['create_product'])) {
    require 'lib/qrlib.php'; // Assurez-vous que ce fichier est inclus correctement

    $nom = $conn->real_escape_string($_POST['nom']);
    $quantite = (int) $_POST['quantite'];

    // Insertion dans la base de données sans QR code
    $sql = "INSERT INTO produits (nom, quantite, qrcode) VALUES ('$nom', $quantite, '')";
    if ($conn->query($sql) === TRUE) {
        $productId = $conn->insert_id;  // Récupérer l'ID du produit inséré

        // Générer le QR Code avec l'ID du produit
        $qrData = $productId;  // Utiliser l'ID comme données du QR code
        $qrFile = 'qr_codes/' . $productId . '.png'; // Nom du fichier basé sur l'ID
        
        if (!is_dir('qr_codes')) {
            mkdir('qr_codes', 0777, true); // Créer le dossier si nécessaire
        }

        // Générer et sauvegarder le QR code
        QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 10);

        // Mettre à jour le produit avec le chemin du QR code généré
        $updateSql = "UPDATE produits SET qrcode = '$qrFile' WHERE id = $productId";
        if ($conn->query($updateSql) === TRUE) {
            $message = "Produit créé avec succès et QR Code généré !";
            $modalType = 'success';
        } else {
            $message = "Erreur lors de la mise à jour du QR Code : " . $conn->error;
            $modalType = 'danger';
        }
    } else {
        $message = "Erreur lors de la création du produit : " . $conn->error;
        $modalType = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des stocks</title>
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
                        <a class="nav-link" href="menu.php">Menu</a>
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
        <h1>Création d'un nouveau produit</h1>

        <!-- Message de succès ou d'erreur -->
        <?php if (isset($message)) { ?>
            <div class="alert alert-<?php echo $modalType; ?> mt-3" role="alert">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <!-- Formulaire de création du produit -->
        <form method="POST" action="gestiondestocks.php" class="mt-4">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du produit</label>
                <input type="text" name="nom" id="nom" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="quantite" class="form-label">Quantité</label>
                <input type="number" name="quantite" id="quantite" class="form-control" required>
            </div>

            <button type="submit" name="create_product" class="btn btn-primary">Créer le produit</button>
        </form>

        <br>
        <a href="referenceproduit.php" class="btn btn-secondary">Retour à la liste des produits</a>
    </div>

    <!-- Lien vers le JS de Bootstrap 5 depuis un CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // Fonction pour ouvrir et fermer le modal
        window.onload = function() {
            var modalMessage = document.getElementById('modalMessage');
            if (modalMessage) {
                modalMessage.style.display = 'flex';
            }
        };
    </script>
</body>

</html>

<?php
$conn->close();
?>
