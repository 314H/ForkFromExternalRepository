<?php
/*
    **********************************************************************************
    *                                                                                *
    * @package URBEM CNM - Soluções em Gestão Pública                                *
    * @copyright (c) 2013 Confederação Nacional de Municípos                         *
    * @author Confederação Nacional de Municípios                                    *
    *                                                                                *
    * O URBEM CNM é um software livre; você pode redistribuí-lo e/ou modificá-lo sob *
    * os  termos  da Licença Pública Geral GNU conforme  publicada  pela Fundação do *
    * Software Livre (FSF - Free Software Foundation); na versão 2 da Licença.       *
    *                                                                                *
    * Este  programa  é  distribuído  na  expectativa  de  que  seja  útil,   porém, *
    * SEM NENHUMA GARANTIA; nem mesmo a garantia implícita  de  COMERCIABILIDADE  OU *
    * ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA. Consulte a Licença Pública Geral do GNU *
    * para mais detalhes.                                                            *
    *                                                                                *
    * Você deve ter recebido uma cópia da Licença Pública Geral do GNU "LICENCA.txt" *
    * com  este  programa; se não, escreva para  a  Free  Software Foundation  Inc., *
    * no endereço 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.       *
    *                                                                                *
    **********************************************************************************
*/
?>
<?php
/**
  * Página de Include Oculta - Exportação Arquivos TCEMG - HOMOLIC.csv
  * Data de Criação: 01/09/2014

  * @author Analista:      Eduardo Paculski Schitz
  * @author Desenvolvedor: Franver Sarmento de Moraes
  *
  * @ignore
  * $Id: HOMOLIC.csv.inc.php 62269 2015-04-15 18:28:39Z franver $
  * $Date: 2015-04-15 15:28:39 -0300 (Qua, 15 Abr 2015) $
  * $Author: franver $
  * $Rev: 62269 $
  *
*/
// ini_set("display_errors", 1);
// error_reporting(E_ALL);

/**
* HOMOLIC.csv | Autor : Evandro Melos
*/
include_once CAM_GPC_TCEMG_MAPEAMENTO.Sessao::getExercicio()."/TTCEMGHOMOLIC.class.php";

$rsRecordSetHOMOLIC10 = new RecordSet();
$rsRecordSetHOMOLIC20 = new RecordSet();
$rsRecordSetHOMOLIC30 = new RecordSet();
$rsRecordSetHOMOLIC40 = new RecordSet();

$obTTCEMGHOMOLIC = new TTCEMGHOMOLIC();
$obTTCEMGHOMOLIC->setDado('exercicio' ,Sessao::getExercicio());
$obTTCEMGHOMOLIC->setDado('cod_entidade' ,$stEntidades);
$obTTCEMGHOMOLIC->setDado('dt_inicial' ,$stDataInicial);
$obTTCEMGHOMOLIC->setDado('dt_final' ,$stDataFinal);

$obTTCEMGHOMOLIC->recuperaDadosHOMOLIC10($rsRecordSetHOMOLIC10,$boTransacao);

$obTTCEMGHOMOLIC->recuperaDadosHOMOLIC30($rsRecordSetHOMOLIC30, $boTransacao);

// $this->obTTCEMGHOMOLIC->recuperaDadosHOMOLIC20($rsRecordSetHOMOLIC20,$boTransacao

$obTTCEMGHOMOLIC->recuperaDadosHOMOLIC40($rsRecordSetHOMOLIC40,$boTransacao);

//Tipo Registro 99 - Declaro que no mês desta remessa não há informações inerentes ao arquivo “Homologação da Licitação.
$arRecordSetHOMOLIC99 = array(
    '0' => array(
        'tipo_registro' => '99',
    )
);

$rsRecordSetHOMOLIC99 = new RecordSet();
$rsRecordSetHOMOLIC99->preenche($arRecordSetHOMOLIC99);

$inContador = 0;
$inCount = 0;
$stChave30 = '';

$arrHomolic20 = array();
foreach ($rsRecordSetHOMOLIC20->getElementos() as $arHOMOLIC20) {
	$stChave20  = $arHOMOLIC20['cod_orgao'].$arHOMOLIC20['cod_unidadesub'].$arHOMOLIC20['exercicio_licitacao'];
	$stChave20 .= $arHOMOLIC20['nro_processolicitatorio'].$arHOMOLIC20['tipo_documento']
		.$arHOMOLIC20['nro_documento'];
	$stChave20 .= $arHOMOLIC20['nro_lote'].$arHOMOLIC20['cod_item'].$arHOMOLIC20['quantidade'];
	$arrHomolic20['10'.$stChave20][] = $arHOMOLIC20;
}

$arrHomolic30 = array();
foreach ($rsRecordSetHOMOLIC30->getElementos() as $arHOMOLIC30) {
	$stChave30  = $arHOMOLIC30['nro_processolicitatorio'];
	$stChaveItem30  = $arHOMOLIC30['cod_item'];
	$stChaveDocumento30 = $arHOMOLIC30['nro_documento'];
    $arrHomolic30[$stChave30][$stChaveItem30][$stChaveDocumento30][] = $arHOMOLIC30;
}

$arrHomolic40 = array();
foreach ($rsRecordSetHOMOLIC40->getElementos() as $arHOMOLIC40) {
	$stChave40  = $arHOMOLIC40['nro_processolicitatorio'];
    $arrHomolic40[$stChave40][] = $arHOMOLIC40;
}

$arRecordSetHOMOLIC10 = $rsRecordSetHOMOLIC10->getElementos();

if (count($arRecordSetHOMOLIC10) > 0) {
    $stChave10 = '';
    foreach ( $arRecordSetHOMOLIC10 as $arHOMOLIC10 ) {
        $inContador++;
        if ( !($stChave10 === $arHOMOLIC10['tiporegistro']
                             .$arHOMOLIC10['cod_orgao']
                             .$arHOMOLIC10['cod_unidadesub']
                             .$arHOMOLIC10['exercicio_licitacao']
                             .$arHOMOLIC10['nro_processolicitatorio']
                             .$arHOMOLIC10['tipo_documento']
                             .$arHOMOLIC10['nro_documento']
                             .$arHOMOLIC10['nro_lote']
                             .$arHOMOLIC10['cod_item']
                             .$arHOMOLIC10['quantidade'] )) {

            $inCount++;
            $stChave10 =  $arHOMOLIC10['tiporegistro']
                         .$arHOMOLIC10['cod_orgao']
                         .$arHOMOLIC10['cod_unidadesub']
                         .$arHOMOLIC10['exercicio_licitacao']
                         .$arHOMOLIC10['nro_processolicitatorio']
                         .$arHOMOLIC10['tipo_documento']
                         .$arHOMOLIC10['nro_documento']
                         .$arHOMOLIC10['nro_lote']
                         .$arHOMOLIC10['cod_item']
                         .$arHOMOLIC10['quantidade'];

            $stNumProcLic = $arHOMOLIC10['nro_processolicitatorio'];
	        $stCodIdem = $arHOMOLIC10['cod_item'];
            $rsBloco10 = 'rsBloco10_'.$inCount;
            unset($$rsBloco10);
            $$rsBloco10 = new RecordSet();
            $$rsBloco10->preenche(array($arHOMOLIC10));

            $obExportador->roUltimoArquivo->setTipoDocumento('TCE_MG');
            $obExportador->roUltimoArquivo->addBloco( $$rsBloco10 );

            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("tiporegistro");
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(2);

            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("cod_orgao");
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(2);

            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("cod_unidadesub");
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(5);

            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("exercicio_licitacao");
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(4);

            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("nro_processolicitatorio");
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(12);

            //**OBS: Falta a validacao na consulta para o tipo de documento, está buscando pelo tcemg.uniorcam
            // mas deve buscar pela configuracao do urbem que ainda não foi criada
            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("tipo_documento");
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(1);

            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("nro_documento");
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(14);

            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("nro_lote");
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(4);

            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("cod_item");
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(15);

            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("quantidade");
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("VALOR_ZEROS_ESQ");
            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(14);

            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("valor_unitario");
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("VALOR_ZEROS_ESQ");
            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(14);

			//APENAS  QUANDO: o valor do campo descontoTabela do registro 10 – Detalhamento dos Convites / Editais de Licitação
			//do arquivo 5.12 ABERLIC for 1 – Sim
			//Se houver registros no array
			$stChave  = $arHOMOLIC10['cod_orgao'].$arHOMOLIC10['cod_unidadesub'].$arHOMOLIC10['exercicio_licitacao'];
			$stChave .= $arHOMOLIC10['nro_processolicitatorio'].$arHOMOLIC10['tipo_documento'].$arHOMOLIC10['nro_documento'];
			$stChave .= $arHOMOLIC10['nro_lote'].$arHOMOLIC10['cod_item'].$arHOMOLIC10['quantidade'];

			if (count($arrHomolic20['10'.$stChave]) > 0) {
				$stChave20 = '';

				//Percorre array de registros
				foreach ($arrHomolic20['10'.$stChave] as $arHOMOLIC20) {


					//Verifica se registro 20 bate com chave do registro 10
					if ($stChave10 === '10'.$stChave) {
						//Chave única do registro 20
						if ($stChave20 !=  $arHOMOLIC20['tiporegistro'].$stChave ) {

                            $stChave20 = $arHOMOLIC20['tiporegistro'].$stChave20Aux;

                            $rsBloco20 = 'rsBloco20_'.$inCount;
                            unset($$rsBloco20);
                            $$rsBloco20 = new RecordSet();
                            $$rsBloco20->preenche(array($arHOMOLIC20));

                            $obExportador->roUltimoArquivo->setTipoDocumento('TCE_MG');
                            $obExportador->roUltimoArquivo->addBloco( $$rsBloco20 );

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("tiporegistro");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(2);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("cod_orgao");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(2);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("cod_unidadesub");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(5);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("exercicio_licitacao");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(4);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("nro_processolicitatorio");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(12);

                            //**OBS: Falta a validacao na consulta para o tipo de documento, está buscando pelo tcemg.uniorcam
                            // mas deve buscar pela configuracao do urbem que ainda não foi criada
                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("tipo_documento");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(1);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("nro_documento");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(14);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("nro_lote");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(4);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("cod_item");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(15);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("perc_desconto");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("VALOR_ZEROS_ESQ");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(6);

                        }//Fim foreach HOMOLIC20
                    }
                }
            }

            // if ($arHOMOLIC10['criterio_adjudicacao'] == 2) {
            //     $obTTCEMGHOMOLIC->recuperaDadosHOMOLIC30($rsRecordSetHOMOLIC30, $boTransacao, " AND config_licitacao.num_licitacao = '" . $stNumProcLic . "'");
            // }

	        $stChaveProcesso30 = $arHOMOLIC10['nro_processolicitatorio'];
	        $stChaveItem30Aux = $arHOMOLIC10['cod_item'];
	        $stChaveDocumento30Aux = $arHOMOLIC10['nro_documento'];
	        //Se houver registros no array
	        if ( count($arrHomolic30[$stChaveProcesso30][$stChaveItem30Aux][$stChaveDocumento30Aux]) > 0 ) {
		        //Percorre array de registros

		        foreach ($arrHomolic30[$stChaveProcesso30][$stChaveItem30Aux][$stChaveDocumento30Aux] as $arHOMOLIC30) {
                    //Verifica se registro 10 bate com chave do registro 30
                        $stChave30 = $stChave30Aux;
                        $rsBloco30 = 'rsBloco30_'.$inCount;
                        unset($$rsBloco30);
                        $$rsBloco30 = new RecordSet();
                        $$rsBloco30->preenche(array($arHOMOLIC30));

                        $obExportador->roUltimoArquivo->setTipoDocumento('TCE_MG');
                        $obExportador->roUltimoArquivo->addBloco( $$rsBloco30 );

                        $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("tiporegistro");
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                        $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(2);

                        $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("cod_orgao");
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                        $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(2);

                        $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("cod_unidadesub");
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                        $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(5);

                        $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("exercicio_licitacao");
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                        $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(4);

                        $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("nro_processolicitatorio");
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
                        $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(12);

                        $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("tipo_documento");
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
                        $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(1);

                        $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("nro_documento");
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
                        $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(14);

                        $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("nro_lote");
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
                        $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(4);

                        $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("cod_item");
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
                        $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(15);

                        $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("percentual_taxa_administracao");
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("VALOR_ZEROS_ESQ");
                        $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                        $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(6);
                }//Fim if HOMOLIC30
            }//Fim foreach HOMOLIC30

	        //Verifica se  o proximo num_processo_licitatorio do array é diferente
	        if($arRecordSetHOMOLIC10[$inContador]['nro_processolicitatorio'] != $stNumProcLic){
		        //Se houver registros no array
		        if ( count($arrHomolic40[$arHOMOLIC10['nro_processolicitatorio']]) > 0 ) {
			        //Percorre array de registros
			        foreach ($arrHomolic40[$arHOMOLIC10['nro_processolicitatorio']] as $arHOMOLIC40) {
                        $stChave40Aux = $arHOMOLIC40['nro_processolicitatorio'];
                        //Verifica se registro 10 bate com chave do registro 40
                        if ( $stNumProcLic === $stChave40Aux ) {
                            $stChave40 = $stChave40Aux;
                            $rsBloco40 = 'rsBloco40_'.$inCount;
                            unset($$rsBloco40);
                            $$rsBloco40 = new RecordSet();
                            $$rsBloco40->preenche(array($arHOMOLIC40));

                            $obExportador->roUltimoArquivo->setTipoDocumento('TCE_MG');
                            $obExportador->roUltimoArquivo->addBloco( $$rsBloco40 );

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("tiporegistro");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(2);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("cod_orgao");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(2);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("cod_unidadesub");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(5);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("exercicio_licitacao");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(4);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("nro_processolicitatorio");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("CARACTER_ESPACOS_DIR");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoMaximo(12);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("dt_homologacao");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("DATA_DDMMYYYY");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(8);

                            $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("dt_adjudicacao");
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("DATA_DDMMYYYY");
                            $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
                            $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(8);
                        }//Fim if HOMOLIC40
                    }//Fim if HOMOLIC40
                }//Fim foreach HOMOLIC40
            }//Fim if HOMOLIC40
        }
    }// Fim do foreach principal HOMOLIC10
} else {
    $obExportador->roUltimoArquivo->addBloco($rsRecordSetHOMOLIC99);
    $obExportador->roUltimoArquivo->roUltimoBloco->addColuna("tipo_registro");
    $obExportador->roUltimoArquivo->roUltimoBloco->setDelimitador(';');
    $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTipoDado("NUMERICO_ZEROS_ESQ");
    $obExportador->roUltimoArquivo->roUltimoBloco->roUltimaColuna->setTamanhoFixo(2);
}

$rsRecordSetHOMOLIC10 = null;
$rsRecordSetHOMOLIC20 = null;
$rsRecordSetHOMOLIC30 = null;
$obTTCEMGHOMOLIC      = null;
$rsRecordSetHOMOLIC99 = null;
$arRecordSetHOMOLIC10 = null;

?>
