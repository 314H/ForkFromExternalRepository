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
    * Classe de mapeamento da tabela contabilidade.plano_conta_encerrada
    * Data de Criação: 30/09/2014
    * @author Desenvolvedor: Evandro Melos
*/

include_once '../../../../../../gestaoAdministrativa/fontes/PHP/framework/include/valida.inc.php';
include_once ( CLA_PERSISTENTE );

class TContabilidadePlanoContaEncerrada extends Persistente
{
    /**
        * Método Construtor
        * @access Private
    */
    public function TContabilidadePlanoContaEncerrada()
    {
        parent::Persistente();
        $this->setTabela('contabilidade.plano_conta_encerrada');

        $this->setCampoCod('');
        $this->setComplementoChave('cod_conta, exercicio');

        $this->AddCampo('cod_conta'      ,'integer',true,''  ,true ,true  );
        $this->AddCampo('exercicio'      ,'char'   ,true,'04',true ,true  );
        $this->AddCampo('dt_encerramento','date'   ,true,''  ,false,false );
        $this->AddCampo('motivo'         ,'text'   ,true,''  ,false,false );

    }

}

?>