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
    } else {
        $message = "Erreur : " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Produit</title>
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

    <h1>Modifier le produit</h1>

    <?php if (isset($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>

    <form method="POST" action="modifierproduit.php?id=<?php echo $id; ?>">
        <label for="nom">Nom du produit :</label>
        <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($nom); ?>" required><br><br>

        <label for="quantite">Quantité :</label>
        <input type="number" name="quantite" id="quantite" value="<?php echo $quantite; ?>" required><br><br>

        <button type="submit" name="update_product">Mettre à jour</button>
    </form>

    <br>
    <a href="referenceproduit.php">Retour à la liste des produits</a>

</body>
</html>

<?php
$conn->close();
?>
