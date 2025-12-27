<?php
// unsubscribe.php - VERSION DE DIAGNOSTIC
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Chargement secrets locaux
if (file_exists(__DIR__ . '/secrets.php')) {
    require_once __DIR__ . '/secrets.php';
}

$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
$api_key = getenv('RESEND_API_KEY');
$audience_id = getenv('RESEND_AUDIENCE_ID');

echo "<h1>Mode Diagnostic</h1>";
echo "<p><strong>Email cible :</strong> " . htmlspecialchars($email) . "</p>";

// VÉRIFICATION 1 : LES VARIABLES
if (empty($api_key)) {
    die("<h2 style='color:red'>ERREUR : Clé API non trouvée ! (Vérifie Coolify)</h2>");
}
if (empty($audience_id)) {
    die("<h2 style='color:red'>ERREUR : ID Audience non trouvé ! (Vérifie Coolify)</h2>");
}
echo "<p style='color:green'>Variables d'environnement OK.</p>";
echo "<p>Audience ID utilisé : " . htmlspecialchars(substr($audience_id, 0, 5)) . "...</p>";

if ($email) {
    // TENTATIVE DE SUPPRESSION
    $url = "https://api.resend.com/audiences/$audience_id/contacts/$email";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_key
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "<h3>Résultat de la demande de suppression :</h3>";
    echo "Code HTTP : <strong>$http_code</strong> (200 = Succès, 404 = Introuvable, 400/500 = Erreur)<br>";
    echo "Réponse brute de Resend : <pre>" . htmlspecialchars($response) . "</pre>";

    if ($http_code == 200) {
        echo "<h2 style='color:green'>SUCCÈS ! Le contact a été supprimé.</h2>";
        // C'est ICI qu'on enverrait normalement le mail
    } else {
        echo "<h2 style='color:red'>ÉCHEC. Le contact n'est pas supprimé.</h2>";
    }

} else {
    echo "Pas d'email fourni dans l'URL.";
}
?>