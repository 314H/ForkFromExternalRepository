<script type="text/javascript">
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
</script>
<?php
/**
    * Página de Oculto do Registro de Evento de Décimo Terceiro
    * Data de Criação: 06/09/2006


    * @author Analista: Vandré Miguel Ramos
    * @author Desenvolvedor: Diego Lemos de Souza

    * @ignore

    $Revision: 30547 $
    $Name$
    $Author: souzadl $
    $Date: 2007-04-16 16:43:24 -0300 (Seg, 16 Abr 2007) $

    * Casos de uso: uc-04.05.21
*/

/*
$Log$
Revision 1.2  2007/04/16 19:43:01  souzadl
Bug #9122#

Revision 1.1  2006/09/08 10:05:36  souzadl
construção

Revision 1.2  2006/08/08 17:42:47  vandre
*/
?>
<script type="text/javascript"> 
function limpaFormularioEventoExtra(){
    f = document.frm;
    f.stCmbDesdobramento.value = '';
}

function salvarOkFiltro(){
    document.frm.stOkRetorno.value = 'filtro';
    document.frm.submit();
}

function salvarOkLista(){
    document.frm.stOkRetorno.value = 'lista';
    document.frm.submit();
}
</script>
