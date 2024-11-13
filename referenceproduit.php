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

// Supprimer un produit
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM produits WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Produit supprimé avec succès!";
        $modalType = 'success';
    } else {
        $message = "Erreur : " . $conn->error;
        $modalType = 'error';
    }
}

// Afficher tous les produits
$sql = "SELECT * FROM produits";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Références des produits</title>
    <link rel="stylesheet" href="style.css"> <!-- Lien vers le fichier CSS externe -->
    <style>
        /* Style pour l'overlay et l'impression */
        .qr-code-print-section {
            display: none;
        }
    </style>
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

    <h1>Références des produits</h1>

    <!-- Message Modal -->
    <?php if (isset($message)) { ?>
        <div class="modal-message <?php echo $modalType; ?>">
            <p><?php echo $message; ?></p>
        </div>
    <?php } ?>

    <!-- Tableau des produits -->
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Quantité</th>
                <th>QR Code</th>
                <th>Modifier</th>
                <th>Supprimer</th>
                <th>Imprimer QR Code</th> <!-- Nouvelle colonne pour imprimer -->
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr data-id='" . $row['id'] . "'>";
                    echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantite']) . "</td>";
                    echo "<td><img class='qr-code-img' src='" . htmlspecialchars($row['qrcode']) . "' alt='QR Code' width='50'></td>";
                    echo "<td><a href='modifierproduit.php?id=" . $row['id'] . "'><button type='button'>Modifier</button></a></td>";
                    echo "<td><a href='referenceproduit.php?delete=" . $row['id'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce produit ?\")'>Supprimer</a></td>";

                    // Ajouter un bouton d'impression pour chaque ligne
                    echo "<td><button type='button' onclick='printQRCode(" . $row['id'] . ")'>Imprimer QR Code</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Aucun produit trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Section pour imprimer un seul QR code -->
    <div class="qr-code-print-section" id="qrCodePrintSection">
        <img id="qrCodeToPrint" src="" alt="QR Code" width="150">
    </div>

    <script>
        // Fonction pour imprimer le QR code d'une ligne spécifique
        function printQRCode(productId) {
            // Récupérer le QR code de l'id sélectionné
            var qrCodeImage = document.querySelector(tr[data-id="${productId}"] .qr-code-img);
            var qrCodeSrc = qrCodeImage ? qrCodeImage.src : '';

            if (qrCodeSrc) {
                // Afficher le QR code dans la section d'impression
                var printSection = document.getElementById('qrCodePrintSection');
                var printImage = document.getElementById('qrCodeToPrint');
                printImage.src = qrCodeSrc;

                // Ouvrir une nouvelle fenêtre pour l'impression
                var newWindow = window.open('', '', 'width=800,height=600');
                newWindow.document.write('<html><head><title>Impression QR Code</title></head><body>');
                newWindow.document.write('<img src="' + qrCodeSrc + '" alt="QR Code" width="150">');
                newWindow.document.write('</body></html>');
                newWindow.document.close();
                newWindow.print();
            } else {
                alert('QR code non trouvé pour ce produit.');
            }
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>