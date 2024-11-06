<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner un QR Code</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
</head>
<body>

<div class="container">
    <h1>Scanner un QR Code</h1>
    
    <!-- Div pour afficher le scanner QR -->
    <div id="reader" style="width: 300px; height: 300px;"></div>
    <p id="result"></p>
</div>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Affiche le texte décodé du QR code
        document.getElementById("result").innerText = `QR Code détecté : ${decodedText}`;
        html5QrcodeScanner.clear(); // Arrête le scanner après avoir scanné un QR code
    }

    function onScanError(errorMessage) {
        // Gérer les erreurs de scan si nécessaire (facultatif)
    }

    // Initialise le scanner avec une demande d'autorisation pour la caméra
    let html5QrcodeScanner = new Html5Qrcode("reader");
    html5QrcodeScanner.start(
        { facingMode: "environment" }, // Utilise la caméra arrière
        {
            fps: 10, // Nombre d'images par seconde pour scanner
            qrbox: { width: 250, height: 250 } // Taille de la zone de scan
        },
        onScanSuccess,
        onScanError
    ).catch(err => {
        console.error(`Erreur d'initialisation du scanner: ${err}`);
    });
</script>

</body>
</html>
