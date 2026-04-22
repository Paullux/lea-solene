<?php
declare(strict_types=1);

// unsubscribe.php - version durcie

if (file_exists(__DIR__ . '/secrets.php')) {
    require_once __DIR__ . '/secrets.php';
}

$apiKey = getenv('RESEND_API_KEY');
$audienceId = getenv('RESEND_AUDIENCE_ID');
$unsubscribeSecret = getenv('UNSUBSCRIBE_SECRET');

$titre_ecran = 'Lien invalide';
$message_ecran = "Ce lien ne semble pas fonctionner.";

if (!$apiKey || !$audienceId || !$unsubscribeSecret) {
    http_response_code(500);
    die('Erreur de configuration technique.');
}

$emailRaw = (string)($_GET['email'] ?? '');
$token = (string)($_GET['token'] ?? '');

$email = filter_var($emailRaw, FILTER_VALIDATE_EMAIL);

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

if ($email && $token !== '') {
    $normalizedEmail = strtolower(trim($email));
    $expectedToken = hash_hmac('sha256', $normalizedEmail, $unsubscribeSecret);

    if (hash_equals($expectedToken, $token)) {
        $encodedEmail = rawurlencode($normalizedEmail);

        $chDel = curl_init();
        curl_setopt_array($chDel, [
            CURLOPT_URL => "https://api.resend.com/audiences/{$audienceId}/contacts/{$encodedEmail}",
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Accept: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
        ]);

        $response = curl_exec($chDel);
        $httpCode = (int)curl_getinfo($chDel, CURLINFO_HTTP_CODE);
        $curlError = curl_error($chDel);
        curl_close($chDel);

        if ($httpCode === 200) {
            $titre_ecran = "C'est noté...";
            $message_ecran = "Tu as bien été retiré(e) de la liste de diffusion.";

            // Facultatif : je te conseille de NE PAS envoyer de mail d'adieu.
            // Si tu tiens à le faire, garde-le très sobre.

        } elseif ($httpCode === 404) {
            // Réponse neutre pour éviter d'exposer trop d'infos
            $titre_ecran = "C'est noté...";
            $message_ecran = "Si cette adresse était encore inscrite, elle a bien été retirée.";

        } else {
            error_log('unsubscribe error | HTTP: ' . $httpCode . ' | cURL: ' . $curlError . ' | Response: ' . $response);
            $titre_ecran = 'Oups...';
            $message_ecran = "Une erreur technique est survenue. Merci de réessayer plus tard.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Désinscription - Léa Solène</title>
    <style>
        body {
            font-family: Georgia, serif;
            background-color: #f9f9f7;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #1E2D08;
            padding: 20px;
            box-sizing: border-box;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
            max-width: 420px;
            border: 1px solid #e0e0d0;
        }
        h1 {
            font-style: italic;
            color: #4a5d23;
            margin-bottom: 20px;
        }
        p {
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #8a8a7a;
            border-bottom: 1px solid #8a8a7a;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1><?= h($titre_ecran) ?></h1>
        <p><?= h($message_ecran) ?></p>
        <a href="/" class="btn">Retour à l'accueil</a>
    </div>
</body>
</html>