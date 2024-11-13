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
            $modalType = 'error';
        }
    } else {
        $message = "Erreur lors de la création du produit : " . $conn->error;
        $modalType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des stocks</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="gestiondestocks.php">Gestion des stocks</a></li>
            <li><a href="referenceproduit.php">Référence Produit</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </div>

    <h1>Création d'un nouveau produit</h1>


    <?php if (isset($message)) { ?>
        <div class="modal-message <?php echo $modalType; ?>" id="modalMessage">
            <p><?php echo $message; ?></p>
        </div>
    <?php } ?>

    <form method="POST" action="gestiondestocks.php">
        <label for="nom">Nom du produit :</label>
        <input type="text" name="nom" id="nom" required>

        <label for="quantite">Quantité :</label>
        <input type="number" name="quantite" id="quantite" required>

        <button type="submit" name="create_product">Créer le produit</button>
    </form>

    <script>

        function closeModal() {
            var modal = document.getElementById('modalMessage');
            modal.style.display = 'none';
        }

        // Fonction pour ouvrir le modal
        window.onload = function() {
            if (document.getElementById('modalMessage')) {
                document.getElementById('modalMessage').style.display = 'flex';
            }
        };
    </script>
</body>
</html>

<?php
$conn->close();
?>
