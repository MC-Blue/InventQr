<?php
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "InventQR";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

require 'vendor/autoload.php'; // Chargez Composer autoloader pour Endroid QR Code
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelL;

if (isset($_POST['create_product'])) {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $quantite = (int) $_POST['quantite'];
    
    // Génération du QR Code
    $qrData = $nom . ' - Quantité: ' . $quantite;
    $qrCode = new QrCode($qrData);
    $qrCode->setEncoding(Encoding::UTF_8)
           ->setErrorCorrectionLevel(new ErrorCorrectionLevelL())
           ->setSize(10)
           ->setMargin(4);
    
    $qrFile = 'qr_codes/' . md5($qrData) . '.png';
    $writer = new PngWriter();
    $writer->writeFile($qrCode, $qrFile);
    
    // Insertion dans la base de données
    $sql = "INSERT INTO produits (nom, quantite, qrcode) VALUES ('$nom', $quantite, '$qrFile')";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='modal' style='display: block;'>Produit ajouté avec succès!</div>";
    } else {
        echo "<p>Erreur : " . $conn->error . "</p>";
    }
}

// Récupération des produits depuis la base de données
$sql = "SELECT * FROM produits";
$result = $conn->query($sql);
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

    <div class="container">
        <h1>Gestion des Stocks</h1>

        <!-- Formulaire pour ajouter un produit -->
        <form action="gestiondestocks.php" method="POST">
            <label for="nom">Nom du Produit :</label>
            <input type="text" id="nom" name="nom" required>
            
            <label for="quantite">Quantité :</label>
            <input type="number" id="quantite" name="quantite" min="1" required>
            
            <button type="submit" name="create_product">Créer le Produit</button>
        </form>

        <!-- Tableau des produits -->
        <table>
            <thead>
                <tr>
                    <th>Nom Produit</th>
                    <th>Quantité</th>
                    <th>QR Code</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantite']) . "</td>";
                        echo "<td><img src='" . htmlspecialchars($row['qrcode']) . "' alt='QR Code' width='50'></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Aucun produit trouvé</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Script pour afficher le modal
        var modal = document.querySelector('.modal');
        if (modal) {
            modal.style.display = 'block';
            setTimeout(function() {
                modal.style.display = 'none';
            }, 2000);
        }
    </script>
</body>
</html>

<?php
// Fermeture de la connexion
$conn->close();
?>
