<?php
class CsrfUploadBypass
{
    public function bypass()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($uri, 'visita_tecnica/uploadMidia') !== false) {
            // Lê o cookie name do .env ou usa o padrão
            $cookieName = getenv('APP_CSRF_COOKIE_NAME') ?: 'MAPOS_COOKIE';
            $tokenName  = getenv('APP_CSRF_TOKEN_NAME')  ?: 'MAPOS_TOKEN';
            $hash = $_COOKIE[$cookieName] ?? bin2hex(random_bytes(16));
            $_POST[$tokenName]    = $hash;
            $_COOKIE[$cookieName] = $hash;
        }
    }
}
