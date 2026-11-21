<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auditoria extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'cAuditoria')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar logs do sistema.');
            redirect(base_url());
        }
        $this->load->model('Audit_model');
        $this->data['menuConfiguracoes'] = 'Auditoria';
    }

    public function index()
    {
        $this->load->library('pagination');

        $usuario  = $this->input->get('usuario');
        $where    = $usuario ? ['usuario' => $usuario] : '';

        $this->data['configuration']['base_url'] = site_url('auditoria/index/');
        $this->data['configuration']['total_rows'] = $this->Audit_model->count('logs');
        if ($usuario) {
            $this->data['configuration']['suffix'] = "?usuario={$usuario}";
        }

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results']  = $this->Audit_model->get('logs', '*', $where, $this->data['configuration']['per_page'], $this->uri->segment(3));
        $this->data['usuarios'] = $this->Audit_model->getUsuarios();

        $this->data['view'] = 'auditoria/logs';

        return $this->layout();
    }

    public function clean()
    {
        if ($this->Audit_model->clean()) {
            log_info('Efetuou limpeza de logs');
            $this->session->set_flashdata('success', 'Limpeza de logs realizada com sucesso.');
        } else {
            $this->session->set_flashdata('error', 'Nenhum log com mais de 30 dias encontrado.');
        }
        redirect(site_url('auditoria'));
    }
}

/* End of file Controllername.php */
