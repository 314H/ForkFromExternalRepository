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
    * Página de Formulario de Oculto de Previsão Receitaa
    * Data de Criação   : 28/07/2004

    * @author Desenvolvedor: Roberto Pawelski Rodrigues

    * @ignore

    $Revision: 30668 $
    $Name$
    $Autor: $
    $Date: 2007-01-30 13:31:20 -0200 (Ter, 30 Jan 2007) $

    * Casos de uso: uc-02.01.06
*/

/*
$Log$
Revision 1.7  2007/01/30 15:30:20  luciano
#7317#

Revision 1.6  2007/01/30 11:43:44  luciano
#7317#

Revision 1.5  2006/07/05 20:43:03  cleisson
Adicionada tag Log aos arquivos

*/

include_once '../../../../../../gestaoAdministrativa/fontes/PHP/pacotes/FrameworkHTML.inc.php';
include_once '../../../../../../gestaoAdministrativa/fontes/PHP/framework/include/valida.inc.php';
include_once( CAM_GF_ORC_NEGOCIO."ROrcamentoPrevisaoDespesa.class.php"    );
include_once( CAM_GF_ORC_NEGOCIO."ROrcamentoConfiguracao.class.php"       );
include_once( CAM_FW_HTML."MontaOrgaoUnidade.class.php"        );

$obRPrevisaoDespesa         = new ROrcamentoPrevisaoDespesa;
$obRConfiguracaoOrcamento   = new ROrcamentoConfiguracao;
$obMascara                  = new Mascara;
$obMontaOrgaoUnidade        = new MontaOrgaoUnidade;

/**
    * Define o nome dos arquivos PHP
*/
$stPrograma = "MetasDespesa";
$pgFilt = "FL".$stPrograma.".php";
$pgList = "LS".$stPrograma.".php";
$pgForm = "FM".$stPrograma.".php";
$pgProc = "PR".$stPrograma.".php";
$pgOcul = "OC".$stPrograma.".php";
$pgJS   = "JS".$stPrograma.".js";

include_once($pgJS);

switch ($_POST["stCtrl"]) {
    case "buscaOrgaoUnidade":
        $obMontaOrgaoUnidade->buscaValoresUnidade();
    break;

    case "preencheUnidade":
        $obMontaOrgaoUnidade->preencheUnidade();
    break;

    case "preencheMascara":
        $obMontaOrgaoUnidade->preencheMascara();
    break;

    case "mascaraClassificacaoFiltro":
        $arMascClassificacao = Mascara::validaMascaraDinamica( $_POST['stMascClassificacao'] , $_POST['inCodClassificacao'] );
        $js .= "f.inCodClassificacao.value = '".$arMascClassificacao[1]."'; \n";
        SistemaLegado::executaFrameOculto( $js );
    break;

    case 'preencheInner':
        $obMontaOrgaoUnidade->setExecutaFrame( false );
        $js .= $obMontaOrgaoUnidade->preencheUnidade();
        $js .= 'd.getElementById("stDescricaoRecurso").innerHTML  = "'.$_POST["stDescricaoRecurso"].'";';
        SistemaLegado::executaFrameOculto($js);
    break;
}
?>
