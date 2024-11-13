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
                    echo "<td><a href='modifierproduit.php?id=" . $row['id'] . "'><button type='button'>Modifier</button></a></td>";
                    echo "<td><a href='referenceproduit.php?delete=" . $row['id'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce produit ?\")'>Supprimer</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Aucun produit trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        // Fonction de confirmation avant la suppression d'un produit
        function confirmDelete() {
            return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
