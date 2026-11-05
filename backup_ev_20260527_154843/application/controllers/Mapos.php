<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Redirecionamento 301: URLs antigas .../mapos/* → .../proton/*
 * Sem lógica de negócio; use sempre {@see Proton}.
 */
class Mapos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function _remap($method, $params = [])
    {
        $segments = [];
        if ($method !== 'index') {
            $segments[] = $method;
        }
        if (!empty($params)) {
            $segments = array_merge($segments, $params);
        }
        $suffix = $segments ? implode('/', $segments) : '';
        $url = site_url('proton' . ($suffix !== '' ? '/' . $suffix : ''));
        redirect($url, 'location', 301);
    }
}
