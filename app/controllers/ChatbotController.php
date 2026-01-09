<?php
class ChatbotController {

    // Affiche la vue du chatbot
    public function index() {
        require "../app/views/chatbot/index.php";
    }

    // Point d'API: relais vers le backend Python (FastAPI)
    public function api() {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $message = isset($input['message']) ? $input['message'] : '';

        if (empty($message)) {
            echo json_encode(['response' => 'Message vide']);
            return;
        }

        $backend = getenv('CHATBOT_BACKEND') ?: 'http://127.0.0.1:8000/chat';

        $payload = json_encode(['message' => $message]);

        // Essayer d'utiliser cURL si disponible
        $result = false;
        $httpcode = 0;

        if (function_exists('curl_init')) {
            $ch = curl_init($backend);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $result = curl_exec($ch);
            $err = curl_error($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        } else {
            // Fallback : file_get_contents avec contexte HTTP
            $opts = [
                'http' => [
                    'method'  => 'POST',
                    'header'  => "Content-Type: application/json\r\n",
                    'content' => $payload,
                    'timeout' => 10
                ]
            ];
            $context  = stream_context_create($opts);
            $result = @file_get_contents($backend, false, $context);
            if (isset($http_response_header) && preg_match('#HTTP/\d+\.\d+\s+(\d+)#', $http_response_header[0], $m)) {
                $httpcode = intval($m[1]);
            }
        }

        if ($result === false || $result === null) {
            echo json_encode(['response' => 'Le serveur de chatbot est indisponible.']);
            return;
        }

        $decoded = json_decode($result, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['response'])) {
            echo json_encode(['response' => $decoded['response']]);
        } else if ($httpcode >=200 && $httpcode <300) {
            echo json_encode(['response' => is_string($result) ? $result : 'Réponse inconnue']);
        } else {
            echo json_encode(['response' => 'Erreur du backend chatbot.']);
        }
    }

}
