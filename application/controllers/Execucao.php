<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Execucao extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('execucao_model');
        $this->load->model('os_model');
        $this->data['menuArquivos'] = 'Arquivos'; // mantém item de menu ativo
    }

    // ----------------------------------------------------------------
    // Listagem
    // ----------------------------------------------------------------
    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vArquivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar execuções.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $pesquisa = $this->input->get('pesquisa');
        $status   = $this->input->get('status');
        $de       = $this->input->get('data');
        $ate      = $this->input->get('data2');

        if ($de)  { $de  = $this->_formatDate($de);  }
        if ($ate) { $ate = $this->_formatDate($ate); }

        $perpage = isset($this->data['configuration']['per_page']) ? $this->data['configuration']['per_page'] : 20;
        $start   = $this->uri->segment(3) ?: 0;

        $this->data['configuration']['base_url']   = site_url('execucao/gerenciar');
        $this->data['configuration']['total_rows']  = $this->execucao_model->count($pesquisa, $status, $de, $ate);
        $this->pagination->initialize($this->data['configuration']);

        $this->data['results']  = $this->execucao_model->getAll($pesquisa, $status, $de, $ate, $perpage, $start);
        $this->data['pesquisa'] = $pesquisa;
        $this->data['status']   = $status;
        $this->data['view']     = 'execucao/execucao';

        return $this->layout();
    }

    // ----------------------------------------------------------------
    // Editar
    // ----------------------------------------------------------------
    public function editar($os_id = null)
    {
        if (! $os_id || ! is_numeric($os_id)) {
            $this->session->set_flashdata('error', 'Execução não encontrada.');
            redirect('execucao');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vArquivo')) {
            $this->session->set_flashdata('error', 'Sem permissão.');
            redirect(base_url());
        }

        // Garante que a execução existe (cria se necessário)
        $this->execucao_model->createFromOs($os_id);
        // Sincroniza campos da OS que possam estar vazios na execução
        $this->execucao_model->syncFromOs($os_id);
        $exec = $this->execucao_model->getByOs($os_id);

        if (! $exec) {
            $this->session->set_flashdata('error', 'OS não encontrada.');
            redirect('execucao');
        }

        $this->data['result']   = $exec;
        $this->data['produtos'] = $this->os_model->getProdutos($os_id);
        $this->data['servicos'] = $this->os_model->getServicos($os_id);
        $this->data['anotacoes']= $this->os_model->getAnotacoes($os_id);
        $this->data['anexos']   = $this->execucao_model->getAnexos($exec->idExecucao);
        $this->data['usuarios'] = $this->db->get('usuarios')->result();
        $this->data['view']     = 'execucao/editarExecucao';

        return $this->layout();
    }

    // ----------------------------------------------------------------
    // Salvar status + checklist (AJAX ou form)
    // ----------------------------------------------------------------
    public function salvar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eArquivo')) {
            $this->session->set_flashdata('error', 'Sem permissão.');
            redirect('execucao');
        }

        $id     = $this->input->post('idExecucao');
        $os_id  = $this->input->post('os_id');
        $status = $this->input->post('status');

        // Checklist instalação elétrica
        $checklistInstalacao = ['chk_quadro','chk_disjuntor','chk_cabos','chk_fixacao','chk_teste','chk_orientacao'];
        // Checklist documentação
        $checklistDocs = [
            'doc_projeto_eletrico','doc_as_built','doc_croqui','doc_diagrama_unifilar',
            'doc_memorial_descritivo','doc_memorial_calculo','doc_laudo_tecnico',
            'doc_art_projeto','doc_art_execucao','doc_relatorio_fotografico',
            'doc_relatorio_comissionamento','doc_orientacao_operacao','doc_orientacao_emergencia',
        ];

        $tipo_instalacao       = $this->input->post('tipo_instalacao');
        $data_inicio_instalacao= $this->input->post('data_inicio_instalacao');
        $data_fim_instalacao   = $this->input->post('data_fim_instalacao');
        if ($data_inicio_instalacao) $data_inicio_instalacao = $this->_formatDate($data_inicio_instalacao);
        if ($data_fim_instalacao)    $data_fim_instalacao    = $this->_formatDate($data_fim_instalacao);

        $data = [
            'status'                 => $status,
            'tipo_instalacao'        => $tipo_instalacao ?: null,
            'data_inicio_instalacao' => $data_inicio_instalacao ?: null,
            'data_fim_instalacao'    => $data_fim_instalacao    ?: null,
        ];
        foreach (array_merge($checklistInstalacao, $checklistDocs) as $field) {
            $data[$field] = $this->input->post($field) ? 1 : 0;
        }

        $this->execucao_model->updateChecklist($id, $data);
        $this->session->set_flashdata('success', 'Execução atualizada com sucesso!');
        redirect(site_url('execucao/editar/' . $os_id));
    }

    // ----------------------------------------------------------------
    // Visualizar
    // ----------------------------------------------------------------
    public function visualizar($os_id = null)
    {
        if (! $os_id || ! is_numeric($os_id)) {
            $this->session->set_flashdata('error', 'Execução não encontrada.');
            redirect('execucao');
        }

        $exec = $this->execucao_model->getByOs($os_id);
        if (! $exec) {
            $this->session->set_flashdata('error', 'Execução não encontrada.');
            redirect('execucao');
        }

        $this->data['result']   = $exec;
        $this->data['produtos'] = $this->os_model->getProdutos($os_id);
        $this->data['servicos'] = $this->os_model->getServicos($os_id);
        $this->data['anotacoes']= $this->os_model->getAnotacoes($os_id);
        $this->data['view']     = 'execucao/visualizarExecucao';

        return $this->layout();
    }

    // ----------------------------------------------------------------
    // CSRF token para AJAX
    // ----------------------------------------------------------------
    public function getCsrfToken()
    {
        echo json_encode(['csrf_hash' => $this->security->get_csrf_hash()]);
        exit;
    }

    // ----------------------------------------------------------------
    // Upload de anexo (AJAX) — 6 fotos/vídeos + 1 PDF + 1 DOC
    // ----------------------------------------------------------------
    public function upload_anexo_ajax()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eArquivo')) {
            echo json_encode(['success' => false, 'message' => 'Sem permissão.']); exit;
        }

        $execucao_id = $this->input->post('execucao_id');
        $os_id       = $this->input->post('os_id');

        // Conta anexos existentes por tipo
        $existentes = $this->execucao_model->getAnexos($execucao_id);
        $qtdFotos = 0; $qtdPdf = 0; $qtdDoc = 0;
        foreach ($existentes as $a) {
            $ext = strtolower(pathinfo($a->anexo, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','webp','mp4','mov','webm'])) $qtdFotos++;
            elseif ($ext === 'pdf') $qtdPdf++;
            elseif (in_array($ext, ['doc','docx'])) $qtdDoc++;
        }

        $file = $_FILES['anexo'] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Nenhum arquivo enviado.']); exit;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $isFotoVideo = in_array($ext, ['jpg','jpeg','png','gif','webp','mp4','mov','webm']);
        $isPdf       = ($ext === 'pdf');
        $isDoc       = in_array($ext, ['doc','docx']);

        if (!$isFotoVideo && !$isPdf && !$isDoc) {
            echo json_encode(['success' => false, 'message' => 'Tipo não permitido. Use fotos, vídeos, PDF ou DOC.']); exit;
        }
        if ($isFotoVideo && $qtdFotos >= 6) {
            echo json_encode(['success' => false, 'message' => 'Limite de 6 fotos/vídeos atingido.']); exit;
        }
        if ($isPdf && $qtdPdf >= 1) {
            echo json_encode(['success' => false, 'message' => 'Limite de 1 PDF atingido.']); exit;
        }
        if ($isDoc && $qtdDoc >= 1) {
            echo json_encode(['success' => false, 'message' => 'Limite de 1 documento atingido.']); exit;
        }

        $date = date('d-m-Y');
        $dir  = './assets/arquivos/execucao/' . $date;
        if (! is_dir($dir)) mkdir($dir, 0777, true);

        $config = [
            'upload_path'   => $dir,
            'allowed_types' => 'jpg|jpeg|png|gif|webp|mp4|mov|webm|pdf|doc|docx',
            'max_size'      => 0,
            'encrypt_name'  => true,
        ];
        $this->load->library('upload', $config);

        if (! $this->upload->do_upload('anexo')) {
            echo json_encode(['success' => false, 'message' => strip_tags($this->upload->display_errors())]); exit;
        }

        $up = $this->upload->data();
        $this->execucao_model->addAnexo([
            'execucao_id' => $execucao_id,
            'anexo'       => $up['file_name'],
            'url'         => base_url() . 'assets/arquivos/execucao/' . $date . '/' . $up['file_name'],
            'path'        => $up['full_path'],
        ]);

        echo json_encode(['success' => true, 'csrf_hash' => $this->security->get_csrf_hash()]);
        exit;
    }

    // ----------------------------------------------------------------
    // Upload de anexo (form tradicional — mantido para compatibilidade)
    // ----------------------------------------------------------------
    public function upload_anexo()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eArquivo')) {
            $this->session->set_flashdata('error', 'Sem permissão.');
            redirect('execucao');
        }
        $os_id = $this->input->post('os_id');
        $this->session->set_flashdata('error', 'Use os botões de upload na página.');
        redirect(site_url('execucao/editar/' . $os_id));
    }

    // ----------------------------------------------------------------
    // Excluir anexo
    // ----------------------------------------------------------------
    public function excluir_anexo()
    {
        $id    = $this->input->post('idAnexo');
        $os_id = $this->input->post('os_id');

        $this->execucao_model->deleteAnexo($id);
        $this->session->set_flashdata('success', 'Anexo removido.');
        redirect(site_url('execucao/editar/' . $os_id));
    }

    // ----------------------------------------------------------------
    // Excluir execução
    // ----------------------------------------------------------------
    public function excluir($id = null)
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eArquivo')) {
            $this->session->set_flashdata('error', 'Sem permissão.');
            redirect('execucao');
        }

        if (! $id || ! is_numeric($id)) {
            redirect('execucao');
        }

        $this->db->where('idExecucao', $id);
        $this->db->delete('execucoes');

        $this->session->set_flashdata('success', 'Execução excluída com sucesso!');
        redirect('execucao');
    }

    // ----------------------------------------------------------------
    // Criar nova execução (sem OS prévia)
    // ----------------------------------------------------------------
    public function criar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eArquivo')) {
            $this->session->set_flashdata('error', 'Sem permissão.');
            redirect('execucao');
        }

        $this->data['clientes'] = $this->db->order_by('nomeCliente','asc')->get('clientes')->result();
        $this->data['usuarios'] = $this->db->order_by('nome','asc')->get('usuarios')->result();
        $this->data['view']     = 'execucao/criarExecucao';

        return $this->layout();
    }

    public function salvar_novo()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eArquivo')) {
            $this->session->set_flashdata('error', 'Sem permissão.');
            redirect('execucao');
        }

        $cliente_id  = $this->input->post('clientes_id');
        $usuario_id  = $this->input->post('usuarios_id');
        $dataInicial = $this->input->post('dataInicial');
        $dataFinal   = $this->input->post('dataFinal');
        $garantia    = $this->input->post('garantia') ?: 365;
        $status      = $this->input->post('status') ?: 'Aberto';

        if ($dataInicial) $dataInicial = $this->_formatDate($dataInicial);
        if ($dataFinal)   $dataFinal   = $this->_formatDate($dataFinal);

        // Cria OS mínima
        $this->db->insert('os', [
            'clientes_id' => $cliente_id,
            'usuarios_id' => $usuario_id,
            'dataInicial' => $dataInicial ?: date('Y-m-d'),
            'dataFinal'   => $dataFinal   ?: null,
            'garantia'    => $garantia,
            'status'      => $status,
        ]);
        $os_id = $this->db->insert_id();

        // Cria execução vinculada
        $engenheiro = $this->input->post('engenheiro');
        $this->execucao_model->createFromOs($os_id, $engenheiro);

        $this->session->set_flashdata('success', 'Execução criada com sucesso!');
        redirect(site_url('execucao/editar/' . $os_id));
    }

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------
    private function _formatDate($date)
    {
        $parts = explode('/', $date);
        if (count($parts) == 3) return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        return $date;
    }
}
