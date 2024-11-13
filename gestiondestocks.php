<?php
// Connexion à la base de données
$servername = "localhost";  // Nom du serveur
$username = "root";         // Nom d'utilisateur
$password = "";             // Mot de passe
$dbname = "InventQR";       // Nom de la base de données

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if (isset($_POST['create_product'])) {
    require 'lib\phpqrcode.php'; // Assurez-vous que cette bibliothèque est incluse et fonctionnelle

    $nom = $conn->real_escape_string($_POST['nom']);
    $quantite = (int) $_POST['quantite'];

    // Générer le QR code
    $qrData = $nom;
    $qrFile = 'qr_codes/' . md5($qrData) . '.png';
    if (!is_dir('qr_codes')) {
        mkdir('qr_codes', 0777, true);
    }

    QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 10);

    // Insertion dans la base de données
    $sql = "INSERT INTO produits (nom, quantite, qrcode) VALUES ('$nom', $quantite, '$qrFile')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Produit créé avec succès !</p>";
    } else {
        echo "<p>Erreur : " . $conn->error . "</p>";
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
    <form method="POST" action="gestiondestocks.php">
        <label for="nom">Nom du produit :</label>
        <input type="text" name="nom" id="nom" required>

        <label for="quantite">Quantité :</label>
        <input type="number" name="quantite" id="quantite" required>

        <button type="submit" name="create_product">Créer le produit</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
