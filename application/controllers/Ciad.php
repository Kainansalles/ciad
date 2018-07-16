<?php

/**
 * Class RecebePrestacaoServico
 * 
 * @author Kainan Salles <kainan@abacos.com.br>
 * @link http://www.abacos.com.br Software para eventos
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Ciad extends CI_Controller {

    /**
      Realiza a comunicação com o Webserver do CIAD
     */
    public function SendRPS() {
        $requestBody = json_decode($this->input->raw_input_stream, true);
        if (is_array($requestBody) && $this->validateData($requestBody)) {
            $this->load->library('ciad/RecebePrestacaoServico');
            $data = array(
                'id_servico' => $requestBody['id_servico'],
                'cliente_cd_ccdcusto' => $requestBody['cliente_cd_ccdcusto'],
                'cliente_dt_cadcliente' => str_replace("/", '-', $requestBody['cliente_dt_cadcliente']),
                'cliente_nrcgccic' => $requestBody['cliente_nrcgccic'],
                'cliente_nofantasia' => $requestBody['cliente_nofantasia'],
                'cliente_nopessoa' => $requestBody['cliente_nopessoa'],
                'cliente_nrrg' => $requestBody['cliente_nrrg'],
                'cliente_dtnasc' => str_replace("/", '-', $requestBody['cliente_dtnasc']),
                'endereco_nrcep' => $requestBody['endereco_nrcep'],
                'endereco_nrinsest' => $requestBody['endereco_nrinsest'],
                'endereco_nrinsmunicip' => $requestBody['endereco_nrinsmunicip'],
                'endereco_nobairro' => $requestBody['endereco_nobairro'],
                'endereco_norua' => $requestBody['endereco_norua'],
                'endereco_nrtelefone' => $requestBody['endereco_nrtelefone'],
                'endereco_nrfax' => $requestBody['endereco_nrfax'],
                'endereco_nocontato' => $requestBody['endereco_nocontato'],
                'endereco_nm_email' => $requestBody['endereco_nm_email'],
                'servico_dt_inicioprestacaoservico' => str_replace("/", '-', $requestBody['servico_dt_inicioprestacaoservico']),
                'servico_dt_finalprestacaoservico' => str_replace("/", '-', $requestBody['servico_dt_finalprestacaoservico']),
                'servico_id_tipodeservico' => $requestBody['servico_id_tipodeservico'],
                'servico_ds_descricaoservico' => $requestBody['servico_ds_descricaoservico'],
                'servico_vl_prestacao' => $requestBody['servico_vl_prestacao'],
                'servico_vl_desconto' => $requestBody['servico_vl_desconto'],
                'parcela_operacao' => $requestBody['parcela_operacao'],
                'parcela_id_parcela' => $requestBody['parcela_id_parcela'],
                'parcela_st_status' => $requestBody['parcela_st_status'],
                'parcela_id_modalidadedepagamento' => $requestBody['parcela_id_modalidadedepagamento'],
                'parcela_dt_vencimento' => str_replace("/", '-', $requestBody['parcela_dt_vencimento']),
                'parcela_nr_deposito' => $requestBody['parcela_nr_deposito'],
                'parcela_dt_deposito' => str_replace("/", '-', $requestBody['parcela_dt_deposito']),
                'parcela_bc_deposito' => $requestBody['parcela_bc_deposito'],
                'parcela_ag_deposito' => $requestBody['parcela_ag_deposito'],
                'parcela_cc_deposito' => $requestBody['parcela_dt_cheque'],
                'parcela_dt_cheque' => str_replace("/", '-', $requestBody['parcela_dt_cheque']),
                'parcela_cd_bccheque' => $requestBody['parcela_cd_bccheque'],
                'parcela_nr_cheque' => $requestBody['parcela_nr_cheque'],
                'parcela_nm_cheque' => $requestBody['parcela_nm_cheque'],
                'parcela_tel_telemissor' => $requestBody['parcela_tel_telemissor'],
                'parcela_bc_boleto' => $requestBody['parcela_bc_boleto'],
                'parcela_ag_boleto' => $requestBody['parcela_ag_boleto'],
                'parcela_cc_boleto' => $requestBody['parcela_cc_boleto'],
                'parcela_nr_boleto' => $requestBody['parcela_nr_boleto'],
                'parcela_ln_boleto' => $requestBody['parcela_ln_boleto'],
                'parcela_nr_bandeira' => $requestBody['parcela_nr_bandeira'],
                'parcela_nr_autorizacao' => $requestBody['parcela_nr_autorizacao'],
                'parcela_nr_comprovante' => $requestBody['parcela_nr_comprovante'],
                'assinatura' => $requestBody['assinatura']
            );

            $this->recebeprestacaoservico->prestacaoServico($data);
        }
    }

    /**
      @param array $data - array de dados para ser validados
      @return boolean
     */
    private function validateData($data) {
        $langDir = sprintf('%s/libraries/ciad/lang/', dirname(dirname(__DIR__)));
        $this->load->library('validator/Validator', $data, array(), 'pt-br', $langDir);

        foreach (dadosObrigatorios() as $key) {
            $this->validator->rule('required', $key);
        }

        $rules = [
            'integer' => [
                ['id_servico',
                    'cliente_nrrg',
                    'endereco_nrfax',
                    'servico_id_tipodeservico',
                    'parcela_id_parcela',
                    'parcela_st_status',
                    'parcela_id_modalidadedepagamento',
                    'parcela_nr_deposito',
                    'parcela_bc_deposito',
                    'parcela_ag_deposito',
                    'parcela_cc_deposito',
                    'parcela_cd_bccheque',
                    'parcela_nr_cheque',
                    'parcela_nm_cheque',
                    'parcela_tel_telemissor',
                    'parcela_bc_boleto',
                    'parcela_ag_boleto',
                    'parcela_cc_boleto',
                    'parcela_nr_boleto',
                    'parcela_ln_boleto',
                    'parcela_nr_bandeira',
                    'parcela_nr_autorizacao',
                    'parcela_nr_comprovante',
                ]
            ],
            'email' => ['endereco_nm_email'],
            'date' => [
                'cliente_dt_cadcliente',
                'cliente_dtnasc',
                'servico_dt_inicioprestacaoservico',
                'servico_dt_finalprestacaoservico',
                'parcela_dt_vencimento',
                'parcela_dt_deposito',
                'parcela_dt_cheque',
            ],
        ];
        $this->validator->rules($rules);

        if ($this->validator->validate()) {
            return true;
        } else {
            // Errors
            echo json_encode($this->validator->errors());
            return false;
        }
    }

}
