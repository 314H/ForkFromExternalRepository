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
* Script de função PLPGSQL
*
* URBEM Soluções de Gestão Pública Ltda
* www.urbem.cnm.org.br
*
* $Id: fn_proprietarios_imovel.plsql 59612 2014-09-02 12:00:51Z gelson $
*
* Casos de uso: uc-05.01.12
*/

/*
$Log$
Revision 1.3  2006/09/15 10:19:51  fabio
correção do cabeçalho,
adicionado trecho de log do CVS

*/

CREATE OR REPLACE FUNCTION imobiliario.fn_proprietarios_imovel( INTEGER ) RETURNS SETOF RECORD AS '
DECLARE
    inInscricaoMunicipal ALIAS FOR $1;
    inCgmProp            INTEGER;
    reRecord            RECORD;
    stSql               VARCHAR;
BEGIN
    stSql := ''SELECT numcgm FROM imobiliario.proprietario where inscricao_municipal = ''||inInscricaoMunicipal;

    FOR reRecord IN EXECUTE stSql LOOP
        return next reRecord;
    END LOOP;
    return;
END;
'language 'plpgsql';
