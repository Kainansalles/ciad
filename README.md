# FFM - Recebimento de prestação de serviços
Integração com a Fundação Faculdade de Medicina (FMUSP) para facilitar o envio de prestação de serviço para a FFM.

Deve ser enviado um JSON nesse formato abaixo:
```
{
    "id_servico": 10,
    "cliente_cd_ccdcusto": "84300-57",
    "cliente_dt_cadcliente": "2018/05/11",
    "cliente_nrcgccic": 80487679000124,
    "cliente_nofantasia": "Nome da empresa",
    "cliente_nopessoa": "Empresa",
    "cliente_nrrg": 123456789,
    "cliente_dtnasc": "1997-04-07",
    "endereco_nrcep": "01136030",
    "endereco_nrinsest": 12345678901234,
    "endereco_nrinsmunicip": 123456789012345,
    "endereco_nobairro": "Nome Bairro",
    "endereco_norua": "Nome Rua",
    "endereco_nrtelefone": "01188112222",
    "endereco_nrfax": 12345678,
    "endereco_nocontato": "teste",
    "endereco_nm_email": "kainansalles@gmail.com",
    "servico_dt_inicioprestacaoservico": "2018-05-11",
    "servico_dt_finalprestacaoservico": "2018-05-11",
    "servico_id_tipodeservico": 57,
    "servico_ds_descricaoservico": "teste_teste_teste",
    "servico_vl_prestacao": 151,
    "servico_vl_desconto": 11,
    "parcela_operacao": "N",
    "parcela_id_parcela": 1,
    "parcela_st_status": 2,
    "parcela_id_modalidadedepagamento": 4,
    "parcela_dt_vencimento": "2018-05-11",
    "parcela_nr_deposito": 23,
    "parcela_dt_deposito": "1997-07-04",
    "parcela_bc_deposito": 23,
    "parcela_ag_deposito": 23,
    "parcela_cc_deposito": 23,
    "parcela_dt_cheque": "1901-01-01",
    "parcela_cd_bccheque": 23,
    "parcela_nr_cheque": 23,
    "parcela_nm_cheque": 23,
    "parcela_tel_telemissor": 12345678,
    "parcela_bc_boleto": 341,
    "parcela_ag_boleto": 23,
    "parcela_cc_boleto": 23,
    "parcela_nr_boleto": 23,
    "parcela_ln_boleto": 23,
    "parcela_nr_bandeira": 2,
    "parcela_nr_autorizacao": 3,
    "parcela_nr_comprovante": 12,
    "assinatura": "assinatura deve ser solicitado ao gestor de desenvolvimento da FFM"
}
```
