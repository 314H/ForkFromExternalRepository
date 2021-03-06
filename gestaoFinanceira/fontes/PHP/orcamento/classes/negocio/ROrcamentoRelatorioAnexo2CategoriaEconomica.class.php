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
    * Classe de regra para Anexo2CategoriaEconomica
    * Data de Criação   : 29/09/2004

    * @author Desenvolvedor: Anderson Buzo
    * @author Desenvolvedor: Diego Victoria
    * @author Desenvolvedor: Eduardo Martins
    * @author Desenvolvedor: Gustavo Tourinho

    * @ignore

    $Revision: 30824 $
    $Name$
    $Author: rodrigosoares $
    $Date: 2008-01-07 16:42:06 -0200 (Seg, 07 Jan 2008) $

    * Casos de uso: uc-02.01.11
*/

/*
$Log$
Revision 1.8  2006/07/05 20:42:11  cleisson
Adicionada tag Log aos arquivos

*/

include_once '../../../../../../gestaoAdministrativa/fontes/PHP/framework/include/valida.inc.php';
include_once( CLA_PERSISTENTE_RELATORIO       );
include_once( CAM_GF_ORC_MAPEAMENTO."FOrcamentoSomatorioDespesaUnidadeCategoriaEconomica.class.php" );
include_once( CAM_GF_ORC_MAPEAMENTO."FOrcamentoSomatorioDespesa.class.php"                 );
include_once( CAM_GF_ORC_MAPEAMENTO."TOrcamentoDespesa.class.php"                                        );

class ROrcamentoRelatorioAnexo2CategoriaEconomica
{
/**
    * @var Object
    * @access Private
*/
var $obFOrcamentoSomatorioDespesaUnidadeCategoriaEconomica;
var $obFOrcamentoSomatorioDespesa;
var $stFiltro;
var $inOrgao;
var $inUnidade;
var $inExercicio;

/**
     * @access Public
     * @param Object $valor
*/
function setFOrcamentoSomatorioDespesaUnidadeCategoriaEconomica($valor) { $this->obFOrcamentoSomatorioDespesaUnidadeCategoriaEconomica  = $valor; }
function setFOrcamentoSomatorioDespesa($valor) { $this->obFOrcamentoSomatorioDespesa  = $valor; }
function setFiltro($valor) { $this->stFiltro                      = $valor; }
function setOrgao($valor) { $this->inOrgao                       = $valor; }
function setUnidade($valor) { $this->inUnidade                     = $valor; }
function setExercicio($valor) { $this->inExercicio                   = $valor; }

/**
     * @access Public
     * @param Object $valor
*/
function getFOrcamentoSomatorioDespesaUnidadeCategoriaEconomica() { return $this->obFOrcamentoSomatorioDespesaUnidadeCategoriaEconomica; }
function getFiltro() { return $this->stFiltro                    ; }
function getOrgao() { return $this->inOrgao                     ; }
function getUnidade() { return $this->inUnidade                   ; }
function getExercicio() { return $this->inExercicio                 ; }

/**
    * Método Construtor
    * @access Private
*/
function ROrcamentoRelatorioAnexo2CategoriaEconomica()
{
    $this->setFOrcamentoSomatorioDespesaUnidadeCategoriaEconomica ( new FOrcamentoSomatorioDespesaUnidadeCategoriaEconomica );
    $this->setFOrcamentoSomatorioDespesa                          ( new FOrcamentoSomatorioDespesa                          );
}

/**
    * Método abstrato
    * @access Public
*/
function geraRecordSet(&$rsRecordSet, $inCategoriaEconomica)
{
    if ($inCategoriaEconomica == 9) {
        $this->obFOrcamentoSomatorioDespesa->setDado ("exercicio", $this->inExercicio);
        $this->obFOrcamentoSomatorioDespesa->setDado ("stFiltro" , $this->stFiltro   );
        $stFiltro  = " WHERE valor <> 0 ";
        $stFiltro .= " AND substr(classificacao,1,1)= '9' and nivel = fnorcamentoanexo2despesaconta() ";

        return $obErro = $this->obFOrcamentoSomatorioDespesa->recuperaTodos( $rsRecordSet, $stFiltro, "" );

    }
    if ($inCategoriaEconomica == 7) {
        $this->obFOrcamentoSomatorioDespesa->setDado ("exercicio", $this->inExercicio);
        $this->obFOrcamentoSomatorioDespesa->setDado ("stFiltro" , $this->stFiltro   );
        $stFiltro  = " WHERE valor <> 0 ";
        $stFiltro .= " AND substr(classificacao,1,1)= '7' and nivel = fnorcamentoanexo2despesaconta() ";

        return $obErro = $this->obFOrcamentoSomatorioDespesa->recuperaTodos( $rsRecordSet, $stFiltro, "" );

    }
    // DEFINE NUMERO MAXIMO DE COLUNAS QUE UM BLOCO TERÁ
    $inMaxColunas = 4;
    // ===============================================

    $obTOrcamentoDespesa = new TOrcamentoDespesa;
    $obTOrcamentoDespesa->setDado ( "stFiltro"            , $this->stFiltro    );
    $obTOrcamentoDespesa->setDado ( "categoria_economica" , $inCategoriaEconomica );
    $obTOrcamentoDespesa->setDado ( "exercicio"           , $this->inExercicio );

    // Busca todos nomes de funcoes
    $obErro = $obTOrcamentoDespesa->buscaGrupos ( $rsDespesaNomesGrupos );

    $inNumGrupos = $rsDespesaNomesGrupos->getNumLinhas ();

    $stFuncoes = "";
    $inCount = 0;
    while ( !$rsDespesaNomesGrupos->eof() ) {
        $stFuncoes .= " g_".$rsDespesaNomesGrupos->getCampo("cod_grupo")." numeric(14,2), ";
        $arCabecalhoGrupos[$inCount]["descricao"]  = $rsDespesaNomesGrupos->getCampo("descricao");
        $arCabecalhoGrupos[$inCount]["nom_grupo"] = 'g_'.$rsDespesaNomesGrupos->getCampo("cod_grupo");
        $inCount++;
        $rsDespesaNomesGrupos->proximo();
    }

    if ( !$obErro->ocorreu () ) {
        $this->obFOrcamentoSomatorioDespesaUnidadeCategoriaEconomica->setDado ("categoria_economica", $inCategoriaEconomica );
        $this->obFOrcamentoSomatorioDespesaUnidadeCategoriaEconomica->setDado ("exercicio"           , $this->inExercicio);
        $this->obFOrcamentoSomatorioDespesaUnidadeCategoriaEconomica->setDado ("stFiltro"            , $this->stFiltro);
        $this->obFOrcamentoSomatorioDespesaUnidadeCategoriaEconomica->setDado ("grupos"              , $stFuncoes);

        $obErro = $this->obFOrcamentoSomatorioDespesaUnidadeCategoriaEconomica->recuperaTodos( $rsRecordSet, "", "" );
    }

    // Gera nome dos campos para indice do array
    $arCampos = array ();
    $arCampos[0] = "descricao";

    $arCampoRecordSet = array ();
    $arCampoRecordSet[0] = "nom_unidade";
    for ($inCount = 1, $inCount2 = 0; $inCount2 < $inNumGrupos; $inCount++, $inCount2++) {
        if ($arCabecalhoGrupos[$inCount2]["descricao"] == $stDescAnt) {
            $inDesc++;
            $arCabecalhoGrupos[$inCount2]["descricao"] = $arCabecalhoGrupos[$inCount2]["descricao"].str_pad(' ',$inDesc);
        }
        $arCampos[$inCount] = $arCabecalhoGrupos[$inCount2]["descricao"];
        $arCampoRecordSet[$inCount] = $arCabecalhoGrupos[$inCount2]["nom_grupo"];
        $stDescAnt = $arCabecalhoGrupos[$inCount2]["descricao"];
    }
    $arCampos[$inCount] = "TOTAL";
    $arCampoRecordSet[$inCount] = "vl_total";

    // Monta relatorio completo conforme impressao
    $inCount = 0;
    while ( !$rsRecordSet->eof() ) {
        for ( $inCountCampos = 0; $inCountCampos < count ($arCampos); $inCountCampos++ ) {
            $arRelatorio[$inCount][$arCampos[$inCountCampos]]  = $rsRecordSet->getCampo($arCampoRecordSet[$inCountCampos]);
        }
        $arRelatorio[$inCount]["TOTAL"]  = $rsRecordSet->getCampo("vl_total");

        $inCount++;
        $rsRecordSet->proximo();
    }

    $arTotais = array ();

    $inNumeroBloco = 0;
    $arBloco = array ();  // cria o array que vai conter o bloco com as colunas
    $inTotalBlocos = (int) (($inNumGrupos/$inMaxColunas) + 1);
    for ($inCountColunas = 0, $inCampoCount = 1; $inCountColunas < $inTotalBlocos; $inCountColunas++, $inNumeroBloco++, $inCampoCount = $inCampo) {
        for ( $inLinhasBloco = 0, $rsRecordSet->setPrimeiroElemento(); $inLinhasBloco < $rsRecordSet->getNumLinhas(); $inLinhasBloco++, $rsRecordSet->proximo() ) {
            $arBloco[$inNumeroBloco][$inLinhasBloco]["codigo"]    = $rsRecordSet->getCampo("num_orgao");
            $arBloco[$inNumeroBloco][$inLinhasBloco]["descricao"] = $rsRecordSet->getCampo("nom_unidade");
            $arTotais[0]["descricao"] = "T O T A L ........";
            for ($inColunasBloco = 0, $inCampo = $inCampoCount; $inColunasBloco < $inMaxColunas ; $inColunasBloco++, $inCampo++) {
                $arBloco[$inNumeroBloco][$inLinhasBloco][$arCampos[$inCampo]] = $arRelatorio[$inLinhasBloco][$arCampos[$inCampo]];
                $arTotais[0][$arCampos[$inCampo]] = $arTotais[0][$arCampos[$inCampo]] + $arRelatorio[$inLinhasBloco][$arCampos[$inCampo]];
            }
        }
    }

    $arRetorno = array ($arCampos,
                        $arBloco,
                        $arTotais);

    $rsRecordSetNovo = new RecordSet;
    $rsRecordSetNovo->preenche( $arRetorno );
    $rsRecordSet = $rsRecordSetNovo;

    return $obErro;
}
}
