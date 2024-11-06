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

// Modifier la quantité d'un produit
if (isset($_POST['update_quantity'])) {
    $id = $_POST['id'];
    $quantite = $_POST['quantite'];
    $sql = "UPDATE produits SET quantite = $quantite WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Quantité mise à jour avec succès!";
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

    <!-- Tableau des produits -->
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Quantité</th>
                <th>QR Code</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                    echo "<td>
                            <form method='POST' action='referenceproduit.php' onsubmit='return confirmUpdate()'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <input type='number' name='quantite' value='" . $row['quantite'] . "' required>
                                <button type='submit' name='update_quantity'>Mettre à jour</button>
                            </form>
                          </td>";
                    echo "<td><img src='" . htmlspecialchars($row['qrcode']) . "' alt='QR Code' width='50'></td>";
                    echo "<td><a href='referenceproduit.php?delete=" . $row['id'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce produit ?\")'>Supprimer</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Aucun produit trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Modale -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>

    <script>
        // Afficher la fenêtre modale si une action est effectuée
        <?php if (isset($message)) { ?>
            var modal = document.getElementById("myModal");
            var modalMessage = document.getElementById("modalMessage");
            var closeBtn = document.getElementsByClassName("close")[0];

            // Afficher le message dans la modale
            modalMessage.textContent = "<?php echo $message; ?>";
            modal.style.display = "block";

            // Fermer la modale quand on clique sur la croix
            closeBtn.onclick = function() {
                modal.style.display = "none";
            }

            // Fermer la modale si l'utilisateur clique en dehors de la fenêtre modale
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        <?php } ?>

        // Fonction de confirmation avant la mise à jour de la quantité
        function confirmUpdate() {
            return confirm('Êtes-vous sûr de vouloir modifier la quantité de ce produit ?');
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
