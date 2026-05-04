<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Proton extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mapos_model');
    }

    public function emitente()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cEmitente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para configurar emitente.');
            redirect(base_url());
        }

        $this->data['menuConfiguracoes'] = 'Configuracoes';
        $this->data['dados'] = $this->mapos_model->getEmitente();
        $this->data['view'] = 'proton/emitente';

        return $this->layout();
    }

    public function cadastrarEmitente()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cEmitente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para configurar emitente.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nome', 'Razão Social', 'required|trim');
        $this->form_validation->set_rules('cnpj', 'CNPJ', 'required|trim');
        $this->form_validation->set_rules('ie', 'IE', 'trim');
        $this->form_validation->set_rules('cep', 'CEP', 'required|trim');
        $this->form_validation->set_rules('logradouro', 'Logradouro', 'required|trim');
        $this->form_validation->set_rules('numero', 'Número', 'required|trim');
        $this->form_validation->set_rules('bairro', 'Bairro', 'required|trim');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required|trim');
        $this->form_validation->set_rules('uf', 'UF', 'required|trim');
        $this->form_validation->set_rules('telefone', 'Telefone', 'required|trim');
        $this->form_validation->set_rules('email', 'E-mail', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Campos obrigatórios não foram preenchidos.');
            redirect(site_url('proton/emitente'));
        }

        $nome = $this->input->post('nome');
        $cnpj = $this->input->post('cnpj');
        $ie = $this->input->post('ie');
        $cep = $this->input->post('cep');
        $logradouro = $this->input->post('logradouro');
        $numero = $this->input->post('numero');
        $bairro = $this->input->post('bairro');
        $cidade = $this->input->post('cidade');
        $uf = $this->input->post('uf');
        $telefone = $this->input->post('telefone');
        $email = $this->input->post('email');
        $image = $this->do_upload();
        $logo = base_url() . 'assets/uploads/' . $image;

        $retorno = $this->mapos_model->addEmitente($nome, $cnpj, $ie, $cep, $logradouro, $numero, $bairro, $cidade, $uf, $telefone, $email, $logo);
        if ($retorno) {
            $this->session->set_flashdata('success', 'As informações foram inseridas com sucesso.');
            log_info('Adicionou informações de emitente.');
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar inserir as informações.');
        }

        redirect(site_url('proton/emitente'));
    }

    public function editarEmitente()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cEmitente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para configurar emitente.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nome', 'Razão Social', 'required|trim');
        $this->form_validation->set_rules('cnpj', 'CNPJ', 'required|trim');
        $this->form_validation->set_rules('ie', 'IE', 'trim');
        $this->form_validation->set_rules('cep', 'CEP', 'required|trim');
        $this->form_validation->set_rules('logradouro', 'Logradouro', 'required|trim');
        $this->form_validation->set_rules('numero', 'Número', 'required|trim');
        $this->form_validation->set_rules('bairro', 'Bairro', 'required|trim');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required|trim');
        $this->form_validation->set_rules('uf', 'UF', 'required|trim');
        $this->form_validation->set_rules('telefone', 'Telefone', 'required|trim');
        $this->form_validation->set_rules('email', 'E-mail', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Campos obrigatórios não foram preenchidos.');
            redirect(site_url('proton/emitente'));
        }

        $nome = $this->input->post('nome');
        $cnpj = $this->input->post('cnpj');
        $ie = $this->input->post('ie');
        $cep = $this->input->post('cep');
        $logradouro = $this->input->post('logradouro');
        $numero = $this->input->post('numero');
        $bairro = $this->input->post('bairro');
        $cidade = $this->input->post('cidade');
        $uf = $this->input->post('uf');
        $telefone = $this->input->post('telefone');
        $email = $this->input->post('email');
        $id = $this->input->post('id');

        $retorno = $this->mapos_model->editEmitente($id, $nome, $cnpj, $ie, $cep, $logradouro, $numero, $bairro, $cidade, $uf, $telefone, $email);
        if ($retorno) {
            $this->session->set_flashdata('success', 'As informações foram alteradas com sucesso.');
            log_info('Alterou informações de emitente.');
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar alterar as informações.');
        }

        redirect(site_url('proton/emitente'));
    }

    public function editarLogo()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cEmitente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para configurar emitente.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar alterar a logomarca.');
            redirect(site_url('proton/emitente'));
        }

        $this->load->helper('file');
        delete_files(FCPATH . 'assets/uploads/');

        $image = $this->do_upload();
        $logo = base_url() . 'assets/uploads/' . $image;

        $retorno = $this->mapos_model->editLogo($id, $logo);
        if ($retorno) {
            $this->session->set_flashdata('success', 'As informações foram alteradas com sucesso.');
            log_info('Alterou a logomarca do emitente.');
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar alterar as informações.');
        }

        redirect(site_url('proton/emitente'));
    }

    public function do_upload()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cEmitente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para configurar emitente.');
            redirect(base_url());
        }

        $this->load->library('upload');

        $image_upload_folder = FCPATH . 'assets/uploads';
        if (!file_exists($image_upload_folder)) {
            mkdir($image_upload_folder, DIR_WRITE_MODE, true);
        }

        $upload_config = [
            'upload_path' => $image_upload_folder,
            'allowed_types' => 'png|jpg|jpeg|bmp|svg',
            'max_size' => 2048,
            'remove_space' => true,
            'encrypt_name' => true,
        ];

        $this->upload->initialize($upload_config);

        if (!$this->upload->do_upload()) {
            $upload_error = $this->upload->display_errors();
            print_r($upload_error);
            exit();
        }

        $file_info = [$this->upload->data()];
        return $file_info[0]['file_name'];
    }
}

