<?php
// unsubscribe.php - VERSION FINALE PROPRE

// Gestion Hybride (Local / Coolify)
if (file_exists(__DIR__ . '/secrets.php')) {
    require_once __DIR__ . '/secrets.php';
}

$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
$api_key = getenv('RESEND_API_KEY');
$audience_id = getenv('RESEND_AUDIENCE_ID');

$titre_ecran = "";
$message_ecran = "";

// Sécurité basique
if (!$api_key || !$audience_id) {
    die("Erreur de configuration technique.");
}

if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {

    // --- 1. On tente de supprimer le contact de l'audience ---
    $ch_del = curl_init();
    curl_setopt($ch_del, CURLOPT_URL, "https://api.resend.com/audiences/$audience_id/contacts/$email");
    curl_setopt($ch_del, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch_del, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_key
    ]);
    curl_setopt($ch_del, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch_del);
    $http_code = curl_getinfo($ch_del, CURLINFO_HTTP_CODE);
    curl_close($ch_del);

    // --- 2. On analyse le résultat ---
    if ($http_code == 200) {
        // SUCCÈS : Le contact existait et vient d'être supprimé
        $titre_ecran = "C'est noté...";
        $message_ecran = "Tu as bien été retiré(e) de ma liste. Un dernier mail de confirmation vient de partir.";

        // On envoie le mail d'adieu uniquement maintenant
        $ch_mail = curl_init('https://api.resend.com/emails');
        $data_mail = [
            'from' => 'Léa Solène <lea@lea-solene.fr>',
            'to' => $email,
            'subject' => 'Au revoir... 🍂',
            'html' => '<div style="font-family: Georgia, serif; color: #1E2D08; padding: 20px;">
                        <p>Bonjour,</p>
                        <p>Je te confirme que tu as été retiré(e) de ma liste de diffusion.</p>
                        <p>Ta lumière manquera à ce petit cercle, mais la porte reste toujours entrouverte.</p>
                        <p><em>Léa Solène</em></p></div>'
        ];
        curl_setopt($ch_mail, CURLOPT_POST, 1);
        curl_setopt($ch_mail, CURLOPT_POSTFIELDS, json_encode($data_mail));
        curl_setopt($ch_mail, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $api_key, 'Content-Type: application/json']);
        curl_setopt($ch_mail, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch_mail);
        curl_close($ch_mail);

    } elseif ($http_code == 404) {
        // DÉJÀ FAIT : Le contact n'était plus dans la liste
        $titre_ecran = "Déjà fait";
        $message_ecran = "Ton adresse ne figurait plus dans la liste de diffusion. Aucune action n'est nécessaire.";
    
    } else {
        // ERREUR
        $titre_ecran = "Oups...";
        $message_ecran = "Une erreur technique est survenue. Merci de réessayer plus tard.";
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