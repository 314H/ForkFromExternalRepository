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
/**
    * Titulo do arquivo PL que retorna o num_orgao de acordo com o órgão passado
    * Data de Criação   : 29/12/2008


    * @author Analista      Tonismar Régis Bernardo
    * @author Desenvolvedor Eduardo Paculski Schitz
    
    * @package URBEM
    * @subpackage 

    $Id:$
 */

CREATE OR REPLACE FUNCTION orcamento.recuperaNumOrgao(INTEGER, VARCHAR) RETURNS INTEGER AS $$
DECLARE
    inCodOrgao          ALIAS FOR $1;
    stExercicio         ALIAS FOR $2;
    stSql               VARCHAR   := '';
    stMascara           VARCHAR   := '';
    stMascaraOrgao      VARCHAR   := '';
    arValor             VARCHAR[];
    inCodOrganograma    INTEGER;
    inCodNivel          INTEGER;
    inNumOrgao          INTEGER;
    inCodOrgaoRetorno   INTEGER;
    inIndex             INTEGER := 0;
BEGIN

-- Verifica qual o organograma do nivel passado
    SELECT cod_organograma 
      INTO inCodOrganograma
      FROM organograma.orgao_nivel 
     WHERE cod_orgao = inCodOrgao LIMIT 1;


-- Verifica qual é o nível do órgão no organograma
    SELECT cod_nivel
      INTO inCodNivel
      FROM orcamento.organograma_nivel 
     WHERE cod_organograma = inCodOrganograma
       AND tipo = 'O' 
       AND "timestamp" = ( SELECT MAX("timestamp") 
                             FROM orcamento.organograma_nivel 
                            WHERE cod_organograma = inCodOrganograma
                         );

    SELECT publico.fn_mascarareduzida(organograma.fn_consulta_orgao(inCodOrganograma, inCodOrgao)) 
      INTO stMascara;

    arValor := string_to_array(stMascara, '.');
    
    WHILE (inIndex<>inCodNivel) LOOP
        inIndex := inIndex + 1;
        stMascaraOrgao := stMascaraOrgao||'.'||arValor[inIndex];
    END LOOP;
    
    IF (length(stMascaraOrgao) > 0) THEN
        stMascaraOrgao := substring(stMascaraOrgao, 2, length(stMascaraOrgao));
    END IF;

    SELECT cod_orgao
      INTO inCodOrgaoRetorno
      FROM organograma.vw_orgao_nivel
     WHERE cod_organograma = inCodOrganograma
       AND orgao ilike stMascaraOrgao||'%';

    SELECT num_orgao
      INTO inNumOrgao
      FROM orcamento.orgao
     WHERE num_orgao = inCodOrgaoRetorno
       AND exercicio = stExercicio;

    RETURN inNumOrgao;
END;
$$ LANGUAGE plpgsql