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
    * Classe de mapeamento da tabela licitacao.participante
    * Data de Criação: 15/09/2006

    * @author Analista: Gelson W. Gonçalves
    * @author Desenvolvedor: Jean da Silva

    * @package URBEM
    * @subpackage Mapeamento

    $Id: TTCEMGJulgamentoLicitacao.class.php 65930 2016-06-30 19:07:13Z michel $

    * Casos de uso: uc-03.05.18
            uc-03.05.19
*/

include_once '../../../../../../gestaoAdministrativa/fontes/PHP/framework/include/valida.inc.php';
include_once CLA_PERSISTENTE;

class TTCEMGJulgamentoLicitacao extends Persistente
{
/**
    * Método Construtor
    * @access Private
*/

    public function recuperaExportacao10(&$rsRecordSet, $stFiltro = "", $stOrder = "", $boTransacao = "")
    {
        return $this->executaRecupera("montaRecuperaExportacao10", $rsRecordSet, $stFiltro, $stOrder, $boTransacao);
    }

    public function montaRecuperaExportacao10()
    {
        $stSql = "SELECT 10 AS tipo_registro
                       , LPAD(configuracao_entidade.valor,2,'0') AS cod_orgao
                       , LPAD(LPAD(licitacao.num_orgao::VARCHAR, 2, '0') || LPAD(licitacao.num_unidade::VARCHAR, 2, '0'),5,'0') AS cod_unidade
                       , ( SELECT exercicio_licitacao
                             FROM tcemg.fn_exercicio_numero_licitacao ('".$this->getDado('exercicio')."', '".$this->getDado('entidades')."')
                                                               VALUES (cod_licitacao       INTEGER
                                                                      ,cod_modalidade      INTEGER
                                                                      ,cod_entidade        INTEGER
                                                                      ,exercicio           CHAR(4)
                                                                      ,exercicio_licitacao VARCHAR
                                                                      ,num_licitacao       TEXT )
                            WHERE cod_entidade = licitacao.cod_entidade
                              AND cod_licitacao = licitacao.cod_licitacao
                              AND cod_modalidade = licitacao.cod_modalidade
                              AND exercicio = licitacao.exercicio 
                         ) AS exercicio_licitacao
                       , ( SELECT num_licitacao
                             FROM tcemg.fn_exercicio_numero_licitacao ('".$this->getDado('exercicio')."', '".$this->getDado('entidades')."')
                                                               VALUES (cod_licitacao       INTEGER
                                                                      ,cod_modalidade      INTEGER
                                                                      ,cod_entidade        INTEGER
                                                                      ,exercicio           CHAR(4)
                                                                      ,exercicio_licitacao VARCHAR
                                                                      ,num_licitacao       TEXT )
                            WHERE cod_entidade = licitacao.cod_entidade
                              AND cod_licitacao = licitacao.cod_licitacao
                              AND cod_modalidade = licitacao.cod_modalidade
                              AND exercicio = licitacao.exercicio 
                         ) AS num_processo_licitatorio
                       , documento_pessoa.tipo_documento AS tipo_documento
                       , documento_pessoa.num_documento AS num_documento
                       , CASE WHEN mapa.cod_tipo_licitacao = 2
                                   THEN homologacao.lote::VARCHAR
                              ELSE ' '
                         END AS num_lote
                       , mapa_item.cod_item AS cod_item
                       , edital.criterio_adjudicacao
                       , (SUM(mapa_item.vl_total) / SUM(mapa_item.quantidade) )::numeric(14,4) AS vl_unitario
                       , SUM(mapa_item.quantidade)::numeric(14,4) AS quantidade

                    FROM licitacao.licitacao

                    JOIN licitacao.edital
                      ON edital.cod_licitacao = licitacao.cod_licitacao
                     AND edital.cod_modalidade = licitacao.cod_modalidade
                     AND edital.cod_entidade = licitacao.cod_entidade
                     AND edital.exercicio_licitacao = licitacao.exercicio

              INNER JOIN compras.mapa
                      ON mapa.exercicio = licitacao.exercicio_mapa
                     AND mapa.cod_mapa = licitacao.cod_mapa

              INNER JOIN compras.mapa_solicitacao
                      ON mapa_solicitacao.exercicio = mapa.exercicio
                     AND mapa_solicitacao.cod_mapa = mapa.cod_mapa

              INNER JOIN compras.mapa_item
                      ON mapa_item.exercicio = mapa_solicitacao.exercicio
                     AND mapa_item.cod_entidade = mapa_solicitacao.cod_entidade
                     AND mapa_item.cod_solicitacao = mapa_solicitacao.cod_solicitacao
                     AND mapa_item.cod_mapa = mapa_solicitacao.cod_mapa
                     AND mapa_item.exercicio_solicitacao = mapa_solicitacao.exercicio_solicitacao

              INNER JOIN compras.mapa_cotacao
                      ON mapa_cotacao.exercicio_mapa = mapa.exercicio
                     AND mapa_cotacao.cod_mapa = mapa.cod_mapa

              INNER JOIN compras.cotacao
                      ON cotacao.exercicio = mapa_cotacao.exercicio_cotacao
                     AND cotacao.cod_cotacao = mapa_cotacao.cod_cotacao

              INNER JOIN compras.julgamento
                      ON julgamento.exercicio = cotacao.exercicio
                     AND julgamento.cod_cotacao = cotacao.cod_cotacao

              INNER JOIN compras.julgamento_item
                      ON julgamento_item.exercicio = julgamento.exercicio
                     AND julgamento_item.cod_cotacao = julgamento.cod_cotacao
                     AND julgamento_item.cod_item = mapa_item.cod_item 

              INNER JOIN licitacao.homologacao
                      ON homologacao.cod_licitacao=licitacao.cod_licitacao
                     AND homologacao.cod_modalidade=licitacao.cod_modalidade
                     AND homologacao.cod_entidade=licitacao.cod_entidade
                     AND homologacao.exercicio_licitacao=licitacao.exercicio
                     AND homologacao.cod_item=julgamento_item.cod_item
                     AND homologacao.lote=julgamento_item.lote

               LEFT JOIN licitacao.homologacao_anulada
                      ON homologacao_anulada.cod_licitacao=homologacao.cod_licitacao
                     AND homologacao_anulada.cod_modalidade=homologacao.cod_modalidade
                     AND homologacao_anulada.cod_entidade=homologacao.cod_entidade
                     AND homologacao_anulada.exercicio_licitacao=homologacao.exercicio_licitacao
                     AND homologacao_anulada.num_homologacao=homologacao.num_homologacao
                     AND homologacao_anulada.cod_item=homologacao.cod_item
                     AND homologacao_anulada.lote=homologacao.lote

              INNER JOIN administracao.configuracao_entidade
                      ON configuracao_entidade.parametro = 'tcemg_codigo_orgao_entidade_sicom'
                     AND configuracao_entidade.cod_modulo = 55
                     AND configuracao_entidade.exercicio = licitacao.exercicio
                     AND configuracao_entidade.cod_entidade = licitacao.cod_entidade

              INNER JOIN ( SELECT num_documento, numcgm, tipo_documento
                             FROM (
                                    SELECT cpf AS num_documento, numcgm, 1 AS tipo_documento
                                      FROM sw_cgm_pessoa_fisica
                                     UNION
                                    SELECT cnpj AS num_documento, numcgm, 2 AS tipo_documento
                                      FROM sw_cgm_pessoa_juridica
                                  ) AS tabela
                         GROUP BY numcgm, num_documento, tipo_documento
                         ) AS documento_pessoa
                      ON documento_pessoa.numcgm = julgamento_item.cgm_fornecedor

                   WHERE TO_DATE(TO_CHAR(homologacao.timestamp,'dd/mm/yyyy'), 'dd/mm/yyyy') BETWEEN TO_DATE('01/" . $this->getDado('mes') . "/" . $this->getDado('exercicio') . "', 'dd/mm/yyyy')
                     AND last_day(TO_DATE('" . $this->getDado('exercicio') . "' || '-' || '".$this->getDado('mes') . "' || '-' || '01','yyyy-mm-dd'))
                     AND homologacao_anulada.cod_licitacao IS NULL
                     AND licitacao.cod_entidade IN (" . $this->getDado('entidades') . ")
                     AND licitacao.cod_modalidade NOT IN (8,9)
                     AND NOT EXISTS ( SELECT 1
                                        FROM licitacao.licitacao_anulada
                                       WHERE licitacao_anulada.cod_licitacao = licitacao.cod_licitacao
                                         AND licitacao_anulada.cod_modalidade = licitacao.cod_modalidade
                                         AND licitacao_anulada.cod_entidade = licitacao.cod_entidade
                                         AND licitacao_anulada.exercicio = licitacao.exercicio
                                    )

                GROUP BY tipo_registro
                       , cod_orgao
                       , cod_unidade
                       , tipo_documento
                       , num_documento
                       , num_lote
                       , mapa_item.cod_item
                       , edital.criterio_adjudicacao
                       , licitacao.cod_entidade
                       , licitacao.cod_licitacao
                       , licitacao.cod_modalidade
                       , licitacao.exercicio 

                ORDER BY num_processo_licitatorio ";

        return $stSql;
    }

    // detalhamento 20 foi removido porque não tem os filtros necessários ainda.
    /*
    public function recuperaDetalhamento20(&$rsRecordSet,$stFiltro="",$stOrder="",$boTransacao="")
    {
        return $this->executaRecupera("montaRecuperaDetalhamento20",$rsRecordSet,$stFiltro,$stOrder,$boTransacao);
    }

    public function montaRecuperaDetalhamento20()
    {
        $stSql = "
            SELECT
                    20 AS tipo_registro
                  , LPAD(configuracao_entidade.valor,2,'0') AS cod_orgao
                  , LPAD(LPAD(despesa.num_orgao::VARCHAR, 2, '0') || LPAD(despesa.num_unidade::VARCHAR, 2, '0'),5,'0') AS cod_unidade
                  , licitacao.exercicio AS exercicio_licitacao
                  , licitacao.exercicio::VARCHAR || LPAD(licitacao.cod_entidade::VARCHAR,2,'0') || LPAD(licitacao.cod_modalidade::VARCHAR,2,'0') || LPAD(licitacao.cod_licitacao::VARCHAR,4,'0') AS num_processo_licitatorio
                  , documento_pessoa.tipo_documento AS tipo_documento
                  , documento_pessoa.num_documento AS num_documento
                  , mapa_item.lote AS num_lote
                  , mapa_item.cod_item AS cod_item
                  , 0.00 AS perc_desconto
                  
            FROM licitacao.licitacao
                    
            JOIN licitacao.participante
              ON participante.cod_licitacao = licitacao.cod_licitacao
             AND participante.cod_modalidade = licitacao.cod_modalidade
             AND participante.cod_entidade = licitacao.cod_entidade
             AND participante.exercicio = licitacao.exercicio
            
            JOIN compras.mapa
              ON mapa.exercicio = licitacao.exercicio_mapa
             AND mapa.cod_mapa = licitacao.cod_mapa
             
            JOIN compras.mapa_solicitacao
              ON mapa_solicitacao.exercicio = mapa.exercicio
             AND mapa_solicitacao.cod_mapa = mapa.cod_mapa
             
            JOIN compras.mapa_item
              ON mapa_item.exercicio = mapa_solicitacao.exercicio
             AND mapa_item.cod_entidade = mapa_solicitacao.cod_entidade
             AND mapa_item.cod_solicitacao = mapa_solicitacao.cod_solicitacao
             AND mapa_item.cod_mapa = mapa_solicitacao.cod_mapa
             AND mapa_item.exercicio_solicitacao = mapa_solicitacao.exercicio_solicitacao
            
            JOIN compras.mapa_cotacao
              ON mapa_cotacao.exercicio_mapa = mapa.exercicio
             AND mapa_cotacao.cod_mapa = mapa.cod_mapa
             
            JOIN compras.cotacao
              ON cotacao.exercicio = mapa_cotacao.exercicio_cotacao
             AND cotacao.cod_cotacao = mapa_cotacao.cod_cotacao
            
            JOIN compras.julgamento
              ON julgamento.exercicio = cotacao.exercicio
             AND julgamento.cod_cotacao = cotacao.cod_cotacao
             
            JOIN compras.mapa_item_dotacao
              ON mapa_item_dotacao.exercicio = mapa_item.exercicio
             AND mapa_item_dotacao.cod_entidade = mapa_item.cod_entidade
             AND mapa_item_dotacao.cod_solicitacao = mapa_item.cod_solicitacao
             AND mapa_item_dotacao.cod_mapa = mapa_item.cod_mapa
             AND mapa_item_dotacao.cod_centro = mapa_item.cod_centro
             AND mapa_item_dotacao.cod_item = mapa_item.cod_item
             AND mapa_item_dotacao.lote = mapa_item.lote
             AND mapa_item_dotacao.exercicio_solicitacao = mapa_item.exercicio_solicitacao
             AND mapa_item_dotacao.cod_entidade = mapa_item.cod_entidade
             
            JOIN compras.solicitacao_item_dotacao
              ON solicitacao_item_dotacao.exercicio = mapa_item_dotacao.exercicio_solicitacao
             AND solicitacao_item_dotacao.cod_entidade = mapa_item_dotacao.cod_entidade
             AND solicitacao_item_dotacao.cod_solicitacao = mapa_item_dotacao.cod_solicitacao
             AND solicitacao_item_dotacao.cod_centro = mapa_item_dotacao.cod_centro
             AND solicitacao_item_dotacao.cod_item = mapa_item_dotacao.cod_item
             AND solicitacao_item_dotacao.cod_conta = mapa_item_dotacao.cod_conta
             AND solicitacao_item_dotacao.cod_despesa = mapa_item_dotacao.cod_despesa
             
            JOIN orcamento.despesa
              ON despesa.exercicio = solicitacao_item_dotacao.exercicio
             AND despesa.cod_despesa = solicitacao_item_dotacao.cod_despesa
             
            JOIN administracao.configuracao_entidade
              ON configuracao_entidade.parametro = 'tcemg_codigo_orgao_entidade_sicom'
             AND configuracao_entidade.cod_modulo = 55
             AND configuracao_entidade.exercicio = despesa.exercicio
             AND configuracao_entidade.cod_entidade = despesa.cod_entidade
             
            JOIN sw_cgm AS responsavel
              ON responsavel.numcgm = participante.numcgm_representante
              
            JOIN ( SELECT num_documento, numcgm, tipo_documento
                     FROM (
                            SELECT cpf AS num_documento, numcgm, 1 AS tipo_documento
                              FROM sw_cgm_pessoa_fisica
                              
                             UNION
                             
                            SELECT cnpj AS num_documento, numcgm, 2 AS tipo_documento
                              FROM sw_cgm_pessoa_juridica
                        ) AS tabela
                    GROUP BY numcgm, num_documento, tipo_documento
                ) AS documento_pessoa
              ON documento_pessoa.numcgm = responsavel.numcgm
              
            WHERE TO_DATE(TO_CHAR(licitacao.timestamp,'dd/mm/yyyy'), 'dd/mm/yyyy') BETWEEN TO_DATE('01/" . $this->getDado('mes') . "/" . $this->getDado('exercicio') . "', 'dd/mm/yyyy')
              AND last_day(TO_DATE('" . $this->getDado('exercicio') . "' || '-' || '".$this->getDado('mes') . "' || '-' || '01','yyyy-mm-dd'))
              AND licitacao.exercicio = '" . $this->getDado('exercicio') . "'
              AND licitacao.cod_entidade IN (" . $this->getDado('entidades') . ")
              
            GROUP BY tipo_registro, cod_orgao, cod_unidade, exercicio_licitacao, num_processo_licitatorio, tipo_documento, num_documento, num_lote, mapa_item.cod_item, mapa_item.vl_total, mapa_item.quantidade
        ";
    }
    */

    public function recuperaExportacao30(&$rsRecordSet,$stFiltro="",$stOrder="",$boTransacao="")
    {
        return $this->executaRecupera("montaRecuperaExportacao30",$rsRecordSet,$stFiltro,$stOrder,$boTransacao);
    }

    public function montaRecuperaExportacao30()
    {
      return "
          SELECT  DISTINCT
                  '30'::char(2) AS tiporegistro,
                  LPAD(( SELECT valor
                          FROM administracao.configuracao_entidade
                         WHERE exercicio = '2017'
                           AND parametro LIKE 'tcemg_codigo_orgao_entidade_sicom'
                           AND cod_entidade = licitacao.cod_entidade
                  ), 2, '0') AS cod_orgao,
                  CASE
                      WHEN homologacao.exercicio_licitacao <= '2013'
                      THEN ''
                      ELSE LPAD(LPAD(licitacao.num_orgao::VARCHAR, 2, '0') || LPAD(licitacao.num_unidade::VARCHAR, 2, '0'), 5, '0')
                  END AS cod_unidadesub,
                  config_licitacao.exercicio_licitacao,
                  config_licitacao.num_licitacao AS nro_processolicitatorio,
                  CASE WHEN sw_cgm_pessoa_juridica.cnpj IS NOT NULL THEN 2
                       WHEN sw_cgm_pessoa_fisica.cpf  IS NOT NULL THEN 1
                       ELSE 3
                  END AS tipo_documento,
                  CASE WHEN sw_cgm_pessoa_juridica.cnpj IS NOT NULL
                       THEN sw_cgm_pessoa_juridica.cnpj
                       ELSE sw_cgm_pessoa_fisica.cpf
                  END AS nro_documento,
                  CASE WHEN mapa.cod_tipo_licitacao = 2
                       THEN homologacao.lote
                       ELSE NULL
                  END AS nro_lote,
                  homologacao.cod_item,
                  COALESCE(edital.percentual_taxa_administracao, 0) as percentual_taxa_administracao

            FROM  licitacao.homologacao

            JOIN  licitacao.adjudicacao 
              ON  adjudicacao.num_adjudicacao = homologacao.num_adjudicacao
             AND  adjudicacao.cod_entidade = homologacao.cod_entidade
             AND  adjudicacao.cod_modalidade = homologacao.cod_modalidade
             AND  adjudicacao.cod_licitacao= homologacao.cod_licitacao
             AND  adjudicacao.exercicio_licitacao = homologacao.exercicio_licitacao
             AND  adjudicacao.cod_item = homologacao.cod_item
             AND  adjudicacao.cod_cotacao = homologacao.cod_cotacao
             AND  adjudicacao.lote = homologacao.lote
             AND  adjudicacao.exercicio_cotacao = homologacao.exercicio_cotacao
             AND  adjudicacao.cgm_fornecedor = homologacao.cgm_fornecedor

            JOIN  licitacao.cotacao_licitacao 
              ON  cotacao_licitacao.cod_licitacao = adjudicacao.cod_licitacao
             AND  cotacao_licitacao.cod_modalidade = adjudicacao.cod_modalidade
             AND  cotacao_licitacao.cod_entidade = adjudicacao.cod_entidade
             AND  cotacao_licitacao.exercicio_licitacao = adjudicacao.exercicio_licitacao
             AND  cotacao_licitacao.lote = adjudicacao.lote
             AND  cotacao_licitacao.cod_cotacao = adjudicacao.cod_cotacao
             AND  cotacao_licitacao.cod_item = adjudicacao.cod_item
             AND  cotacao_licitacao.exercicio_cotacao = adjudicacao.exercicio_cotacao
             AND  cotacao_licitacao.cgm_fornecedor = adjudicacao.cgm_fornecedor

            JOIN  licitacao.licitacao 
              ON  licitacao.cod_licitacao  = cotacao_licitacao.cod_licitacao
             AND  licitacao.cod_modalidade = cotacao_licitacao.cod_modalidade
             AND  licitacao.cod_entidade = cotacao_licitacao.cod_entidade
             AND  licitacao.exercicio = cotacao_licitacao.exercicio_licitacao

            JOIN  licitacao.edital
              ON  edital.cod_licitacao = licitacao.cod_licitacao
             AND  edital.cod_modalidade = licitacao.cod_modalidade
             AND  edital.cod_entidade = licitacao.cod_entidade
             AND  edital.exercicio_licitacao = licitacao.exercicio

            JOIN  licitacao.licitacao_documentos
              ON  licitacao_documentos.cod_licitacao = licitacao.cod_licitacao
             AND  licitacao_documentos.cod_modalidade = licitacao.cod_modalidade
             AND  licitacao_documentos.cod_entidade = licitacao.cod_entidade
             AND  licitacao_documentos.exercicio = licitacao.exercicio

            JOIN  licitacao.participante 
              ON  participante.cod_licitacao = licitacao.cod_licitacao
             AND  participante.cod_modalidade = licitacao.cod_modalidade
             AND  participante.cod_entidade = licitacao.cod_entidade
             AND  participante.exercicio = licitacao.exercicio

            JOIN  compras.cotacao_fornecedor_item
              ON  cotacao_fornecedor_item.cgm_fornecedor = cotacao_licitacao.cgm_fornecedor
             AND  cotacao_fornecedor_item.cod_cotacao = cotacao_licitacao.cod_cotacao
             AND  cotacao_fornecedor_item.exercicio = cotacao_licitacao.exercicio_cotacao
             AND  cotacao_fornecedor_item.lote = cotacao_licitacao.lote
             AND  cotacao_fornecedor_item.cod_item = cotacao_licitacao.cod_item

            JOIN  compras.cotacao_item
              ON  cotacao_item.exercicio = cotacao_fornecedor_item.exercicio
             AND  cotacao_item.cod_cotacao = cotacao_fornecedor_item.cod_cotacao
             AND  cotacao_item.cod_item = cotacao_fornecedor_item.cod_item
             AND  cotacao_item.lote = cotacao_fornecedor_item.lote

            JOIN  compras.cotacao
              ON  cotacao.exercicio = cotacao_item.exercicio
             AND  cotacao.cod_cotacao = cotacao_item.cod_cotacao

            JOIN  compras.mapa_cotacao
              ON  mapa_cotacao.exercicio_cotacao = cotacao.exercicio
             AND  mapa_cotacao.cod_cotacao = cotacao.cod_cotacao

            JOIN  compras.julgamento_item
              ON  cotacao_fornecedor_item.exercicio = julgamento_item.exercicio
             AND  cotacao_fornecedor_item.cod_cotacao = julgamento_item.cod_cotacao
             AND  cotacao_fornecedor_item.cod_item = julgamento_item.cod_item
             AND  cotacao_fornecedor_item.cgm_fornecedor = julgamento_item.cgm_fornecedor
             AND  cotacao_fornecedor_item.lote = julgamento_item.lote

            LEFT  JOIN sw_cgm_pessoa_juridica
              ON  sw_cgm_pessoa_juridica.numcgm = participante.cgm_fornecedor

            LEFT  JOIN sw_cgm_pessoa_fisica
              ON  sw_cgm_pessoa_fisica.numcgm = participante.cgm_fornecedor

            JOIN  compras.fornecedor 
              ON  fornecedor.cgm_fornecedor = participante.cgm_fornecedor
                       
            JOIN  compras.mapa
              ON  mapa.exercicio = mapa_cotacao.exercicio_mapa
             AND  mapa.cod_mapa  = mapa_cotacao.cod_mapa

            JOIN  compras.mapa_item
              ON  mapa_item.cod_mapa = mapa_cotacao.cod_mapa
             AND  mapa_item.exercicio = mapa_cotacao.exercicio_mapa
             AND  mapa_item.cod_item = cotacao_fornecedor_item.cod_item
             AND  mapa_item.lote = cotacao_fornecedor_item.lote

            LEFT  JOIN compras.mapa_item_anulacao
              ON  mapa_item.exercicio = mapa_item_anulacao.exercicio
             AND  mapa_item.exercicio_solicitacao = mapa_item_anulacao.exercicio_solicitacao
             AND  mapa_item.cod_mapa = mapa_item_anulacao.cod_mapa
             AND  mapa_item.cod_entidade = mapa_item_anulacao.cod_entidade
             AND  mapa_item.cod_solicitacao = mapa_item_anulacao.cod_solicitacao
             AND  mapa_item.cod_centro = mapa_item_anulacao.cod_centro
             AND  mapa_item.lote = mapa_item_anulacao.lote
             AND  mapa_item.cod_item = mapa_item_anulacao.cod_item

            JOIN  (
                  SELECT  *
                    FROM  tcemg.fn_exercicio_numero_licitacao (
                      '".$this->getDado('exercicio')."',
                      '".$this->getDado('entidades')."'
                    )
                  VALUES  (
                            cod_licitacao INTEGER,
                            cod_modalidade INTEGER,
                            cod_entidade INTEGER,
                            exercicio CHAR(4),
                            exercicio_licitacao VARCHAR,
                            num_licitacao TEXT
                          )
                  )  AS config_licitacao 
              ON  config_licitacao.cod_entidade = licitacao.cod_entidade
             AND  config_licitacao.cod_licitacao = licitacao.cod_licitacao
             AND  config_licitacao.cod_modalidade = licitacao.cod_modalidade
             AND  config_licitacao.exercicio = licitacao.exercicio

            LEFT  JOIN licitacao.licitacao_anulada
              ON  licitacao_anulada.cod_licitacao = licitacao.cod_licitacao
             AND  licitacao_anulada.cod_modalidade = licitacao.cod_modalidade
             AND  licitacao_anulada.cod_entidade = licitacao.cod_entidade
             AND  licitacao_anulada.exercicio = licitacao.exercicio
             
           WHERE  licitacao.cod_entidade IN (".$this->getDado('entidades').")
             AND  licitacao_anulada.cod_licitacao IS NULL
             AND  TO_DATE(homologacao.timestamp::varchar, 'YYYY-MM-DD') 
                  BETWEEN TO_DATE('".$this->getDado('dt_inicial')."', 'dd/mm/yyyy') 
             AND  TO_DATE('".$this->getDado('dt_final')."', 'dd/mm/yyyy')
             AND  licitacao.cod_modalidade NOT IN (8,9)
      ";
    }

    public function recuperaExportacao40(&$rsRecordSet,$stFiltro="",$stOrder="",$boTransacao="")
    {
        return $this->executaRecupera("montaRecuperaExportacao40",$rsRecordSet,$stFiltro,$stOrder,$boTransacao);
    }

    public function montaRecuperaExportacao40()
    {
        $stSql = "
          SELECT 40 AS tipo_registro
               , LPAD(configuracao_entidade.valor,2,'0') AS cod_orgao
               , LPAD(LPAD(licitacao.num_orgao::VARCHAR, 2, '0') || LPAD(licitacao.num_unidade::VARCHAR, 2, '0'),5,'0') AS cod_unidade
               , (SELECT exercicio_licitacao
                    FROM tcemg.fn_exercicio_numero_licitacao ('".$this->getDado('exercicio')."', '".$this->getDado('entidades')."')
                                                      VALUES (cod_licitacao       INTEGER
                                                             ,cod_modalidade      INTEGER
                                                             ,cod_entidade        INTEGER
                                                             ,exercicio           CHAR(4)
                                                             ,exercicio_licitacao VARCHAR
                                                             ,num_licitacao       TEXT ) 	    
                   WHERE cod_entidade = licitacao.cod_entidade
                     AND cod_licitacao = licitacao.cod_licitacao
                     AND cod_modalidade = licitacao.cod_modalidade
                     AND exercicio = licitacao.exercicio 
                 ) AS exercicio_licitacao
               , (SELECT num_licitacao
                    FROM tcemg.fn_exercicio_numero_licitacao ('".$this->getDado('exercicio')."', '".$this->getDado('entidades')."')
                                                      VALUES (cod_licitacao       INTEGER
                                                             ,cod_modalidade      INTEGER
                                                             ,cod_entidade        INTEGER
                                                             ,exercicio           CHAR(4)
                                                             ,exercicio_licitacao VARCHAR
                                                             ,num_licitacao       TEXT ) 	    
                   WHERE cod_entidade = licitacao.cod_entidade
                     AND cod_licitacao = licitacao.cod_licitacao
                     AND cod_modalidade = licitacao.cod_modalidade
                     AND exercicio = licitacao.exercicio 
                 ) AS num_processo_licitatorio
               , TO_CHAR(julgamento.timestamp,'ddmmyyyy') AS dt_julgamento
               , 1 AS presenca_licitantes
               , CASE WHEN participante.renuncia_recurso = true THEN
                                    1
                         ELSE
                                    2
                    END AS renuncia_recurso 

            FROM licitacao.licitacao

      INNER JOIN licitacao.participante
              ON participante.cod_licitacao = licitacao.cod_licitacao
             AND participante.cod_modalidade = licitacao.cod_modalidade
             AND participante.cod_entidade = licitacao.cod_entidade
             AND participante.exercicio = licitacao.exercicio

      INNER JOIN compras.mapa
              ON mapa.exercicio = licitacao.exercicio_mapa
             AND mapa.cod_mapa = licitacao.cod_mapa

      INNER JOIN compras.mapa_solicitacao
              ON mapa_solicitacao.exercicio = mapa.exercicio
             AND mapa_solicitacao.cod_mapa = mapa.cod_mapa

      INNER JOIN compras.mapa_item
              ON mapa_item.exercicio = mapa_solicitacao.exercicio
             AND mapa_item.cod_entidade = mapa_solicitacao.cod_entidade
             AND mapa_item.cod_solicitacao = mapa_solicitacao.cod_solicitacao
             AND mapa_item.cod_mapa = mapa_solicitacao.cod_mapa
             AND mapa_item.exercicio_solicitacao = mapa_solicitacao.exercicio_solicitacao

      INNER JOIN compras.mapa_cotacao
              ON mapa_cotacao.exercicio_mapa = mapa.exercicio
             AND mapa_cotacao.cod_mapa = mapa.cod_mapa

      INNER JOIN compras.cotacao
              ON cotacao.exercicio = mapa_cotacao.exercicio_cotacao
             AND cotacao.cod_cotacao = mapa_cotacao.cod_cotacao

      INNER JOIN compras.julgamento
              ON julgamento.exercicio = cotacao.exercicio
             AND julgamento.cod_cotacao = cotacao.cod_cotacao

      INNER JOIN compras.julgamento_item
              ON julgamento_item.exercicio = julgamento.exercicio
             AND julgamento_item.cod_cotacao = julgamento.cod_cotacao
             AND julgamento_item.cod_item = mapa_item.cod_item                      

      INNER JOIN licitacao.homologacao
              ON homologacao.cod_licitacao=licitacao.cod_licitacao
             AND homologacao.cod_modalidade=licitacao.cod_modalidade
             AND homologacao.cod_entidade=licitacao.cod_entidade
             AND homologacao.exercicio_licitacao=licitacao.exercicio
             AND homologacao.cod_item=julgamento_item.cod_item
             AND homologacao.lote=julgamento_item.lote

       LEFT JOIN licitacao.homologacao_anulada
              ON homologacao_anulada.cod_licitacao=homologacao.cod_licitacao
             AND homologacao_anulada.cod_modalidade=homologacao.cod_modalidade
             AND homologacao_anulada.cod_entidade=homologacao.cod_entidade
             AND homologacao_anulada.exercicio_licitacao=homologacao.exercicio_licitacao
             AND homologacao_anulada.num_homologacao=homologacao.num_homologacao
             AND homologacao_anulada.cod_item=homologacao.cod_item
             AND homologacao_anulada.lote=homologacao.lote

      INNER JOIN administracao.configuracao_entidade
              ON configuracao_entidade.parametro = 'tcemg_codigo_orgao_entidade_sicom'
             AND configuracao_entidade.cod_modulo = 55
             AND configuracao_entidade.exercicio = licitacao.exercicio
             AND configuracao_entidade.cod_entidade = licitacao.cod_entidade

      INNER JOIN sw_cgm AS responsavel
              ON responsavel.numcgm = participante.numcgm_representante

      INNER JOIN ( SELECT num_documento, numcgm, tipo_documento
                     FROM (
                            SELECT cpf AS num_documento, numcgm, 1 AS tipo_documento
                              FROM sw_cgm_pessoa_fisica
                             UNION
                            SELECT cnpj AS num_documento, numcgm, 2 AS tipo_documento
                              FROM sw_cgm_pessoa_juridica
                        ) AS tabela
                    GROUP BY numcgm, num_documento, tipo_documento
                 ) AS documento_pessoa
              ON documento_pessoa.numcgm = responsavel.numcgm

           WHERE TO_DATE(TO_CHAR(homologacao.timestamp,'dd/mm/yyyy'), 'dd/mm/yyyy') BETWEEN TO_DATE('01/" . $this->getDado('mes') . "/" . $this->getDado('exercicio') . "', 'dd/mm/yyyy')
             AND last_day(TO_DATE('" . $this->getDado('exercicio') . "' || '-' || '".$this->getDado('mes') . "' || '-' || '01','yyyy-mm-dd'))
             AND homologacao_anulada.cod_licitacao IS NULL
             AND licitacao.cod_entidade IN (" . $this->getDado('entidades'). ")
             AND licitacao.cod_modalidade NOT IN (8,9)
             AND NOT EXISTS( SELECT 1
                               FROM licitacao.licitacao_anulada
                              WHERE licitacao_anulada.cod_licitacao = licitacao.cod_licitacao
                                AND licitacao_anulada.cod_modalidade = licitacao.cod_modalidade
                                AND licitacao_anulada.cod_entidade = licitacao.cod_entidade
                                AND licitacao_anulada.exercicio = licitacao.exercicio
                           )

        GROUP BY tipo_registro
               , cod_orgao
               , cod_unidade
               , dt_julgamento, presenca_licitantes
               , renuncia_recurso
               , licitacao.cod_entidade
               , licitacao.cod_licitacao
               , licitacao.cod_modalidade
               , licitacao.exercicio

        ORDER BY num_processo_licitatorio ";

        return $stSql;
    }

    public function __destruct(){}
}