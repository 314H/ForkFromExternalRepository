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
    * Página de Formulario de Seleção de Impressora para Relatorio
    * Data de Criação   : 19/12/2004

    * @author Analista: Jorge B. Ribarr
    * @author Desenvolvedor: Anderson R. M. Buzo

    * @ignore

    $Revision: 30805 $
    $Name$
    $Autor: $
    $Date: 2006-07-05 17:51:50 -0300 (Qua, 05 Jul 2006) $

    * Casos de uso: uc-02.03.03
                    uc-02.03.18
*/

/*
$Log$
Revision 1.6  2006/07/05 20:48:34  cleisson
Adicionada tag Log aos arquivos

*/

include_once '../../../../../../gestaoAdministrativa/fontes/PHP/pacotes/FrameworkHTML.inc.php';
include_once '../../../../../../gestaoAdministrativa/fontes/PHP/framework/include/valida.inc.php';
include_once( CAM_FW_PDF."RRelatorio.class.php"                             );
include_once( CAM_GF_EMP_NEGOCIO."REmpenhoRelatorioNotaEmpenhoAnulado.class.php");

$obRRelatorio  = new RRelatorio;
$obRegra       = new REmpenhoRelatorioNotaEmpenhoAnulado;

$arRecordSetSessao = Sessao::read('filtroRelatorio');
$obRegra->setExercicio  ( Sessao::getExercicio() );
$obRegra->setExercicioEmpenho  ( $arRecordSetSessao['stDtExercicioEmpenho'] );
$obRegra->setImplantado        ( $arRecordSetSessao['boImplantado'] );
$obRegra->setCodEntidade( $arRecordSetSessao['inCodEntidade'] );
$obRegra->setCodEmpenho ( $arRecordSetSessao['inCodEmpenho'] );
$obRegra->setTimestamp  ( $arRecordSetSessao['timestamp'] );

$obRegra->setSaldoDotacao ( Sessao::read('nuSaldoDotacao'));
if (Sessao::read('reemitir') == "t") {
    $obRegra->setReemitir("t");
}

$obRegra->geraRecordSet( $arRecordSet );
Sessao::write('arRecordSet', $arRecordSet);

$obRRelatorio->executaFrameOculto( "OCGeraRelatorioEmpenhoOrcamentarioAnulado.php" );

?>
