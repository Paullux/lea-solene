<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

session_start();

// --- CHARGEMENT DES SECRETS ---
if (file_exists(__DIR__ . '/secrets.php')) {
    require_once __DIR__ . '/secrets.php';
}

$apiKey = getenv('RESEND_API_KEY');
$audienceId = getenv('RESEND_AUDIENCE_ID');

if (!$apiKey || !$audienceId) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de configuration serveur'
    ]);
    exit;
}

// --- OPTIONS ---
$debug = false; // mettre true uniquement en local
$allowedOrigin = 'https://tonsite.com'; // remplace par ton vrai domaine
$rateLimitWindow = 300; // 5 min
$rateLimitMaxRequests = 10; // max 10 tentatives par IP sur 5 min
$minFormDelaySeconds = 3; // temps mini entre affichage du formulaire et soumission

// --- FONCTION DE REPONSE ---
function jsonResponse(bool $success, string $message = '', int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit;
}

// 1. Méthode POST obligatoire
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Méthode non autorisée', 405);
}

// 2. Vérification origine / referer
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$referer = $_SERVER['HTTP_REFERER'] ?? '';

$originOk = ($origin === $allowedOrigin);
$refererOk = (str_starts_with($referer, $allowedOrigin));

if (!$originOk && !$refererOk) {
    jsonResponse(false, 'Requête refusée', 403);
}

// 3. CSRF token
$csrfToken = $_POST['csrf_token'] ?? '';
if (
    empty($_SESSION['csrf_token']) ||
    empty($csrfToken) ||
    !hash_equals($_SESSION['csrf_token'], $csrfToken)
) {
    jsonResponse(false, 'Jeton invalide', 403);
}

// 4. Anti-bot honeypot
$website = trim((string)($_POST['website'] ?? ''));
if ($website !== '') {
    jsonResponse(true, 'OK', 200); // on fait semblant que ça marche
}

// 5. Anti-bot délai minimum
$formStartedAt = isset($_POST['form_started_at']) ? (int)$_POST['form_started_at'] : 0;
if ($formStartedAt <= 0 || (time() - $formStartedAt) < $minFormDelaySeconds) {
    jsonResponse(false, 'Soumission trop rapide', 429);
}

// 6. Rate limiting simple par IP
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$ipHash = hash('sha256', $ip);
$rateDir = sys_get_temp_dir() . '/subscribe_rate_limit';

if (!is_dir($rateDir)) {
    mkdir($rateDir, 0700, true);
}

$rateFile = $rateDir . '/' . $ipHash . '.json';
$now = time();

$rateData = [
    'start' => $now,
    'count' => 0
];

if (file_exists($rateFile)) {
    $raw = file_get_contents($rateFile);
    $decoded = json_decode($raw ?: '', true);

    if (is_array($decoded) && isset($decoded['start'], $decoded['count'])) {
        $rateData = $decoded;
    }
}

if (($now - (int)$rateData['start']) > $rateLimitWindow) {
    $rateData = [
        'start' => $now,
        'count' => 0
    ];
}

$rateData['count']++;

file_put_contents($rateFile, json_encode($rateData), LOCK_EX);

if ($rateData['count'] > $rateLimitMaxRequests) {
    jsonResponse(false, 'Trop de tentatives, réessaie plus tard', 429);
}

// 7. Validation email
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if (!$email) {
    jsonResponse(false, 'Email invalide', 400);
}

// 8. Appel API Resend
$url = 'https://api.resend.com/audiences/' . rawurlencode($audienceId) . '/contacts';

$data = [
    'email' => $email,
    'unsubscribed' => false,
    'first_name' => '',
    'last_name' => ''
];

$