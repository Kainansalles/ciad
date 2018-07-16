<?php

if (!function_exists('ValidaData')) {

    function ValidaData($data) {

        $d = substr($data, 0, 2);
        $m = substr($data, 2, 2);
        $y = substr($data, -4);

        // verifica se a data é válida!
        // 1 = true (válida)
        // 0 = false (inválida)
        $res = checkdate($m, $d, $y);
        if ($res == 1) {
            return true; // Válida
        } else {
            return false;
            "data inválida!";
        }
    }

}

if (!function_exists('dadosObrigatorios')) {

    function dadosObrigatorios() {
        return array(
            "id_servico",
            "cliente_cd_ccdcusto",
            "cliente_dt_cadcliente",
            "cliente_nrcgccic",
            "cliente_nofantasia",
            "cliente_nopessoa",
            "cliente_nrrg",
            "cliente_dtnasc",
            "endereco_nrcep",
            "endereco_nrinsest",
            "endereco_nrinsmunicip",
            "endereco_nobairro",
            "endereco_norua",
            "endereco_nrtelefone",
            "endereco_nrfax",
            "endereco_nocontato",
            "endereco_nm_email",
            "servico_dt_inicioprestacaoservico",
            "servico_dt_finalprestacaoservico",
            "servico_id_tipodeservico",
            "servico_ds_descricaoservico",
            "servico_vl_prestacao",
            "servico_vl_desconto",
            "parcela_operacao",
            "parcela_id_parcela",
            "parcela_st_status",
            "parcela_id_modalidadedepagamento",
            "parcela_dt_vencimento",
            "parcela_nr_deposito",
            "parcela_dt_deposito",
            "parcela_bc_deposito",
            "parcela_ag_deposito",
            "parcela_cc_deposito",
            "parcela_dt_cheque",
            "parcela_cd_bccheque",
            "parcela_nr_cheque",
            "parcela_nm_cheque",
            "parcela_tel_telemissor",
            "parcela_bc_boleto",
            "parcela_ag_boleto",
            "parcela_cc_boleto",
            "parcela_nr_boleto",
            "parcela_ln_boleto",
            "parcela_nr_bandeira",
            "parcela_nr_autorizacao",
            "parcela_nr_comprovante",
            "assinatura",
        );
    }

}
