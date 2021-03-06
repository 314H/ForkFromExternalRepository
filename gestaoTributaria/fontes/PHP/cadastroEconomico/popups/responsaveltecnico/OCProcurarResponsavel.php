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
    * PÃ¡gina do Frame oculto para Popup de Responsavel Tecnico
    * Data de CriaÃ§Ã£o   : 20/04/2005

    * @author Tonismar Régis Bernardo

    * @ignore

    * $Id: OCProcurarResponsavel.php 59612 2014-09-02 12:00:51Z gelson $

    Casos de uso: uc-05.02.04
*/

/*
$Log$
Revision 1.7  2006/09/15 13:50:37  fabio
correção do cabeçalho,
adicionado trecho de log do CVS

*/

include_once '../../../../../../gestaoAdministrativa/fontes/PHP/pacotes/FrameworkHTML.inc.php';
include_once '../../../../../../gestaoAdministrativa/fontes/PHP/framework/include/valida.inc.php';

include_once ( CAM_GA_CSE_NEGOCIO."RProfissao.class.php"       );
include_once ( CAM_GA_CSE_NEGOCIO."RConselho.class.php"        );
include_once ( CAM_GA_ADM_NEGOCIO."RAdministracaoUF.class.php" );
include_once ( CAM_GT_CIM_NEGOCIO."RCIMLoteRural.class.php"    );
include_once ( CAM_GT_CIM_NEGOCIO."RCIMLoteUrbano.class.php"   );
include_once ( CAM_GA_CGM_NEGOCIO."RCGMPessoaFisica.class.php" );
include_once ( CAM_FW_URBEM."MontaLocalizacao.class.php"     );

switch ($_REQUEST['stCtrl']) {
    case "montaAtributosProfissao":
        $obRProfissao       = new RProfissao;
        $obRConselho        = new RConselho ;
        if ($_REQUEST[ "cmbProfissao" ]) {
            $obRProfissao->setCodigoProfissao( $_REQUEST["cmbProfissao"]        );
        } elseif ($_REQUEST[ "inCodigoProfissao" ]) {
            $obRProfissao->setCodigoProfissao( $_REQUEST["inCodigoProfissao"]   );
        }

        $obErro = $obRProfissao->consultarProfissao();
        if ( !$obErro->ocorreu() ) {
            $obRConselho->setCodigoConselho( $obRProfissao->getCodigoConselho() );
            $obErro = $obRConselho->consultarConselho();
            if ( !$obErro->ocorreu() ) {
                $stJs .= 'f.inCodigoProfissao.value = "'.$obRProfissao->getCodigoProfissao().'";';
                if ($_REQUEST["stAcao"]== "incluir") {
                   $stJs .= 'd.getElementById("stNomeConselhoClasse").innerHTML = "'.$obRConselho->getNomeConselho().'";';
                }
                $stJs .= 'd.getElementById("rotRegistro").innerHTML = "'.$obRConselho->getNomeRegistro().'";';
//                $stJs .= 'd.getElementById("cmbProfissao").selectedIndex = d.getElementById("cmbProfissao").options.value=
            } else {
                $stJs .= 'f.inCodigoProfissao.value = "";';
                $stJs .= 'd.getElementById("stNomeConselhoClasse").innerHTML = "&nbsp;";';
                $stJs .= "alertaAviso('@Valor inválido. (".$_REQUEST["cmbProfisao"].")','form','erro','".Sessao::getId()."');";
            }
        }
        SistemaLegado::executaFrameOculto($stJs);
    break;
    case "buscaCGM":
    if ($_REQUEST["inNumCGM"] != "") {
        $obRCGMPessoaFisica = new RCGMPessoaFisica;
        $obRCGMPessoaFisica->setNumCGM ( $_REQUEST["inNumCGM"] );
        $stWhere = " numcgm = ".$obRCGMPessoaFisica->getNumCGM();
        $null = "&nbsp;";
        $obRCGMPessoaFisica->consultarCGM($rsCgm, $stWhere);
        $inNumLinhas = $rsCgm->getNumLinhas();
        if ($inNumLinhas <= 0) {
            $js .= 'f.inNumCGM.value = "";';
            $js .= 'f.inNumCGM.focus();';
            $js .= 'd.getElementById("inNomCGM").innerHTML = "'.$null.'";';
            $js .= "alertaAviso('@CGM deve ser de Pessoa Fisica. (".$_REQUEST["inNumCGM"].")','form','erro','".Sessao::getId()."');";
        } else {
            $stNomCgm = $rsCgm->getCampo("nom_cgm");
            $js .= 'd.getElementById("inNomCGM").innerHTML = "'.$stNomCgm.'";';
        }
        SistemaLegado::executaFrameOculto($js);
    }
    break;

}

?>
