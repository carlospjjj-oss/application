<?php
class CsrfUploadBypass
{
    public function bypass()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($uri, 'visita_tecnica/uploadMidia') !== false) {
            // Lê o cookie name do .env ou usa o padrão
            // Lê o .env manualmente pois pre_system roda antes do CI carregar o .env
            $envFile = isset($_SERVER['DOCUMENT_ROOT']) ? dirname($_SERVER['DOCUMENT_ROOT']) . '/.env' : '';
            $cookieName = 'MAPOS_COOKIE';
            $tokenName  = 'MAPOS_TOKEN';
            if ($envFile && file_exists($envFile)) {
                $env = parse_ini_file($envFile);
                $cookieName = $env['APP_CSRF_COOKIE_NAME'] ?? $cookieName;
                $tokenName  = $env['APP_CSRF_TOKEN_NAME']  ?? $tokenName;
            }
            $hash = $_COOKIE[$cookieName] ?? bin2hex(random_bytes(16));
            $_POST[$tokenName]    = $hash;
            $_COOKIE[$cookieName] = $hash;
        }
    }
}
