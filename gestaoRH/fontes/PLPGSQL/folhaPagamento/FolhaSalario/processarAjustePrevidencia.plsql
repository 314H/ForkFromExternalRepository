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
--/**
--    * Função PLSQL
--    * Data de Criação: 15/03/2006
--
--
--    * @author Analista: Vandré Miguel Ramos
--    * @author Desenvolvedor: Diego Lemos de Souza
--
--    * @package URBEM
--    * @subpackage
--
--    $Revision: 29014 $
--    $Name$
--    $Author: souzadl $
--    $Date: 2008-04-04 16:10:59 -0300 (Sex, 04 Abr 2008) $
--
--    * Casos de uso: uc-04.05.09
--*/

CREATE OR REPLACE FUNCTION processarAjustePrevidencia(INTEGER) RETURNS BOOLEAN as $$
DECLARE
    inCodContrato               ALIAS FOR $1;
    stSql                       VARCHAR := '';
    reRegistro                  RECORD;
    reBases                     RECORD;
    reFaixaDesconto             RECORD;
    reDescontoExterno           RECORD;    
    boRetorno                   BOOLEAN := TRUE;
    stCodigoEvento              VARCHAR := '';
    stNatureza                  VARCHAR := '';
    dtVigencia                  VARCHAR := '';
    stTimestamp                 VARCHAR := '';
    stSituacaoFolhaSalario      VARCHAR := '';
    stTimestampRegistro         TIMESTAMP;
    stTimestampDesconto         TIMESTAMP;
    inCodRegistro               INTEGER;
    inCodRegime                 INTEGER;
    inCodSubDivisao             INTEGER;
    inCodFuncao                 INTEGER;
    inCodEspecialidade          INTEGER;
    inCodEvento                 INTEGER;
    inCodPeriodoMovimentacao    INTEGER;
    inCodComplementar           INTEGER;
    inCodConfiguracao           INTEGER;
    inCodPrevidencia            INTEGER;
    inIndex                     INTEGER;
    inNumCgm                    INTEGER;
    inCodRegistroDesconto       INTEGER;
    inCodEventoDesconto         INTEGER;
    inCodConfiguracaoDesconto   INTEGER;
    inCountFolhaComplementar    INTEGER;
    inCountFolhaSalario         INTEGER;
    inCountFolhaFerias          INTEGER;
    inCountFolhaRescisao        INTEGER;
    nuValor                     NUMERIC := 0.00;
    nuTotalDescontoCalculo      NUMERIC := 0.00;
    nuPercentualDesconto        NUMERIC := 0.00;
    nuSomaBase                  NUMERIC := 0.00;
    nuSomaDesconto              NUMERIC := 0.00;
    nuSomaDescontoExterno       NUMERIC := 0.00;
    crCursor                    REFCURSOR;
    stEntidade VARCHAR := recuperarBufferTexto('stEntidade');
BEGIN
    --BUSCA CÓDIGO E TIMESTAMP DA PREVIDENCIA
    --PARA BUSCAR OS EVENTOS VINCULADOS A ESSA PREVIDENCIA
    inCodPrevidencia := recuperarBufferInteiro('inCodPrevidenciaOficial');
    IF inCodPrevidencia IS NOT NULL THEN
        stTimestamp      := pega1TimestampTabelaPrevidencia();
        dtVigencia       := recuperarBufferTexto('dtVigenciaPrevidencia');    
        inNumCgm                    := recuperarBufferInteiro('inNumCgm');
        inCodPeriodoMovimentacao    := recuperarBufferInteiro('inCodPeriodoMovimentacao');
        inCountFolhaComplementar := selectIntoInteger('SELECT count(*)
                                                FROM (SELECT registro_evento_complementar.cod_contrato
                                                        , registro_evento_complementar.cod_complementar
                                                        FROM folhapagamento'||stEntidade||'.registro_evento_complementar
                                                        , folhapagamento'||stEntidade||'.ultimo_registro_evento_complementar
                                                        , folhapagamento'||stEntidade||'.evento_complementar_calculado
                                                        , pessoal'||stEntidade||'.servidor_contrato_servidor
                                                        , pessoal'||stEntidade||'.servidor
                                                        , folhapagamento'||stEntidade||'.complementar_situacao
                                                        , (SELECT cod_periodo_movimentacao
                                                                , cod_complementar
                                                                ,  max(timestamp) as timestamp
                                                                FROM folhapagamento'||stEntidade||'.complementar_situacao
                                                            GROUP BY cod_periodo_movimentacao
                                                                    , cod_complementar) as max_complementar_situacao
                                                    WHERE registro_evento_complementar.cod_registro     = ultimo_registro_evento_complementar.cod_registro
                                                        AND registro_evento_complementar.timestamp        = ultimo_registro_evento_complementar.timestamp
                                                        AND registro_evento_complementar.cod_evento       = ultimo_registro_evento_complementar.cod_evento
                                                        AND registro_evento_complementar.cod_configuracao = ultimo_registro_evento_complementar.cod_configuracao
                                                        AND registro_evento_complementar.cod_registro     = evento_complementar_calculado.cod_registro
                                                        AND registro_evento_complementar.timestamp        = evento_complementar_calculado.timestamp_registro
                                                        AND registro_evento_complementar.cod_evento       = evento_complementar_calculado.cod_evento
                                                        AND registro_evento_complementar.cod_configuracao = evento_complementar_calculado.cod_configuracao
                                                        AND registro_evento_complementar.cod_contrato = servidor_contrato_servidor.cod_contrato
                                                        AND servidor_contrato_servidor.cod_servidor = servidor.cod_servidor
                                                        AND registro_evento_complementar.cod_complementar = complementar_situacao.cod_complementar
                                                        AND registro_evento_complementar.cod_periodo_movimentacao = complementar_situacao.cod_periodo_movimentacao
                                                        AND complementar_situacao.cod_complementar = max_complementar_situacao.cod_complementar
                                                        AND complementar_situacao.cod_periodo_movimentacao = max_complementar_situacao.cod_periodo_movimentacao
                                                        AND complementar_situacao.timestamp = max_complementar_situacao.timestamp
                                                        AND complementar_situacao.situacao = ''f''
                                                        AND servidor.numcgm = '||inNumCgm||'
                                                        AND registro_evento_complementar.cod_periodo_movimentacao = '||inCodPeriodoMovimentacao||'
                                                        AND registro_evento_complementar.cod_configuracao = 1
                                                        AND EXISTS (SELECT 1
                                                                    FROM pessoal'||stEntidade||'.contrato_servidor_previdencia
                                                                        , (  SELECT cod_contrato
                                                                                    , cod_previdencia
                                                                                    , max(timestamp) as timestamp
                                                                                FROM pessoal'||stEntidade||'.contrato_servidor_previdencia
                                                                            GROUP BY cod_contrato  
                                                                                    , cod_previdencia) as max_contrato_servidor_previdencia
                                                                    WHERE contrato_servidor_previdencia.cod_contrato = max_contrato_servidor_previdencia.cod_contrato
                                                                        AND contrato_servidor_previdencia.cod_previdencia = max_contrato_servidor_previdencia.cod_previdencia
                                                                        AND contrato_servidor_previdencia.timestamp = max_contrato_servidor_previdencia.timestamp
                                                                        AND contrato_servidor_previdencia.bo_excluido IS FALSE
                                                                        AND contrato_servidor_previdencia.cod_contrato = registro_evento_complementar.cod_contrato)                                                     
                                                    GROUP BY registro_evento_complementar.cod_contrato
                                                        , registro_evento_complementar.cod_complementar) as complementar');
        inCountFolhaFerias := selectIntoInteger('SELECT count(*) as contador
                                        FROM (SELECT registro_evento_ferias.cod_contrato
                                                FROM folhapagamento'||stEntidade||'.registro_evento_ferias
                                                    , folhapagamento'||stEntidade||'.ultimo_registro_evento_ferias
                                                    , folhapagamento'||stEntidade||'.evento_ferias_calculado
                                                    , folhapagamento'||stEntidade||'.periodo_movimentacao
                                                    , pessoal'||stEntidade||'.lancamento_ferias
                                                    , pessoal'||stEntidade||'.ferias
                                                    , pessoal'||stEntidade||'.servidor_contrato_servidor
                                                    , pessoal'||stEntidade||'.servidor
                                                WHERE registro_evento_ferias.cod_registro     = ultimo_registro_evento_ferias.cod_registro
                                                AND registro_evento_ferias.timestamp        = ultimo_registro_evento_ferias.timestamp
                                                AND registro_evento_ferias.cod_evento       = ultimo_registro_evento_ferias.cod_evento
                                                AND registro_evento_ferias.desdobramento    = ultimo_registro_evento_ferias.desdobramento
                                                AND registro_evento_ferias.cod_registro     = evento_ferias_calculado.cod_registro
                                                AND registro_evento_ferias.timestamp        = evento_ferias_calculado.timestamp_registro
                                                AND registro_evento_ferias.cod_evento       = evento_ferias_calculado.cod_evento
                                                AND registro_evento_ferias.desdobramento    = evento_ferias_calculado.desdobramento
                                                AND registro_evento_ferias.cod_contrato = servidor_contrato_servidor.cod_contrato
                                                AND servidor_contrato_servidor.cod_servidor = servidor.cod_servidor
                                                AND registro_evento_ferias.cod_periodo_movimentacao = periodo_movimentacao.cod_periodo_movimentacao
                                                AND ferias.cod_contrato = registro_evento_ferias.cod_contrato
                                                AND ferias.cod_ferias = lancamento_ferias.cod_ferias
                                                AND servidor.numcgm = '||inNumCgm||'
                                                AND registro_evento_ferias.cod_periodo_movimentacao = '||inCodPeriodoMovimentacao||'
                                                AND (registro_evento_ferias.desdobramento = ''F''                                                  
                                                    OR  registro_evento_ferias.desdobramento = ''A'')
                                                AND lancamento_ferias.ano_competencia = to_char(periodo_movimentacao.dt_final,''yyyy'')
                                                AND lancamento_ferias.mes_competencia = to_char(periodo_movimentacao.dt_final,''mm'')
                                                AND EXISTS (SELECT 1
                                                                FROM pessoal'||stEntidade||'.contrato_servidor_previdencia
                                                                    , (  SELECT cod_contrato
                                                                            , cod_previdencia
                                                                            , max(timestamp) as timestamp
                                                                        FROM pessoal'||stEntidade||'.contrato_servidor_previdencia
                                                                    GROUP BY cod_contrato  
                                                                            , cod_previdencia) as max_contrato_servidor_previdencia
                                                                WHERE contrato_servidor_previdencia.cod_contrato = max_contrato_servidor_previdencia.cod_contrato
                                                                AND contrato_servidor_previdencia.cod_previdencia = max_contrato_servidor_previdencia.cod_previdencia
                                                                AND contrato_servidor_previdencia.timestamp = max_contrato_servidor_previdencia.timestamp
                                                                AND contrato_servidor_previdencia.bo_excluido IS FALSE
                                                                AND contrato_servidor_previdencia.cod_contrato = registro_evento_ferias.cod_contrato)                                                                                                    
                                            GROUP BY registro_evento_ferias.cod_contrato) as ferias');
        inCountFolhaRescisao := selectIntoInteger('SELECT count(*) as contador
                                        FROM (SELECT registro_evento_rescisao.cod_contrato
                                                FROM folhapagamento'||stEntidade||'.registro_evento_rescisao
                                                    , folhapagamento'||stEntidade||'.ultimo_registro_evento_rescisao
                                                    , folhapagamento'||stEntidade||'.evento_rescisao_calculado
                                                    , pessoal'||stEntidade||'.servidor_contrato_servidor
                                                    , pessoal'||stEntidade||'.servidor
                                                WHERE registro_evento_rescisao.cod_registro     = ultimo_registro_evento_rescisao.cod_registro
                                                AND registro_evento_rescisao.timestamp        = ultimo_registro_evento_rescisao.timestamp
                                                AND registro_evento_rescisao.cod_evento       = ultimo_registro_evento_rescisao.cod_evento
                                                AND registro_evento_rescisao.desdobramento    = ultimo_registro_evento_rescisao.desdobramento
                                                AND registro_evento_rescisao.cod_registro     = evento_rescisao_calculado.cod_registro
                                                AND registro_evento_rescisao.timestamp        = evento_rescisao_calculado.timestamp_registro
                                                AND registro_evento_rescisao.cod_evento       = evento_rescisao_calculado.cod_evento
                                                AND registro_evento_rescisao.desdobramento    = evento_rescisao_calculado.desdobramento
                                                AND registro_evento_rescisao.cod_contrato = servidor_contrato_servidor.cod_contrato
                                                AND servidor_contrato_servidor.cod_servidor = servidor.cod_servidor
                                                AND servidor.numcgm = '||inNumCgm||'
                                                AND registro_evento_rescisao.cod_periodo_movimentacao = '||inCodPeriodoMovimentacao||'
                                                AND registro_evento_rescisao.desdobramento = ''S''                                               
                                                AND EXISTS (SELECT 1
                                                                FROM pessoal'||stEntidade||'.contrato_servidor_previdencia
                                                                    , (  SELECT cod_contrato
                                                                            , cod_previdencia
                                                                            , max(timestamp) as timestamp
                                                                        FROM pessoal'||stEntidade||'.contrato_servidor_previdencia
                                                                    GROUP BY cod_contrato  
                                                                            , cod_previdencia) as max_contrato_servidor_previdencia
                                                                WHERE contrato_servidor_previdencia.cod_contrato = max_contrato_servidor_previdencia.cod_contrato
                                                                AND contrato_servidor_previdencia.cod_previdencia = max_contrato_servidor_previdencia.cod_previdencia
                                                                AND contrato_servidor_previdencia.timestamp = max_contrato_servidor_previdencia.timestamp
                                                                AND contrato_servidor_previdencia.bo_excluido IS FALSE
                                                                AND contrato_servidor_previdencia.cod_contrato = registro_evento_rescisao.cod_contrato)                                                                                                    
                                            GROUP BY registro_evento_rescisao.cod_contrato) as rescisao');                                          
        inCountFolhaSalario := selectIntoInteger('SELECT count(*) as contador
                                        FROM (SELECT registro_evento_periodo.cod_contrato
                                                FROM folhapagamento'||stEntidade||'.evento_calculado
                                                    , folhapagamento'||stEntidade||'.registro_evento
                                                    , folhapagamento'||stEntidade||'.registro_evento_periodo
                                                    , pessoal'||stEntidade||'.servidor_contrato_servidor
                                                    , pessoal'||stEntidade||'.servidor
                                                WHERE evento_calculado.cod_evento = registro_evento.cod_evento
                                                    AND evento_calculado.cod_registro = registro_evento.cod_registro
                                                    AND evento_calculado.timestamp_registro = registro_evento.timestamp
                                                    AND registro_evento.cod_registro = registro_evento_periodo.cod_registro
                                                    AND registro_evento_periodo.cod_contrato = servidor_contrato_servidor.cod_contrato
                                                    AND servidor_contrato_servidor.cod_servidor = servidor.cod_servidor
                                                    AND servidor.numcgm = '||inNumCgm||'
                                                    AND registro_evento_periodo.cod_periodo_movimentacao = '||inCodPeriodoMovimentacao||'
                                                    AND EXISTS (SELECT 1
                                                                FROM pessoal'||stEntidade||'.contrato_servidor_previdencia
                                                                    , (  SELECT cod_contrato
                                                                            , cod_previdencia
                                                                            , max(timestamp) as timestamp
                                                                            FROM pessoal'||stEntidade||'.contrato_servidor_previdencia
                                                                        GROUP BY cod_contrato  
                                                                            , cod_previdencia) as max_contrato_servidor_previdencia
                                                                WHERE contrato_servidor_previdencia.cod_contrato = max_contrato_servidor_previdencia.cod_contrato
                                                                AND contrato_servidor_previdencia.cod_previdencia = max_contrato_servidor_previdencia.cod_previdencia
                                                                AND contrato_servidor_previdencia.timestamp = max_contrato_servidor_previdencia.timestamp
                                                                AND contrato_servidor_previdencia.bo_excluido IS FALSE
                                                                AND contrato_servidor_previdencia.cod_contrato = registro_evento_periodo.cod_contrato)                                                                                                     
                                                GROUP BY registro_evento_periodo.cod_contrato) as salario');
    
        IF inCountFolhaSalario >= 1 AND NOT (inCountFolhaSalario = 1 AND inCountFolhaComplementar = 0 AND inCountFolhaFerias = 0 AND inCountFolhaRescisao = 0) THEN
            --Código para ajustes da previdência
            inCodComplementar := pega0UltimaComplementar('f',inCodPeriodoMovimentacao);
        
            stSql := 'SELECT *
                        FROM folhapagamento'||stEntidade||'.tipo_evento_previdencia';
            FOR reRegistro IN EXECUTE stSql
            LOOP
                --Consulta que busca os eventos da previdencia
                stSql := 'SELECT evento.cod_evento
                                , evento.natureza
                            FROM folhapagamento'||stEntidade||'.previdencia_evento 
                                , folhapagamento'||stEntidade||'.evento
                            WHERE cod_tipo = '||reRegistro.cod_tipo||'
                            AND cod_previdencia = '||inCodPrevidencia||'
                            AND timestamp       = '''||stTimestamp||'''
                            AND previdencia_evento.cod_evento = evento.cod_evento';
                OPEN crCursor FOR EXECUTE stSql;
                    FETCH crCursor INTO inCodEvento,stNatureza;
                CLOSE crCursor;           
    
                --Consulta que busca o valor da folha principal a ser somado com os demais valores
                --verificando se a folha já foi calculado e se está fechada
                stSql := 'SELECT evento_calculado.valor
                                , registro_evento.cod_registro
                                , registro_evento.timestamp
                                , registro_evento.cod_evento
                                , registro_evento_periodo.cod_contrato                           
                            FROM folhapagamento'||stEntidade||'.registro_evento
                                , folhapagamento'||stEntidade||'.evento_calculado
                                , folhapagamento'||stEntidade||'.registro_evento_periodo
                                , folhapagamento'||stEntidade||'.contrato_servidor_periodo
                                , folhapagamento'||stEntidade||'.periodo_movimentacao
                                , folhapagamento'||stEntidade||'.folha_situacao
                                , (  SELECT cod_periodo_movimentacao
                                        , max(timestamp) as timestamp
                                    FROM folhapagamento'||stEntidade||'.folha_situacao
                                GROUP BY cod_periodo_movimentacao) as max_folha_situacao
                                , pessoal'||stEntidade||'.servidor_contrato_servidor
                                , pessoal'||stEntidade||'.servidor
                            WHERE registro_evento.cod_evento       = '||inCodEvento||'
                            AND registro_evento.cod_registro     = registro_evento_periodo.cod_registro
                            AND registro_evento.cod_registro     = evento_calculado.cod_registro
                            AND registro_evento.timestamp        = evento_calculado.timestamp_registro
                            AND registro_evento.cod_evento       = evento_calculado.cod_evento
                            AND registro_evento_periodo.cod_contrato             = contrato_servidor_periodo.cod_contrato
                            AND registro_evento_periodo.cod_periodo_movimentacao = contrato_servidor_periodo.cod_periodo_movimentacao
                            AND contrato_servidor_periodo.cod_periodo_movimentacao = periodo_movimentacao.cod_periodo_movimentacao
                            AND periodo_movimentacao.cod_periodo_movimentacao = folha_situacao.cod_periodo_movimentacao
                            AND periodo_movimentacao.cod_periodo_movimentacao = '||inCodPeriodoMovimentacao||'
                            AND registro_evento_periodo.cod_contrato != '||inCodContrato||'
                            AND folha_situacao.cod_periodo_movimentacao = max_folha_situacao.cod_periodo_movimentacao
                            AND folha_situacao.timestamp                = max_folha_situacao.timestamp
                            AND registro_evento_periodo.cod_contrato = servidor_contrato_servidor.cod_contrato
                            AND servidor_contrato_servidor.cod_servidor = servidor.cod_servidor
                            AND servidor.numcgm = '||inNumCgm||'';
                FOR reBases IN EXECUTE stSql
                LOOP
                    IF stNatureza = 'B' THEN
                        nuSomaBase := nuSomaBase + reBases.valor;
                    END IF;
                    IF stNatureza = 'D' THEN
                        nuSomaDesconto := nuSomaDesconto + reBases.valor;
                    END IF;
                END LOOP;                    
                --Loop para buscar os demais valores das outras complementar maiores que 1
                FOR inIndex IN 1 .. inCodComplementar
                LOOP
                    stSql := 'SELECT evento_complementar_calculado.valor
                                    , registro_evento_complementar.cod_registro
                                    , registro_evento_complementar.cod_configuracao
                                    , registro_evento_complementar.timestamp
                                    , registro_evento_complementar.cod_complementar
                                    , registro_evento_complementar.cod_contrato
                                    , registro_evento_complementar.cod_evento
                                FROM folhapagamento'||stEntidade||'.registro_evento_complementar                                         
                                    , folhapagamento'||stEntidade||'.ultimo_registro_evento_complementar 
                                    , folhapagamento'||stEntidade||'.evento_complementar_calculado
                                    , pessoal'||stEntidade||'.servidor_contrato_servidor
                                    , pessoal'||stEntidade||'.servidor
                                    , folhapagamento'||stEntidade||'.complementar_situacao
                                    , (SELECT cod_periodo_movimentacao
                                            , cod_complementar
                                            ,  max(timestamp) as timestamp
                                        FROM folhapagamento'||stEntidade||'.complementar_situacao
                                    GROUP BY cod_periodo_movimentacao
                                            , cod_complementar) as max_complementar_situacao
                                WHERE registro_evento_complementar.cod_evento       = '||inCodEvento||'
                                AND registro_evento_complementar.cod_complementar = '||inIndex||'
                                AND registro_evento_complementar.cod_registro     = ultimo_registro_evento_complementar.cod_registro
                                AND registro_evento_complementar.timestamp        = ultimo_registro_evento_complementar.timestamp
                                AND registro_evento_complementar.cod_evento       = ultimo_registro_evento_complementar.cod_evento
                                AND registro_evento_complementar.cod_configuracao = ultimo_registro_evento_complementar.cod_configuracao
                                AND registro_evento_complementar.cod_registro     = evento_complementar_calculado.cod_registro
                                AND registro_evento_complementar.timestamp        = evento_complementar_calculado.timestamp_registro
                                AND registro_evento_complementar.cod_evento       = evento_complementar_calculado.cod_evento
                                AND registro_evento_complementar.cod_configuracao = evento_complementar_calculado.cod_configuracao
                                AND registro_evento_complementar.cod_complementar = complementar_situacao.cod_complementar
                                AND registro_evento_complementar.cod_periodo_movimentacao = complementar_situacao.cod_periodo_movimentacao
                                AND complementar_situacao.cod_complementar = max_complementar_situacao.cod_complementar
                                AND complementar_situacao.cod_periodo_movimentacao = max_complementar_situacao.cod_periodo_movimentacao
                                AND complementar_situacao.timestamp = max_complementar_situacao.timestamp
                                AND complementar_situacao.situacao = ''f''
                                AND registro_evento_complementar.cod_periodo_movimentacao = '||inCodPeriodoMovimentacao||'
                                AND registro_evento_complementar.cod_configuracao = 1
                                AND registro_evento_complementar.cod_contrato = servidor_contrato_servidor.cod_contrato
                                AND servidor_contrato_servidor.cod_servidor = servidor.cod_servidor
                                AND servidor.numcgm = '||inNumCgm||'';
                    FOR reBases IN EXECUTE stSql
                    LOOP
                        IF stNatureza = 'B' THEN                        
                            nuSomaBase := nuSomaBase + reBases.valor;
                        END IF;
                        IF stNatureza = 'D' THEN
                            nuSomaDesconto := nuSomaDesconto + reBases.valor;
                        END IF;
                    END LOOP;
                END LOOP;                    
                -------------------INÍCIO DO AJUSTE COM AS FÉRIAS--------------------
    
                stSql := 'SELECT evento_ferias_calculado.valor
                            FROM folhapagamento'||stEntidade||'.registro_evento_ferias
                                , folhapagamento'||stEntidade||'.ultimo_registro_evento_ferias
                                , folhapagamento'||stEntidade||'.evento_ferias_calculado
                                , folhapagamento'||stEntidade||'.periodo_movimentacao
                                , pessoal'||stEntidade||'.lancamento_ferias
                                , pessoal'||stEntidade||'.ferias
                                , pessoal'||stEntidade||'.servidor_contrato_servidor
                                , pessoal'||stEntidade||'.servidor
                            WHERE registro_evento_ferias.cod_registro     = ultimo_registro_evento_ferias.cod_registro
                            AND registro_evento_ferias.timestamp        = ultimo_registro_evento_ferias.timestamp
                            AND registro_evento_ferias.cod_evento       = ultimo_registro_evento_ferias.cod_evento
                            AND registro_evento_ferias.desdobramento    = ultimo_registro_evento_ferias.desdobramento
                            AND registro_evento_ferias.cod_registro     = evento_ferias_calculado.cod_registro
                            AND registro_evento_ferias.timestamp        = evento_ferias_calculado.timestamp_registro
                            AND registro_evento_ferias.cod_evento       = evento_ferias_calculado.cod_evento
                            AND registro_evento_ferias.desdobramento    = evento_ferias_calculado.desdobramento
                            AND registro_evento_ferias.cod_contrato = servidor_contrato_servidor.cod_contrato
                            AND servidor_contrato_servidor.cod_servidor = servidor.cod_servidor
                            AND registro_evento_ferias.cod_periodo_movimentacao = periodo_movimentacao.cod_periodo_movimentacao
                            AND ferias.cod_contrato = registro_evento_ferias.cod_contrato
                            AND ferias.cod_ferias = lancamento_ferias.cod_ferias
                            AND servidor.numcgm = '||inNumCgm||'
                            AND registro_evento_ferias.cod_periodo_movimentacao = '||inCodPeriodoMovimentacao||'
                            AND registro_evento_ferias.cod_evento = '||inCodEvento||'
                            AND (registro_evento_ferias.desdobramento = ''F''                                                  
                            OR  registro_evento_ferias.desdobramento = ''A'')
                            AND lancamento_ferias.ano_competencia = to_char(periodo_movimentacao.dt_final,''yyyy'')
                            AND lancamento_ferias.mes_competencia = to_char(periodo_movimentacao.dt_final,''mm'')';
                FOR reBases IN EXECUTE stSql
                LOOP
                    IF stNatureza = 'B' THEN                        
                        nuSomaBase := nuSomaBase + reBases.valor;
                    END IF;
                    IF stNatureza = 'D' THEN
                        nuSomaDesconto := nuSomaDesconto + reBases.valor;
                    END IF;
                END LOOP;                        
                -------------------FIM DO AJUSTE COM AS FÉRIAS-----------------------
                
                -------------------INÍCIO DO AJUSTE COM AS RESCISÃO--------------------
                stSql := 'SELECT evento_rescisao_calculado.valor
                            FROM folhapagamento'||stEntidade||'.registro_evento_rescisao
                                , folhapagamento'||stEntidade||'.ultimo_registro_evento_rescisao
                                , folhapagamento'||stEntidade||'.evento_rescisao_calculado
                                , pessoal'||stEntidade||'.servidor_contrato_servidor
                                , pessoal'||stEntidade||'.servidor
                            WHERE registro_evento_rescisao.cod_registro     = ultimo_registro_evento_rescisao.cod_registro
                            AND registro_evento_rescisao.timestamp        = ultimo_registro_evento_rescisao.timestamp
                            AND registro_evento_rescisao.cod_evento       = ultimo_registro_evento_rescisao.cod_evento
                            AND registro_evento_rescisao.desdobramento    = ultimo_registro_evento_rescisao.desdobramento
                            AND registro_evento_rescisao.cod_registro     = evento_rescisao_calculado.cod_registro
                            AND registro_evento_rescisao.timestamp        = evento_rescisao_calculado.timestamp_registro
                            AND registro_evento_rescisao.cod_evento       = evento_rescisao_calculado.cod_evento
                            AND registro_evento_rescisao.desdobramento    = evento_rescisao_calculado.desdobramento
                            AND registro_evento_rescisao.cod_contrato = servidor_contrato_servidor.cod_contrato
                            AND servidor_contrato_servidor.cod_servidor = servidor.cod_servidor
                            AND servidor.numcgm = '||inNumCgm||'
                            AND registro_evento_rescisao.cod_periodo_movimentacao = '||inCodPeriodoMovimentacao||'
                            AND registro_evento_rescisao.cod_evento = '||inCodEvento||'
                            AND registro_evento_rescisao.desdobramento = ''S'' ';
                FOR reBases IN EXECUTE stSql
                LOOP
                    IF stNatureza = 'B' THEN                       
                        nuSomaBase := nuSomaBase + reBases.valor;
                    END IF;
                    IF stNatureza = 'D' THEN
                        nuSomaDesconto := nuSomaDesconto + reBases.valor;
                    END IF;
                END LOOP;            
                -------------------FIM DO AJUSTE COM AS RESCISÃO-----------------------
            END LOOP;
    
        END IF;
        ---------------------AJUSTE COM DESCONTOS EXTERNOS----------------------
        stSql := ' SELECT registro_evento_periodo.cod_contrato
                    FROM folhapagamento'||stEntidade||'.registro_evento_periodo
                        , folhapagamento'||stEntidade||'.evento_calculado
                        , pessoal'||stEntidade||'.servidor_contrato_servidor
                        , pessoal'||stEntidade||'.servidor
                    WHERE registro_evento_periodo.cod_registro = evento_calculado.cod_registro
                    AND registro_evento_periodo.cod_contrato = servidor_contrato_servidor.cod_contrato
                    AND servidor_contrato_servidor.cod_servidor = servidor.cod_servidor
                    AND registro_evento_periodo.cod_periodo_movimentacao = '||inCodPeriodoMovimentacao||'
                    AND numcgm = '||inNumCgm||'
                GROUP BY registro_evento_periodo.cod_contrato';
     
        FOR reRegistro IN EXECUTE stSql
        LOOP        
            stSql := '     SELECT desconto_externo_previdencia.vl_base_previdencia as base
                                , desconto_externo_previdencia_valor.valor_previdencia as desconto
                            FROM folhapagamento'||stEntidade||'.desconto_externo_previdencia                                    
                        LEFT JOIN (SELECT desconto_externo_previdencia_valor.*
                                    FROM folhapagamento'||stEntidade||'.desconto_externo_previdencia_valor
                                        , (   SELECT cod_contrato
                                                , max(timestamp_valor) as timestamp_valor
                                                FROM folhapagamento'||stEntidade||'.desconto_externo_previdencia_valor
                                            GROUP BY cod_contrato) as max_desconto_externo_previdencia_valor
                                    WHERE desconto_externo_previdencia_valor.cod_contrato = max_desconto_externo_previdencia_valor.cod_contrato
                                    AND desconto_externo_previdencia_valor.timestamp_valor = max_desconto_externo_previdencia_valor.timestamp_valor) AS desconto_externo_previdencia_valor
                            ON desconto_externo_previdencia_valor.cod_contrato = desconto_externo_previdencia.cod_contrato
                            AND desconto_externo_previdencia_valor.timestamp = desconto_externo_previdencia_valor.timestamp      
                                , (  SELECT cod_contrato
                                        , max(timestamp) as timestamp
                                    FROM folhapagamento'||stEntidade||'.desconto_externo_previdencia
                                    WHERE vigencia <= '||quote_literal(pega0DataFinalCompetenciaDoPeriodoMovimento(inCodPeriodoMovimentacao))||'
                                GROUP BY cod_contrato) as max_desconto_externo_previdencia
                            WHERE desconto_externo_previdencia.cod_contrato = max_desconto_externo_previdencia.cod_contrato
                            AND desconto_externo_previdencia.timestamp = max_desconto_externo_previdencia.timestamp
                            AND NOT EXISTS (SELECT 1
                                                FROM folhapagamento'||stEntidade||'.desconto_externo_previdencia_anulado
                                            WHERE desconto_externo_previdencia.cod_contrato = desconto_externo_previdencia_anulado.cod_contrato
                                                AND desconto_externo_previdencia.timestamp = desconto_externo_previdencia_anulado.timestamp)
                            AND desconto_externo_previdencia.cod_contrato = '||reRegistro.cod_contrato;
            OPEN crCursor FOR EXECUTE stSql;
                FETCH crCursor INTO reDescontoExterno;
            CLOSE crCursor;             
            IF reDescontoExterno.base IS NOT NULL THEN 
                nuSomaBase     := nuSomaBase + reDescontoExterno.base;
            END IF;
            IF reDescontoExterno.desconto IS NOT NULL THEN
                nuSomaDescontoExterno := nuSomaDescontoExterno + reDescontoExterno.desconto;
            END IF;
        END LOOP;
        ---------------------AJUSTE COM DESCONTOS EXTERNOS----------------------  
        IF nuSomaBase > 0 THEN
            nuSomaDesconto := nuSomaDesconto + nuSomaDescontoExterno;
            stSql := '     SELECT evento_calculado.*
                                , cod_tipo
                            FROM folhapagamento'||stEntidade||'.registro_evento_periodo
                                , folhapagamento'||stEntidade||'.evento_calculado
                                , folhapagamento'||stEntidade||'.previdencia_evento
                            WHERE registro_evento_periodo.cod_registro     = evento_calculado.cod_registro
                            AND evento_calculado.cod_evento = previdencia_evento.cod_evento
                            AND (cod_tipo = 1 OR cod_tipo = 2)
                            AND cod_previdencia = '||inCodPrevidencia||'
                            AND previdencia_evento.timestamp = '||quote_literal(stTimestamp)||'
                            AND cod_periodo_movimentacao = '||inCodPeriodoMovimentacao||'
                            AND cod_contrato = '||inCodContrato; 

            FOR reRegistro IN EXECUTE stSql
            LOOP         
                --Desconto de Previdência do contrato que está sendo calculado
                IF reRegistro.cod_tipo = 1 THEN
                    inCodRegistroDesconto     := reRegistro.cod_registro;
                    inCodEventoDesconto       := reRegistro.cod_evento;
                    stTimestampDesconto       := reRegistro.timestamp_registro;                           
                END IF;
                --Base de Previdência do contrato que está sendo calculado
                IF reRegistro.cod_tipo = 2 THEN
                    nuSomaBase := nuSomaBase + reRegistro.valor;
                END IF;            
            END LOOP;
    
            --Percentual de desconto baseado na faixa de desconto da tabela folhapagamento.faixa_desconto
            stSql := 'SELECT percentual_desconto 
                        FROM folhapagamento'||stEntidade||'.faixa_desconto
                        , folhapagamento'||stEntidade||'.previdencia_previdencia
                    WHERE valor_inicial <= '||nuSomaBase||'
                        AND valor_final   >= '||nuSomaBase||'
                        AND faixa_desconto.cod_previdencia = '||inCodPrevidencia||'
                        AND faixa_desconto.timestamp_previdencia = '||quote_literal(stTimestamp)||'
                        AND previdencia_previdencia.timestamp       = faixa_desconto.timestamp_previdencia
                        AND previdencia_previdencia.cod_previdencia = faixa_desconto.cod_previdencia
                        AND previdencia_previdencia.vigencia        = '||quote_literal(dtVigencia)||' ';
            nuPercentualDesconto := selectIntoNumeric(stSql); 
            IF nuPercentualDesconto IS NULL THEN
                --VERIFICAR
                nuSomaBase := selectIntoNumeric('SELECT COALESCE(valor_final,0.00) as valor_final
                                    FROM folhapagamento'||stEntidade||'.faixa_desconto
                                        , folhapagamento'||stEntidade||'.previdencia_previdencia
                                    WHERE valor_final <= '||nuSomaBase||'
                                        AND valor_inicial > 0.00
                                        AND faixa_desconto.cod_previdencia = '||inCodPrevidencia||'
                                        AND faixa_desconto.timestamp_previdencia = '||quote_literal(stTimestamp)||'
                                        AND previdencia_previdencia.timestamp       = faixa_desconto.timestamp_previdencia
                                        AND previdencia_previdencia.cod_previdencia = faixa_desconto.cod_previdencia
                                        AND previdencia_previdencia.vigencia        = '||quote_literal(dtVigencia)||'
                                ORDER BY valor_final DESC
                                    LIMIT 1');
                nuPercentualDesconto := selectIntoNumeric('SELECT COALESCE(percentual_desconto,0.00) as percentual_desconto
                                    FROM folhapagamento'||stEntidade||'.faixa_desconto
                                        , folhapagamento'||stEntidade||'.previdencia_previdencia
                                    WHERE valor_final <= '||nuSomaBase||'
                                        AND valor_inicial > 0.00
                                        AND faixa_desconto.cod_previdencia = '||inCodPrevidencia||'
                                        AND faixa_desconto.timestamp_previdencia = '||quote_literal(stTimestamp)||'
                                        AND previdencia_previdencia.timestamp       = faixa_desconto.timestamp_previdencia
                                        AND previdencia_previdencia.cod_previdencia = faixa_desconto.cod_previdencia
                                        AND previdencia_previdencia.vigencia        = '||quote_literal(dtVigencia)||'
                                ORDER BY valor_final DESC
                                    LIMIT 1');
            END IF;
            nuTotalDescontoCalculo := nuSomaBase * nuPercentualDesconto / 100;
            nuTotalDescontoCalculo := nuTotalDescontoCalculo - nuSomaDesconto;
            nuTotalDescontoCalculo := truncarNumerico(nuTotalDescontoCalculo,2);
            IF nuTotalDescontoCalculo < 0 THEN
                nuTotalDescontoCalculo := 0;
            END IF;
            stSql := 'UPDATE folhapagamento'||stEntidade||'.evento_calculado SET valor = '||nuTotalDescontoCalculo||',
                                                                            quantidade = '||nuPercentualDesconto||'
                        WHERE cod_evento       = '||inCodEventoDesconto||'
                        AND cod_registro       = '||inCodRegistroDesconto||'
                        AND timestamp_registro = '||quote_literal(stTimestampDesconto)||'';
            EXECUTE stSql;
        END IF;    
    END IF;
    RETURN TRUE;
END;
$$ LANGUAGE 'plpgsql';
