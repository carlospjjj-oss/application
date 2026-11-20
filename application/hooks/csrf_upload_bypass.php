<?php
class CsrfUploadBypass
{
    public function bypass()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($uri, 'visita_tecnica/uploadMidia') !== false) {
            $cookieName = 'MAPOS_COOKIE';
            $tokenName  = 'MAPOS_TOKEN';
            // Usa o hash do cookie existente — mesmo valor que o CI vai comparar
            $hash = $_COOKIE[$cookieName] ?? bin2hex(random_bytes(16));
            $_POST[$tokenName]    = $hash;
            $_COOKIE[$cookieName] = $hash;
        }
    }
}
