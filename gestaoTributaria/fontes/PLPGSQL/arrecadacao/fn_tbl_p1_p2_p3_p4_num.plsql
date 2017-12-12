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
*
* URBEM Soluções de Gestão Pública Ltda
* www.urbem.cnm.org.br
*
* $Id: fn_tbl_p1_p2_p3_p4_num.plsql 59612 2014-09-02 12:00:51Z gelson $
*
* Caso de uso: uc-5.3.5
* Caso de uso: uc-05.03.05
*/

/*
$Log$
Revision 1.1  2007/09/28 20:58:33  fabio
funcao para recuperar valores de tabela de conversao, considerando intervalos entre par1 e par2, e entre par3 e par4


*/

CREATE OR REPLACE FUNCTION arrecadacao.fn_tbl_p1_p2_p3_p4_num(inCodTabela INTEGER, inExercicio INTEGER, stValor1 VARCHAR, stValor2 VARCHAR)  RETURNS VARCHAR AS $$
DECLARE
    stResultado             VARCHAR;
    boLog                   BOOLEAN;
BEGIN
    SELECT
        b.valor
    INTO
        stResultado
    FROM
        arrecadacao.tabela_conversao_valores b
    WHERE
        b.cod_tabela = inCodTabela      AND
        b.exercicio  = inExercicio      AND
        stValor1::numeric BETWEEN b.parametro_1::numeric AND b.parametro_2::numeric    AND
        stValor2::numeric BETWEEN b.parametro_3::numeric AND b.parametro_4::numeric;


    IF stResultado = 0 OR stResultado IS NULL THEN
        stResultado := 0;
    END IF;
    RETURN stResultado;
END;
$$ LANGUAGE 'plpgsql';
