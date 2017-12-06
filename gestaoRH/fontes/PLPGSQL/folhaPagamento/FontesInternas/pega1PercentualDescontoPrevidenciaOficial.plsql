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
/*
* script de funcao PLSQL
* 
* URBEM Soluções de Gestão Pública Ltda
* www.urbem.cnm.org.br

* $Revision: 23095 $
* $Name$
* $Autor: MArcia $
* Date: 2006/04/20 10:50:00 $
*
* Caso de uso: uc-04.05.14
*
* Objetivo: Recebe o codigo da previdencia do buffer e o timestamp 
* (final de competencia que sera comparada com a vigencia e ultimo timestamp
* da referida competencia.
* O valor da base a comparar e passado por parametro
* Para localizar o percentual da previdencia se faz necessario verificar 
* o teto da previdencia e compara-lo com a base informada.
*/



CREATE OR REPLACE FUNCTION pega1PercentualDescontoPrevidenciaOficial(numeric) RETURNS numeric as '

DECLARE
    nuValorBase             ALIAS FOR $1;

    inCodPrevidencia        INTEGER;
    stDataFinalCompetencia  VARCHAR;
    nuValorBaseAux          NUMERIC := 0.00;
    stSql                   VARCHAR := '''';
    reRegistro              RECORD ;
    dtTimestamp             DATE;
    nuTetoBaseDesconto      NUMERIC := 0.00;
    nuPercentualDesconto    NUMERIC := 0.00;
stEntidade VARCHAR := recuperarBufferTexto(''stEntidade'');
 BEGIN


    inCodPrevidencia := recuperarBufferInteiro(''inCodPrevidenciaOficial'');
    stDataFinalCompetencia := recuperarBufferTexto(''stDataFinalCompetencia'');

    nuTetoBaseDesconto := pega1TetoDescPrevidenciaOficial();

    IF nuValorBase > nuTetoBaseDesconto THEN
        nuValorBaseAux := nuTetoBaseDesconto;
    ELSE
        nuValorBaseAux := nuValorBase;
    END if;

    dtTimestamp := to_date(substr( stDataFinalCompetencia ,1,10),''yyyy-mm-dd'');

    stSql := '' SELECT 
                  COALESCE( percentual_desconto,0) as percentual_desconto 

              FROM folhapagamento''||stEntidade||''.previdencia_previdencia as pp

              LEFT OUTER JOIN folhapagamento''||stEntidade||''.faixa_desconto as fd 
                ON fd.timestamp_previdencia  = pp.timestamp
               AND fd.cod_previdencia = pp.cod_previdencia
               AND ''|| nuValorBaseAux ||'' between  fd.valor_inicial AND fd.valor_final

             WHERE pp.cod_previdencia = ''||inCodPrevidencia||''
               AND pp.vigencia  <= ''''''||dtTimesTamp||''''''

              ORDER BY pp.timestamp desc

              LIMIT 1
            '' ;

    FOR reRegistro IN  EXECUTE stSql
    LOOP

       IF reRegistro.percentual_desconto is null  THEN
          nuPercentualDesconto := 0.00;
       ELSE
          nuPercentualDesconto := reRegistro.percentual_desconto;
       END IF;

    END LOOP;

    RETURN nuPercentualDesconto;
END;
' LANGUAGE 'plpgsql';
