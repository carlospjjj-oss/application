<?php

use Piggly\Pix\StaticPayload;

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Vendas_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($table, $fields, $where = [], $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $lista_clientes = [];
        if ($where) {
            if (array_key_exists('pesquisa', $where)) {
                $this->db->select('idClientes');
                $this->db->like('nomeCliente', $where['pesquisa']);
                $this->db->limit(25);
                $clientes = $this->db->get('clientes')->result();

                foreach ($clientes as $c) {
                    array_push($lista_clientes, $c->idClientes);
                }
            }
        }
        $this->db->select($fields . ', clientes.nomeCliente, clientes.idClientes');
        $this->db->from($table);
        $this->db->limit($perpage, $start);
        $this->db->join('clientes', 'clientes.idClientes = ' . $table . '.clientes_id');
        $this->db->join('usuarios', 'usuarios.idUsuarios = ' . $table . '.usuarios_id');
        if ($table === 'vendas') {
            $this->db->where('vendas.convertida', 0);
        }
        $this->db->order_by('idVendas', 'desc');
        
        // condicionais da pesquisa
        if ($where) {
            // condicional de status
            if (array_key_exists('status', $where)) {
                $this->db->where_in('vendas.status', $where['status']);
            }

            // condicional de clientes
            if (array_key_exists('pesquisa', $where)) {
                if ($lista_clientes != null) {
                    $this->db->where_in('vendas.clientes_id', $lista_clientes);
                }
            }

            // condicional data Venda
            if (array_key_exists('de', $where)) {
                $this->db->where('vendas.dataVenda >=', $where['de']);
            }
            // condicional data final
            if (array_key_exists('ate', $where)) {
                $this->db->where('vendas.dataVenda <=', $where['ate']);
            }
        }
        $query = $this->db->get();

        $result = !$one ? $query->result() : $query->row();

        return $result;
    }

    public function getVendas($table, $fields, $where = [], $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $lista_clientes = [];
        if ($where) {
            if (array_key_exists('pesquisa', $where)) {
                $this->db->select('idClientes');
                $this->db->like('nomeCliente', $where['pesquisa']);
                $this->db->limit(25);
                $clientes = $this->db->get('clientes')->result();

                foreach ($clientes as $c) {
                    array_push($lista_clientes, $c->idClientes);
                }
            }
        }

        $this->db->select($fields . ',clientes.idClientes, clientes.nomeCliente, clientes.celular as celular_cliente, usuarios.nome, garantias.*');
        $this->db->from($table);
        $this->db->join('clientes', 'clientes.idClientes = vendas.clientes_id');
        $this->db->join('usuarios', 'usuarios.idUsuarios = vendas.usuarios_id');
        $this->db->join('garantias', 'garantias.idGarantias = vendas.garantias_id', 'left');
        $this->db->join('produtos_vendas', 'produtos_vendas.vendas_id = vendas.idVendas', 'left');
        $this->db->join('servicos_vendas', 'servicos_vendas.vendas_id = vendas.idVendas', 'left');

        // condicionais da pesquisa

        // condicional de status
        if (array_key_exists('status', $where)) {
            $this->db->where_in('status', $where['status']);
        }

        // condicional de clientes
        if (array_key_exists('pesquisa', $where)) {
            if ($lista_clientes != null) {
                $this->db->where_in('vendas.clientes_id', $lista_clientes);
            }
        }

        // condicional data inicial
        if (array_key_exists('de', $where)) {
            $this->db->where('dataInicial >=', $where['de']);
        }
        // condicional data final
        if (array_key_exists('ate', $where)) {
            $this->db->where('dataFinal <=', $where['ate']);
        }

        $this->db->limit($perpage, $start);
        $this->db->order_by('vendas.idVendas', 'desc');
        $this->db->group_by('vendas.idVendas');

        $query = $this->db->get();

        $result = !$one ? $query->result() : $query->row();

        return $result;
    }

    public function getById($id)
    {
        $this->db->select('vendas.*, clientes.*, clientes.contato as contato_cliente, clientes.email as emailCliente, lancamentos.data_vencimento, usuarios.telefone as telefone_usuario, usuarios.email as email_usuario, usuarios.nome as nome');
        $this->db->from('vendas');
        $this->db->join('clientes', 'clientes.idClientes = vendas.clientes_id');
        $this->db->join('usuarios', 'usuarios.idUsuarios = vendas.usuarios_id');
        $this->db->join('lancamentos', 'vendas.idVendas = lancamentos.vendas_id', 'LEFT');
        $this->db->where('vendas.idVendas', $id);
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    public function isEditable($id = null)
    {
        if ($vendas = $this->getById($id)) {
            if ($vendas->convertida) {
                return false;
            }
            if ($vendas->faturado) {
                return $this->data['configuration']['control_edit_vendas'] == '1';
            }
        }

        return true;
    }

    public function getByIdCobrancas($id)
    {
        $this->db->select('vendas.*, clientes.*, clientes.email as emailCliente, lancamentos.data_vencimento, usuarios.telefone as telefone_usuario, usuarios.email as email_usuario, usuarios.nome, usuarios.nome, cobrancas.vendas_id,cobrancas.idCobranca,cobrancas.status');
        $this->db->from('vendas');
        $this->db->join('clientes', 'clientes.idClientes = vendas.clientes_id');
        $this->db->join('usuarios', 'usuarios.idUsuarios = vendas.usuarios_id');
        $this->db->join('cobrancas', 'cobrancas.vendas_id = vendas.idVendas');
        $this->db->join('lancamentos', 'vendas.idVendas = lancamentos.vendas_id', 'LEFT');
        $this->db->where('vendas.idVendas', $id);
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    public function getProdutos($id = null)
    {
        $this->db->select('itens_de_vendas.*, produtos.*');
        $this->db->from('itens_de_vendas');
        $this->db->join('produtos', 'produtos.idProdutos = itens_de_vendas.produtos_id');
        $this->db->where('vendas_id', $id);

        return $this->db->get()->result();
    }

    public function getCobrancas($id = null)
    {
        $this->db->select('cobrancas.*');
        $this->db->from('cobrancas');
        $this->db->where('vendas_id', $id);

        return $this->db->get()->result();
    }

    public function add($table, $data, $returnId = false)
    {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            if ($returnId == true) {
                return $this->db->insert_id($table);
            }

            return true;
        }

        return false;
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0) {
            return true;
        }

        return false;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1') {
            return true;
        }

        return false;
    }

    public function count($table)
    {
        if ($table === 'vendas') {
            $this->db->where('convertida', 0);
        }
        return $this->db->count_all_results($table);
    }

    public function converterParaOs($idVendas)
    {
        $venda = $this->getById($idVendas);
        if (! $venda) {
            return false;
        }

        $this->db->trans_start();

        $osData = [
            'dataInicial' => date('Y-m-d'),
            'dataFinal' => null,
            'garantia' => $venda->garantia,
            'descricaoProduto' => 'Convertido da Venda #' . $venda->idVendas,
            'status' => 'Aprovado',
            'observacoes' => $venda->observacoes,
            'valorTotal' => $venda->valorTotal,
            'desconto' => $venda->desconto,
            'valor_desconto' => $venda->valor_desconto,
            'tipo_desconto' => $venda->tipo_desconto,
            'clientes_id' => $venda->clientes_id,
            'usuarios_id' => $venda->usuarios_id,
            'faturado' => 0,
        ];

        $this->db->insert('os', $osData);
        $novoOsId = $this->db->insert_id();

        $itens = $this->getProdutos($idVendas);
        foreach ($itens as $item) {
            $this->db->insert('produtos_os', [
                'quantidade' => $item->quantidade,
                'descricao' => $item->descricao,
                'preco' => $item->preco,
                'os_id' => $novoOsId,
                'produtos_id' => $item->produtos_id,
                'subTotal' => $item->subTotal,
            ]);
        }

        $this->db->where('idVendas', $idVendas);
        $this->db->update('vendas', ['convertida' => 1, 'os_id' => $novoOsId]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        }

        return $novoOsId;
    }

    public function autoCompleteProduto($q)
    {
        $this->db->select('*');
        $this->db->limit(25);
        $this->db->like('descricao', $q);
        $query = $this->db->get('produtos');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label' => $row['descricao'] . ' | Preço: R$ ' . $row['precoVenda'] . ' | Estoque: ' . $row['estoque'], 'estoque' => $row['estoque'], 'id' => $row['idProdutos'], 'preco' => $row['precoVenda']];
            }
            echo json_encode($row_set);
        }
    }

    public function autoCompleteCliente($q)
    {
        $this->db->select('*');
        $this->db->limit(25);
        $this->db->like('nomeCliente', $q);
        $this->db->or_like('documento', $q);
        $query = $this->db->get('clientes');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>$row['nomeCliente'].' | Celular: '.$row['celular'].' | Documento: '.$row['documento'],'id'=>$row['idClientes']];
            }
            echo json_encode($row_set);
        } else {
            $row_set[] = ['label' => 'Adicionar cliente...', 'id' => null];
            echo json_encode($row_set);
        }
    }

    public function autoCompleteVendedor($q)
    {
        $row_set = [];
        $labelsUsados = [];

        // Usuarios cadastrados no sistema (mantem o comportamento antigo de trocar o dono da venda)
        $this->db->select('idUsuarios, nome');
        $this->db->limit(10);
        $this->db->like('nome', $q);
        $this->db->where('situacao', 1);
        $queryUsuarios = $this->db->get('usuarios');
        if ($queryUsuarios->num_rows() > 0) {
            foreach ($queryUsuarios->result_array() as $row) {
                $row_set[] = ['label' => $row['nome'], 'id' => $row['idUsuarios'], 'source_type' => 'usuario'];
                $labelsUsados[] = $row['nome'];
            }
        }

        // Nomes ja digitados antes no campo Vendedor (texto livre, aprendido)
        $this->db->select('DISTINCT(vendedor) as vendedor');
        $this->db->limit(10);
        $this->db->like('vendedor', $q);
        $this->db->where('vendedor IS NOT NULL', null, false);
        $queryVendedor = $this->db->get('vendas');
        if ($queryVendedor->num_rows() > 0) {
            foreach ($queryVendedor->result_array() as $row) {
                if (!in_array($row['vendedor'], $labelsUsados)) {
                    $row_set[] = ['label' => $row['vendedor'], 'id' => null, 'source_type' => 'texto'];
                }
            }
        }

        echo json_encode($row_set);
    }

    public function autoCompleteUsuario($q)
    {
        $this->db->select('*');
        $this->db->limit(25);
        $this->db->like('nome', $q);
        $this->db->where('situacao', 1);
        $query = $this->db->get('usuarios');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label' => $row['nome'] . ' | Telefone: ' . $row['telefone'], 'id' => $row['idUsuarios']];
            }
            echo json_encode($row_set);
        }
    }

    public function getQrCode($id, $pixKey, $emitente)
    {
        if (empty($id) || empty($pixKey) || empty($emitente)) {
            return;
        }

        $produtos = $this->getProdutos($id);
        $valorDesconto = $this->getById($id);
        $totalProdutos = array_reduce(
            $produtos,
            function ($carry, $produto) {
                return $carry + ($produto->quantidade * $produto->preco);
            },
            0
        );
        $amount = $valorDesconto->valor_desconto != 0 ? round(floatval($valorDesconto->valor_desconto), 2) : round(floatval($totalProdutos), 2);

        if ($amount <= 0) {
            return;
        }

        $pix = (new StaticPayload())
            ->setAmount($amount)
            ->setTid($id)
            ->setDescription(sprintf('%s Venda %s', substr($emitente->nome, 0, 18), $id), true)
            ->setPixKey(getPixKeyType($pixKey), $pixKey)
            ->setMerchantName($emitente->nome)
            ->setMerchantCity($emitente->cidade);

        return $pix->getQRCode();
    }

    public function getTotalVendas($idVendas)
    {
        $produtos = $this->getProdutos($idVendas);
        $total = 0;

        foreach ($produtos as $produto) {
            $total += $produto->quantidade * $produto->preco;
        }

        return $total;
    }
}

/* End of file vendas_model.php */
/* Location: ./application/models/vendas_model.php */
