<?php
header('Content-Type: application/json');

// --- CHARGEMENT DES SECRETS ---
// Si on est en local et que le fichier existe, on le charge
if (file_exists(__DIR__ . '/secrets.php')) {
    require_once __DIR__ . '/secrets.php';
}

// Récupération sécurisée des clés (Local ou Prod)
$apiKey = getenv('RESEND_API_KEY');
$audienceId = getenv('RESEND_AUDIENCE_ID');

if (!$apiKey || !$audienceId) {
    // Sécurité : si les clés manquent, on arrête tout
    echo json_encode(['success' => false, 'message' => 'Erreur de configuration serveur']);
    exit;
}
// ------------------------------

// 1. Sécurité de base
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// 2. Récupération et nettoyage de l'email
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email invalide']);
    exit;
}

// 3. Configuration API Resend
$url = 'https://api.resend.com/audiences/' . $audienceId . '/contacts';

// Données à envoyer
$data = [
    'email' => $email,
    'unsubscribed' => false,
    'first_name' => '', // Optionnel
    'last_name' => ''   // Optionnel
];

// 4. Appel cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// --- CORRECTIF SPECIAL WINDOWS LOCAL ---
// Désactive la vérification du certificat SSL (souvent absent sur WAMP/PHP local)
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // <-- À ENLEVER OU COMMENTER POUR LA PROD
// ---------------------------------------

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch); // On capture l'erreur technique cURL s'il y en a une

// 5. Gestion de la réponse
if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['success' => true]);
} else {
    // MODE DEBUG : On affiche la vraie erreur au lieu de "Erreur technique"
    // Si c'est Resend qui bloque, $response contiendra le message (ex: "Audience not found")
    // Si c'est ton PC, $curlError contiendra le message
    $debugMessage = "Erreur HTTP: $httpCode | cURL: $curlError | Resend: $response";
    
    echo json_encode([
        'success' => false, 
        'message' => $debugMessage
    ]);
}
?>