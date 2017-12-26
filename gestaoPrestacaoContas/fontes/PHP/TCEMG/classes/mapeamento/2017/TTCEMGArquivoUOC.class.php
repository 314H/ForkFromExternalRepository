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
    * Classe de mapeamento da tabela tcemg.arquivo_uoc
    * Data de Criação: 19/05/2014
    
    
    * @author Desenvolvedor: Eduardo Paculski Schitz
    
    * @package URBEM
    * @subpackage Mapeamento
    *
    * $Id:  $
*/

include_once( "../../../../../../gestaoAdministrativa/fontes/PHP/framework/include/valida.inc.php" );

class TTCEMGArquivoUOC extends Persistente {
    /**
        * Método Construtor
        * @access Private
    */
    function TTCEMGArquivoUOC()
    {
        parent::Persistente();
        $this->setTabela('tcemg.arquivo_uoc');
        
        $this->setCampoCod('');

        $this->AddCampo('cod_orgao',     'char', true, '2');
        $this->AddCampo('cod_unidade',   'char', true, '10');
        $this->AddCampo('id_fundo',      'char', true, '2');
        $this->AddCampo('desc_unidade',  'char', true, '50');
        $this->AddCampo('e_sub_unidade', 'char', true, '1');
    }
    
    public function __destruct(){}

}
?>