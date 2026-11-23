<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Execucao_model extends CI_Model
{
    // ----------------------------------------------------------------
    // Listagem
    // ----------------------------------------------------------------
    public function getAll($pesquisa = '', $status = '', $de = '', $ate = '', $perpage = 0, $start = 0)
    {
        $this->db->select('execucoes.*, os.idOs, os.dataInicial, os.dataFinal, os.garantia,
                           clientes.nomeCliente, usuarios.nome as tecnico');
        $this->db->from('execucoes');
        $this->db->join('os',       'os.idOs = execucoes.os_id');
        $this->db->join('clientes', 'clientes.idClientes = os.clientes_id');
        $this->db->join('usuarios', 'usuarios.idUsuarios = os.usuarios_id', 'left');

        if ($pesquisa) {
            $this->db->like('clientes.nomeCliente', $pesquisa, 'after');
        }
        if ($status) {
            $this->db->where('execucoes.status', $status);
        }
        if ($de) {
            $this->db->where('os.dataInicial >=', $de);
        }
        if ($ate) {
            $this->db->where('os.dataInicial <=', $ate);
        }

        $this->db->order_by('execucoes.idExecucao', 'DESC');

        if ($perpage > 0) {
            $this->db->limit($perpage, $start);
        }

        return $this->db->get()->result();
    }

    public function count($pesquisa = '', $status = '', $de = '', $ate = '')
    {
        $this->db->from('execucoes');
        $this->db->join('os',       'os.idOs = execucoes.os_id');
        $this->db->join('clientes', 'clientes.idClientes = os.clientes_id');

        if ($pesquisa) $this->db->like('clientes.nomeCliente', $pesquisa, 'after');
        if ($status)   $this->db->where('execucoes.status', $status);
        if ($de)       $this->db->where('os.dataInicial >=', $de);
        if ($ate)      $this->db->where('os.dataInicial <=', $ate);

        return $this->db->count_all_results();
    }

    // ----------------------------------------------------------------
    // Busca por OS
    // ----------------------------------------------------------------
    public function getByOs($os_id)
    {
        $this->db->select('execucoes.*, os.idOs, os.dataInicial, os.dataFinal, os.garantia,
                           clientes.nomeCliente, clientes.idClientes,
                           clientes.email, clientes.celular as celular_cliente, clientes.telefone as telefone_cliente,
                           usuarios.nome as tecnico, usuarios.idUsuarios');
        $this->db->from('execucoes');
        $this->db->join('os',       'os.idOs = execucoes.os_id');
        $this->db->join('clientes', 'clientes.idClientes = os.clientes_id');
        $this->db->join('usuarios', 'usuarios.idUsuarios = os.usuarios_id', 'left');
        $this->db->where('execucoes.os_id', $os_id);
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    public function getById($id)
    {
        $this->db->select('execucoes.*, os.idOs, os.dataInicial, os.dataFinal, os.garantia,
                           clientes.nomeCliente, clientes.idClientes,
                           clientes.email, clientes.celular as celular_cliente, clientes.telefone as telefone_cliente,
                           usuarios.nome as tecnico, usuarios.idUsuarios');
        $this->db->from('execucoes');
        $this->db->join('os',       'os.idOs = execucoes.os_id');
        $this->db->join('clientes', 'clientes.idClientes = os.clientes_id');
        $this->db->join('usuarios', 'usuarios.idUsuarios = os.usuarios_id', 'left');
        $this->db->where('execucoes.idExecucao', $id);
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    // ----------------------------------------------------------------
    // Criar execução a partir de uma OS
    // ----------------------------------------------------------------
    public function createFromOs($os_id, $engenheiro = null)
    {
        // Evita duplicata
        if ($this->getByOs($os_id)) return true;

        // Busca dados da OS para preencher a execução
        $os = $this->db->where('idOs', $os_id)->get('os')->row();

        $data = ['os_id' => $os_id, 'status' => 'Aberto'];

        if ($os) {
            if (!empty($os->tipo_instalacao))        $data['tipo_instalacao']         = $os->tipo_instalacao;
            if (!empty($os->data_inicio_instalacao) && $os->data_inicio_instalacao != '0000-00-00')
                                                     $data['data_inicio_instalacao']  = $os->data_inicio_instalacao;
            if (!empty($os->data_fim_instalacao) && $os->data_fim_instalacao != '0000-00-00')
                                                     $data['data_fim_instalacao']     = $os->data_fim_instalacao;
            if (!empty($os->engenheiro))             $data['engenheiro']              = $os->engenheiro;
        }

        // Parâmetro explícito sobrescreve o da OS
        if ($engenheiro) $data['engenheiro'] = $engenheiro;

        $this->db->insert('execucoes', $data);
        return $this->db->affected_rows() == 1 ? $this->db->insert_id() : false;
    }

    // ----------------------------------------------------------------
    // Sincroniza campos vazios da execução com a OS (para execuções já criadas)
    // ----------------------------------------------------------------
    public function syncFromOs($os_id)
    {
        $exec = $this->getByOs($os_id);
        if (!$exec) return false;

        $os = $this->db->where('idOs', $os_id)->get('os')->row();
        if (!$os) return false;

        $update = [];
        if (empty($exec->tipo_instalacao) && !empty($os->tipo_instalacao))
            $update['tipo_instalacao'] = $os->tipo_instalacao;
        if (empty($exec->data_inicio_instalacao) && !empty($os->data_inicio_instalacao) && $os->data_inicio_instalacao != '0000-00-00')
            $update['data_inicio_instalacao'] = $os->data_inicio_instalacao;
        if (empty($exec->data_fim_instalacao) && !empty($os->data_fim_instalacao) && $os->data_fim_instalacao != '0000-00-00')
            $update['data_fim_instalacao'] = $os->data_fim_instalacao;
        if (empty($exec->engenheiro) && !empty($os->engenheiro))
            $update['engenheiro'] = $os->engenheiro;

        if (!empty($update)) {
            $this->db->where('idExecucao', $exec->idExecucao)->update('execucoes', $update);
        }
        return true;
    }

    // ----------------------------------------------------------------
    // Atualizar status e checklist
    // ----------------------------------------------------------------
    public function updateStatus($id, $status)
    {
        $this->db->where('idExecucao', $id);
        $this->db->update('execucoes', ['status' => $status]);
        return $this->db->affected_rows() >= 0;
    }

    public function updateChecklist($id, $data)
    {
        $this->db->where('idExecucao', $id);
        $this->db->update('execucoes', $data);
        return $this->db->affected_rows() >= 0;
    }

    // ----------------------------------------------------------------
    // Anexos
    // ----------------------------------------------------------------
    public function getAnexos($execucao_id)
    {
        $this->db->where('execucao_id', $execucao_id);
        return $this->db->get('anexos_execucao')->result();
    }

    public function addAnexo($data)
    {
        $this->db->insert('anexos_execucao', $data);
        return $this->db->affected_rows() == 1;
    }

    public function deleteAnexo($id)
    {
        $this->db->where('idAnexo', $id);
        return $this->db->delete('anexos_execucao');
    }
}
