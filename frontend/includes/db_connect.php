<?php
/**
 * FastAPI REST Client Helper
 * 
 * @param string $endpoint API endpoint (e.g., '/users')
 * @param string $method HTTP method (GET, POST, PUT, DELETE)
 * @param array|null $data Request payload
 * @param array $headers Additional headers
 * @param int $timeout Request timeout in seconds
 * @return array Decoded JSON response
 * @throws Exception On connection errors or invalid responses
 */
function call_fastapi(
    string $endpoint, 
    string $method = 'GET', 
    ?array $data = null, 
    array $headers = [],
    int $timeout = 10
): array {
    // Configuration
    $base_url = 'http://localhost:8000';
    $api_key = 'your_api_key_here'; // Store this securely in environment variables
    
    // Validate input
    $valid_methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
    if (!in_array(strtoupper($method), $valid_methods)) {
        throw new InvalidArgumentException("Invalid HTTP method: $method");
    }

    // Initialize cURL
    $ch = curl_init();
    $url = rtrim($base_url, '/') . '/' . ltrim($endpoint, '/');
    
    // Set base options
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_HTTPHEADER => array_merge([
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ], $headers),
    ];

    // Add request data for non-GET methods
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        $options[CURLOPT_POSTFIELDS] = json_encode($data);
    } elseif ($data && $method === 'GET') {
        $url .= '?' . http_build_query($data);
        $options[CURLOPT_URL] = $url;
    }

    curl_setopt_array($ch, $options);

    // Execute request
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Handle errors
    if ($response === false) {
        throw new RuntimeException("cURL error: $error");
    }

    // Decode response
    $decoded = json_decode($response, true) ?? [];
    
    // Handle non-2xx responses
    if ($http_code < 200 || $http_code >= 300) {
        $error_msg = $decoded['detail'] ?? $decoded['message'] ?? 'Unknown API error';
        throw new RuntimeException("API error [$http_code]: $error_msg");
    }

    return $decoded;
}

// Example usage:
try {
    // GET request
    $users = call_fastapi('/users');
    
    // POST request
    $new_user = call_fastapi('/users', 'POST', [
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ]);
    
    // With custom headers
    $profile = call_fastapi('/profile', 'GET', null, [
        'X-Custom-Header: value'
    ]);
    
} catch (Exception $e) {
    error_log("API call failed: " . $e->getMessage());
    // Handle error appropriately
}
?>