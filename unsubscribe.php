<?php
// unsubscribe.php

// --- CHARGEMENT INTELLIGENT DES SECRETS ---
// Permet de dev en local (via fichier) et de prod sur Coolify (via env vars)
if (file_exists(__DIR__ . '/secrets.php')) {
    require_once __DIR__ . '/secrets.php';
}

$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
$status_message = "Ta demande a bien été prise en compte.";

// Récupération des variables d'environnement (Coolify)
$api_key = getenv('RESEND_API_KEY'); 
$audience_id = getenv('RESEND_AUDIENCE_ID'); 

if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    
    // --- 1. SUPPRESSION DU CONTACT DANS RESEND ---
    if ($audience_id) {
        $ch_del = curl_init();
        curl_setopt($ch_del, CURLOPT_URL, "https://api.resend.com/audiences/$audience_id/contacts/$email");
        curl_setopt($ch_del, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch_del, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $api_key
        ]);
        curl_setopt($ch_del, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch_del);
        // curl_close supprimé : PHP 8 gère la fermeture tout seul
    }

    // --- 2. ENVOI DU MAIL D'AU REVOIR ---
    $url = 'https://api.resend.com/emails';
    $data = [
        'from' => 'Léa Solène <lea@lea-solene.fr>',
        'to' => $email,
        'subject' => 'Au revoir... 🍂',
        'html' => '
            <div style="font-family: Georgia, serif; color: #1E2D08; padding: 20px;">
                <p>Bonjour,</p>
                <p>Tu as bien été retiré(e) de ma liste de diffusion.</p>
                <p>Ta lumière manquera à ce petit cercle, mais la porte reste toujours entrouverte si tu souhaites revenir écouter le vent.</p>
                <p>Prends soin de toi.</p>
                <p><em>Léa Solène</em></p>
            </div>
        '
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    curl_exec($ch);
    // curl_close supprimé ici aussi

} else {
    $status_message = "Lien invalide ou expiré.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Désinscription - Léa Solène</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        body { font-family: 'Georgia', serif; background-color: #f9f9f7; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; color: #1E2D08; }
        .card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; max-width: 400px; border: 1px solid #e0e0d0; }
        h1 { font-style: italic; color: #4a5d23; margin-bottom: 20px; }
        .btn { display: inline-block; margin-top: 20px; text-decoration: none; color: #8a8a7a; border-bottom: 1px solid #8a8a7a; }
    </style>
</head>
<body>
    <div class="card">
        <h1>C'est noté...</h1>
        <p><?php echo $status_message; ?></p>
        <p>Un dernier email de confirmation a été envoyé à <?php echo htmlspecialchars($email); ?>.</p>
        <a href="/" class="btn">Retour à l'accueil</a>
    </div>
</body>
</html>