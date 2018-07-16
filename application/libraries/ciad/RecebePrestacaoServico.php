<?php

/**
 * Class RecebePrestacaoServico
 * 
 * @author Kainan Salles <kainan@abacos.com.br>
 * @link http://www.abacos.com.br Software para eventos
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class RecebePrestacaoServico {

    /**
     * @param array $data - Array de dados para Recebimento de Prestação de Serviços
     */
    public function prestacaoServico(array $data) {

        switch ($data['parcela_id_modalidadedepagamento']) {
            case 2: // Cheque
                $this->run($data, 'deposito|boleto');
                break;

            case 3: // Crédito em Conta Corrente
                $this->run($data, 'cheque|boleto');
                break;

            case 4: // Espécie - Deposito em dinheiro
                $this->run($data, 'cheque|boleto');
                break;

            case 5: // Cartão de Débito
                $this->run($data, 'cheque|boleto');
                break;

            case 6: // Cartão de Crédito
                $this->run($data, 'cheque|boleto');
                break;

            case 10: // Boleto
                $this->run($data, 'deposito|cheque');
                break;

            default :
                echo json_encode(array("modalidade" => "Modalidade inválida"));
                break;
        }
    }

    /**
      Método responsável por executar o sistema
     */
    private function run(array $data, $modalidade) {
        $data = $this->cleanXML($data, $modalidade);
        $token = $this->getCurl($this->prepareXML($data));
        if ($token) {
            $result = $this->getCurl($this->prepareXML($data, $token));
            if (strpos($result[0], "inserida com sucesso!") != false) {
                echo json_encode(array("sucesso" => "Inserido com sucesso"));
            } else {
                print_r($result);
            }
        }
    }

    /**
     * @param array $data - Array de dados para ser limpo com base na modalidade
     * @param var $modalidade - Modalidade
     * @return array
     */
    private function cleanXML(array $data, $modalidade) {
        $modalidade = explode('|', $modalidade);
        foreach ($modalidade as $m) {
            foreach (array_keys($data) as $key) {
                if (substr($key, 0, strpos($key, $m))) {
                    $result = substr($key, 0, strpos($key, $m));
                    $value = $result . $m;
                    $data[$value] = 0;
                    if (strpos($result, 'dt')) {
                        $data[$key] = "1901-01-01";
                    }
                    if ($m == "boleto") {
                        if (strpos($result, 'bc')) {
                            $data[$key] = 341;
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * @param array $data - Array de dados para Recebimento de Prestação de Serviços
     * @param var $hash - Hash de autenticação
     * @return string - XML dinâmico
     */
    private function prepareXML($data, $hash = " ") {
        $valLiquido = $data['servico_vl_prestacao'] - $data['servico_vl_desconto'];
        $xml_post_string = "
                        <soap:Envelope xmlns:soap='http://www.w3.org/2003/05/soap-envelope' xmlns:tem='http://tempuri.org/'>
                          <soap:Header/>
                            <soap:Body>
                               <tem:RecebePrestacaoServico>
                                  <tem:docXml>
                                    <recebimentos>
                                      <sre id_servico='{$data['id_servico']}'>
                                        <cliente>
                                          <cd_ccdcusto>{$data['cliente_cd_ccdcusto']}</cd_ccdcusto>
                                          <dt_cadcliente>{$data['cliente_dt_cadcliente']}</dt_cadcliente>
                                          <nrcgccic>{$data['cliente_nrcgccic']}</nrcgccic>
                                          <nofantasia>{$this->sanitizeString($data['cliente_nofantasia'])}</nofantasia>
                                          <nopessoa>{$this->sanitizeString($data['cliente_nopessoa'])}</nopessoa>
                                          <nrrg>{$data['cliente_nrrg']}</nrrg>
                                          <dtnasc>{$data['cliente_dtnasc']}</dtnasc>
                                        </cliente>
                                        <endereco>
                                          <nrcep>{$data['endereco_nrcep']}</nrcep>
                                          <nrinsest>{$data['endereco_nrinsest']}</nrinsest>
                                          <nrinsmunicip>{$data['endereco_nrinsmunicip']}</nrinsmunicip>
                                          <nobairro>{$this->sanitizeString($data['endereco_nobairro'])}</nobairro>
                                          <norua>{$this->sanitizeString($data['endereco_norua'])}</norua>
                                          <nrtelefone>{$data['endereco_nrtelefone']}</nrtelefone>
                                          " . ( isset($data['endereco_nrfax']) ? "<nrfax>{$data['endereco_nrfax']}</nrfax>" : "<nrfax>0</nrfax>" ) . "
                                          " . ( isset($data['endereco_nocontato']) ? "<nocontato>{$data['endereco_nocontato']}</nocontato>" : "<nocontato> </nocontato>" ) . "                                                  
                                          <nm_email>{$data['endereco_nm_email']}</nm_email>
                                        </endereco>
                                        <servico>
                                          <dt_inicioprestacaoservico>{$data['servico_dt_inicioprestacaoservico']}</dt_inicioprestacaoservico>
                                          <dt_finalprestacaoservico>{$data['servico_dt_finalprestacaoservico']}</dt_finalprestacaoservico>
                                          <id_tipodeservico>{$data['servico_id_tipodeservico']}</id_tipodeservico>
                                          <ds_descricaoservico>{$this->sanitizeString($data['servico_ds_descricaoservico'], true)}</ds_descricaoservico>
                                          <vl_prestacao>{$data['servico_vl_prestacao']}</vl_prestacao>
                                          <vl_desconto>{$data['servico_vl_desconto']}</vl_desconto>
                                          <vl_liquido>{$valLiquido}</vl_liquido>
                                        </servico>
                                        <parcela>
                                          <operacao>{$data['parcela_operacao']}</operacao>
                                          <id_parcela>{$data['parcela_id_parcela']}</id_parcela>
                                          <st_status>{$data['parcela_st_status']}</st_status>
                                          <id_modalidadedepagamento>{$data['parcela_id_modalidadedepagamento']}</id_modalidadedepagamento>
                                          <dt_vencimento>{$data['parcela_dt_vencimento']}</dt_vencimento>
                                          <vl_parcela>{$valLiquido}</vl_parcela>
                                          <nr_deposito>{$data['parcela_nr_deposito']}</nr_deposito>
                                          <dt_deposito>{$data['parcela_dt_deposito']}</dt_deposito>
                                          <bc_deposito>{$data['parcela_bc_deposito']}</bc_deposito>
                                          <ag_deposito>{$data['parcela_ag_deposito']}</ag_deposito>
                                          <cc_deposito>{$data['parcela_cc_deposito']}</cc_deposito>
                                          <dt_cheque>{$data['parcela_dt_cheque']}</dt_cheque>
                                          <cd_bccheque>{$data['parcela_cd_bccheque']}</cd_bccheque>
                                          <nr_cheque>{$data['parcela_nr_cheque']}</nr_cheque>
                                          <nm_cheque>{$data['parcela_nm_cheque']}</nm_cheque>
                                          <tl_telemissor>{$data['parcela_tel_telemissor']}</tl_telemissor>
                                          <bc_boleto>{$data['parcela_bc_boleto']}</bc_boleto>
                                          <ag_boleto>{$data['parcela_ag_boleto']}</ag_boleto>
                                          <cc_boleto>{$data['parcela_cc_boleto']}</cc_boleto>
                                          <nr_boleto>{$data['parcela_nr_boleto']}</nr_boleto>
                                          <ln_boleto>{$data['parcela_ln_boleto']}</ln_boleto>
                                          <nr_bandeira>{$data['parcela_nr_bandeira']}</nr_bandeira>
                                          <nr_autorizacao>{$data['parcela_nr_autorizacao']}</nr_autorizacao>
                                          <nr_comprovante>{$data['parcela_nr_comprovante']}</nr_comprovante> 
                                        </parcela>
                                        <assinatura>
                                            <nm_assinatura>{$data['assinatura']}</nm_assinatura>
                                        </assinatura>
                                      </sre>
                                      <hash>{$hash}</hash>
                                    </recebimentos>
                                    </tem:docXml>
                               </tem:RecebePrestacaoServico>
                            </soap:Body>
                        </soap:Envelope>";
        return $xml_post_string;
    }

    /**
     * @param var $xml_post_string - String XML para realização do POST
     * @return object ou hash - resultado do webservice
     */
    private function getCurl($xml_post_string) {

        $url = "http://portalffmhm.ffm.br/WFServices/WSSre.asmx?WSDL"; // asmx URL of WSDL    
        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://tempuri.org/RecebePrestacaoServico",
            "Content-length: " . strlen($xml_post_string),
        ); //SOAPAction: your op URL
        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        $response = curl_exec($ch);
        curl_close($ch);

        // converting
        $response1 = str_replace("<soap:Body>", "", $response);
        $response2 = str_replace("</soap:Body>", "", $response1);

        // convertingc to XML
        $parser = simplexml_load_string($response2);
        $result = $parser->RecebePrestacaoServicoResponse->RecebePrestacaoServicoResult;
        if (strpos($result, "gerado=") != false) {
            $arr = explode('gerado=', $result);
            $hash = substr($arr[1], 0, strpos($arr[1], "</Mensagem><Mensagem>"));

            return $hash;
        } else {

            return $result;
        }
    }

    /**
     * @param var $string - String para formatação
     * @param boolean $lower - Se o retorno é maiúsculo ou minúsculo
     * @return string - string formatada
     */
    public function sanitizeString($string, $lower = false) {
        // matriz de entrada
        $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º');

        // matriz de saída
        $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_');

        // devolver a string
        $result = ($lower == false ? strtoupper(str_replace("_", " ", str_replace($what, $by, $string))) : strtolower(str_replace("_", " ", str_replace($what, $by, $string))));
        return $result;
    }

}
