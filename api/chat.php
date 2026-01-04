<?php
/**
 * AI Consultant API Endpoint
 */

require_once __DIR__ . '/../includes/config.php';

// JSON Response helper
function send_json($data, $code = 200)
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Check method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json(['error' => 'Method not allowed'], 405);
}

// Check auth
if (!is_logged_in()) {
    send_json(['error' => 'Unauthorized'], 401);
}

// Get input
$input = json_decode(file_get_contents('php://input'), true);
$messages = $input['messages'] ?? [];

if (empty($messages)) {
    send_json(['error' => 'Messages are required'], 400);
}

// System Prompt
$systemPrompt = "Eres el Consultor IA de AiScalers, experto en automatización de negocios y eficiencia operativa.
Tu objetivo es ayudar a dueños de negocios a sistematizar sus empresas usando IA, n8n, Make y estrategias de delegación.

Tus principios:
1. Sé pragmático: Da soluciones implementables, no teoría.
2. Sé conciso: Respuestas directas al grano.
3. Cita herramientas: Menciona n8n, Zapier o Make cuando sea relevante.
4. Identidad: Eres parte del equipo de AiScalers, un club de empresarios de alto nivel.

Si te preguntan sobre temas fuera de negocios/sistematización, responde amablemente que tu especialidad es escalar empresas.";

// Prepare OpenAI Request
$api_messages = [['role' => 'system', 'content' => $systemPrompt]];
foreach ($messages as $msg) {
    $api_messages[] = [
        'role' => $msg['role'],
        'content' => $msg['content']
    ];
}

$payload = [
    'model' => 'gpt-4o', // Or gpt-3.5-turbo
    'messages' => $api_messages,
    'temperature' => 0.7,
];

// Call OpenAI API
$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . OPENAI_API_KEY
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    error_log('OpenAI API Error: ' . $response);
    send_json(['error' => 'Error communicating with AI'], 500);
}

$result = json_decode($response, true);
$content = $result['choices'][0]['message']['content'] ?? '';

send_json(['role' => 'assistant', 'content' => $content]);
