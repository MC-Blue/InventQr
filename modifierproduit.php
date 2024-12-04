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

// Récupérer les informations du produit à modifier
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM produits WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nom = $row['nom'];
        $quantite = $row['quantite'];
    } else {
        die("Produit introuvable.");
    }
}

// Mettre à jour les informations du produit
if (isset($_POST['update_product'])) {
    $nom = $_POST['nom'];
    $quantite = $_POST['quantite'];
    $sql = "UPDATE produits SET nom = '$nom', quantite = $quantite WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $message = "Produit mis à jour avec succès!";
        $modalType = 'success';
    } else {
        $message = "Erreur : " . $conn->error;
        $modalType = 'danger';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Produit</title>
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

    <!-- Contenu principal -->
    <div class="container mt-4">
        <h1>Modifier le produit</h1>

        <!-- Message de succès ou d'erreur -->
        <?php if (isset($message)) { ?>
            <div class="alert alert-<?php echo $modalType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <!-- Formulaire de modification du produit -->
        <form method="POST" action="modifierproduit.php?id=<?php echo $id; ?>" class="mt-4">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du produit</label>
                <input type="text" name="nom" id="nom" class="form-control" value="<?php echo htmlspecialchars($nom); ?>"
                    required>
            </div>

            <div class="mb-3">
                <label for="quantite" class="form-label">Quantité</label>
                <input type="number" name="quantite" id="quantite" class="form-control" value="<?php echo $quantite; ?>"
                    required>
            </div>

            <button type="submit" name="update_product" class="btn btn-primary">Mettre à jour</button>
        </form>

        <br>
        <a href="referenceproduit.php" class="btn btn-secondary">Retour à la liste des produits</a>
    </div>

    <!-- Lien vers le JS de Bootstrap 5 depuis un CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>
