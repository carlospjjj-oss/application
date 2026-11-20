<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CsrfUploadBypass
{
    public function bypass()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($uri, 'visita_tecnica/uploadMidia') !== false) {
            // Pega o nome do token e cookie do config diretamente
            $config = require(APPPATH . 'config/config.php');
            $tokenName  = $config['csrf_token_name']  ?? 'MAPOS_TOKEN';
            $cookieName = $config['csrf_cookie_name'] ?? 'MAPOS_COOKIE';
            // Pega o hash do cookie atual ou gera um
            $hash = $_COOKIE[$cookieName] ?? sha1(uniqid(mt_rand(), true));
            // Injeta no POST e no cookie para passar na verificação
            $_POST[$tokenName]    = $hash;
            $_COOKIE[$cookieName] = $hash;
        }
    }
}
