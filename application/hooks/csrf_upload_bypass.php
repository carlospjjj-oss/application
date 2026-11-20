<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CsrfUploadBypass
{
    public function bypass()
    {
        $uri = ltrim($_SERVER['REQUEST_URI'] ?? '', '/');
        // Remove query string
        $uri = strtok($uri, '?');
        // Normaliza removendo index.php
        $uri = preg_replace('#^index\.php/#', '', $uri);

        if (strpos($uri, 'visita_tecnica/uploadMidia') !== false) {
            $CI =& get_instance();
            // Injeta o token válido no POST para passar na verificação do CI2
            $tokenName = $CI->config->item('csrf_token_name');
            $hash      = $CI->security->get_csrf_hash();
            $_POST[$tokenName]    = $hash;
            $_COOKIE[$CI->config->item('csrf_cookie_name')] = $hash;
        }
    }
}
