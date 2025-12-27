<?php
// unsubscribe.php

// --- CHARGEMENT HYBRIDE (Local vs Prod) ---
if (file_exists(__DIR__ . '/secrets.php')) {
    require_once __DIR__ . '/secrets.php';
}

$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
$api_key = getenv('RESEND_API_KEY');
$audience_id = getenv('RESEND_AUDIENCE_ID');

$message_ecran = "";
$titre_ecran = "";

// Vérification de sécurité avant tout
if (!$api_key || !$audience_id) {
    die("Erreur de configuration serveur : Clé API ou Audience ID manquant.");
}

if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {

    // --- ÉTAPE 1 : TENTATIVE DE SUPPRESSION ---
    $ch_del = curl_init();
    curl_setopt($ch_del, CURLOPT_URL, "https://api.resend.com/audiences/$audience_id/contacts/$email");
    curl_setopt($ch_del, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch_del, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_key
    ]);
    curl_setopt($ch_del, CURLOPT_RETURNTRANSFER, true);
    
    $response_body = curl_exec($ch_del);
    $http_code = curl_getinfo($ch_del, CURLINFO_HTTP_CODE);

    // --- ÉTAPE 2 : ANALYSE DU RÉSULTAT ---
    
    if ($http_code == 200) {
        // CAS 1 : SUCCÈS TOTAL -> On envoie le mail d'adieu
        $titre_ecran = "C'est noté...";
        $message_ecran = "Tu as bien été retiré(e) de ma liste. Un dernier mail de confirmation vient de partir.";

        // Envoi du mail d'adieu (uniquement maintenant !)
        $ch_mail = curl_init('https://api.resend.com/emails');
        $data_mail = [
            'from' => 'Léa Solène <lea@lea-solene.fr>',
            'to' => $email,
            'subject' => 'Au revoir... 🍂',
            'html' => '<div style="font-family: Georgia, serif; color: #1E2D08; padding: 20px;">
                        <p>Bonjour,</p>
                        <p>Je te confirme que tu as été retiré(e) de ma liste de diffusion.</p>
                        <p>La porte reste toujours entrouverte. Prends soin de toi.</p>
                        <p><em>Léa Solène</em></p></div>'
        ];
        curl_setopt($ch_mail, CURLOPT_POST, 1);
        curl_setopt($ch_mail, CURLOPT_POSTFIELDS, json_encode($data_mail));
        curl_setopt($ch_mail, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $api_key, 'Content-Type: application/json']);
        curl_setopt($ch_mail, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch_mail); // On tire et on oublie

    } elseif ($http_code == 404) {
        // CAS 2 : DÉJÀ PARTI -> Pas de mail, juste un message
        $titre_ecran = "Déjà fait";
        $message_ecran = "Tu ne faisais déjà plus partie de la liste. Aucune action nécessaire.";
    
    } else {
        // CAS 3 : ERREUR TECHNIQUE
        $titre_ecran = "Oups...";
        $message_ecran = "Une erreur est survenue lors de la désinscription (Code Resend: $http_code).";
        // Décommenter la ligne suivante pour voir l'erreur exacte si besoin :
        // $message_ecran .= "<br>Détail : " . htmlspecialchars($response_body);
    }

} else {
    $titre_ecran = "Lien invalide";
    $message_ecran = "Ce lien ne semble pas fonctionner.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>Désinscription - Léa Solène</title>
    <style>
        body { font-family: 'Georgia', serif; background-color: #f9f9f7; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; color: #1E2D08; }
        .card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; max-width: 400px; border: 1px solid #e0e0d0; }
        h1 { font-style: italic; color: #4a5d23; margin-bottom: 20px; }
        .btn { display: inline-block; margin-top: 20px; text-decoration: none; color: #8a8a7a; border-bottom: 1px solid #8a8a7a; }
    </style>
</head>
<body>
    <div class="card">
        <h1><?php echo $titre_ecran; ?></h1>
        <p><?php echo $message_ecran; ?></p>
        <a href="/" class="btn">Retour à l'accueil</a>
    </div>
</body>
</html>