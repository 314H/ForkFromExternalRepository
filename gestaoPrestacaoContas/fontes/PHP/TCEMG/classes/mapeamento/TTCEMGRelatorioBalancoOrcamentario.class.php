<?php

    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    include_once '../../../../../../gestaoAdministrativa/fontes/PHP/framework/include/valida.inc.php';
    include_once ( CLA_PERSISTENTE );

    class TTCEMGRelatorioBalancoOrcamentario extends Persistente
    {
        public function recuperaDadosBalancoOrcamentario($metodo, &$rsRecordSet, $stFiltro = "", $stOrder = "", $boTransacao = "")
        {
            return $this->executaRecupera($metodo, $rsRecordSet, $stFiltro, $stOrder, $boTransacao);
        }

        public function montaRecuperaDadosBalancoOrcamentario10()
        {
            return "
                    SELECT  -- Receitas Orçamentárias -> Receitas Correntes -> Receita Tributária
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecTributaria'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_tributaria_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecTributaria'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_tributaria_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecTributaria'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_tributaria_realizada,

                            -- Receitas Orçamentárias -> Receitas Correntes -> Receita de Contribuições
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecContribuicoes'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_contribuicoes_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecContribuicoes'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_contribuicoes_previsao_atualizada,                            
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecContribuicoes'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_contribuicoes_realizada,

                            -- Receitas Orçamentárias -> Receitas Correntes -> Receita Patrimonial
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecPatrimonial'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_patrimonial_previsao_inicial,                            
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecPatrimonial'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_patrimonial_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecPatrimonial'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_patrimonial_realizada,

                            -- Receitas Orçamentárias -> Receitas Correntes -> Receita Agropecuária
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecAgropecuaria'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_agropecuaria_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecAgropecuaria'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_agropecuaria_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecAgropecuaria'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_agropecuaria_realizada,

                            -- Receitas Orçamentárias -> Receitas Correntes -> Receita Industrial
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecIndustrial'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_industrial_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecIndustrial'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_industrial_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecIndustrial'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_industrial_realizada,

                            -- Receitas Orçamentárias -> Receitas Correntes -> Receita de Serviços
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecServicos'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_servicos_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecServicos'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_servicos_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlRecServicos'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_rec_servicos_realizada,

                            -- Receitas Orçamentárias -> Receitas Correntes -> Tranferências Correntes
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlTransfCorrentes'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_transf_correntes_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlTransfCorrentes'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_transf_correntes_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlTransfCorrentes'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_transf_correntes_realizada,

                            -- Receitas Orçamentárias -> Receitas Correntes -> Outras Receitas Correntes
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOutrasRecCorrentes'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_outras_rec_correntes_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOutrasRecCorrentes'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_outras_rec_correntes_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOutrasRecCorrentes'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_outras_rec_correntes_realizada,

                            -- Receitas Orçamentárias -> Receitas Capital -> Operações de Crédito
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperacoesCredito'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_operacoes_credito_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperacoesCredito'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_operacoes_credito_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperacoesCredito'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_operacoes_credito_realizada,

                            -- Receitas Orçamentárias -> Receitas Capital -> Alienação de Bens
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlAlienacaoBens'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_alienacao_bens_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlAlienacaoBens'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_alienacao_bens_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlAlienacaoBens'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_alienacao_bens_realizada,

                            -- Receitas Orçamentárias -> Receitas Capital -> Amortizações de Empréstimos
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlAmortizacaoEmprestimo'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                      END
                                ), 0.00
                            ) AS vl_amortizacao_emprestimo_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlAmortizacaoEmprestimo'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                      END
                                ), 0.00
                            ) AS vl_amortizacao_emprestimo_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlAmortizacaoEmprestimo'
                                          THEN campos.valor
                                          ELSE 0.00
                                      END
                                ), 0.00
                            ) AS vl_amortizacao_emprestimo_realizada,

                            -- Receitas Orçamentárias -> Receitas Capital -> Transferências de Capital
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlTransfCapital'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_transf_capital_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlTransfCapital'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_transf_capital_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlTransfCapital'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_transf_capital_realizada,

                            -- Receitas Orçamentárias -> Receitas Capital -> Outras Receitas de Capital
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOutrasRecCapital'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_outras_rec_capital_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOutrasRecCapital'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_outras_rec_capital_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOutrasRecCapital'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_outras_rec_capital_realizada,

                            -- Operações de Crédito / Refinanciamento -> Operações de Crédito Internas -> Mobiliária
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaInternasMobiliaria'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_internas_mobiliaria_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaInternasMobiliaria'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_internas_mobiliaria_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaInternasMobiliaria'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_internas_mobiliaria_realizada,

                            -- Operações de Crédito / Refinanciamento -> Operações de Crédito Internas -> Contratual
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaInternasContratual'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_internas_contratual_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaInternasContratual'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_internas_contratual_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaInternasContratual'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_internas_contratual_realizada,

                            -- Operações de Crédito / Refinanciamento -> Operações de Crédito Externas -> Mobiliária
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaExternasMobiliaria'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_externas_mobiliaria_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaExternasMobiliaria'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_externas_mobiliaria_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaExternasMobiliaria'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_externas_mobiliaria_realizada,

                            -- Operações de Crédito / Refinanciamento -> Operações de Crédito Externas -> Contratual
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaExternasContratual'
                                           AND campos.fase = 1
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_externas_contratual_previsao_inicial,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaExternasContratual'
                                           AND campos.fase = 2
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_externas_contratual_previsao_atualizada,
                            COALESCE(
                                SUM(
                                    CASE  WHEN campos.nome_tag = 'vlOperaCreditoRefinaExternasContratual'
                                          THEN campos.valor
                                          ELSE 0.00
                                     END
                                ), 0.00
                            ) AS vl_opera_credito_refina_externas_contratual_realizada,
                            COALESCE(SUM(campos.valor), 0) AS vl_total_quadro_receita,
                            COALESCE(SUM(campos.vl_original), 0) AS vl_orcado
                      FROM  (
                            SELECT  configuracao_dcasp_arquivo.nome_tag,
                                    configuracao_dcasp_arquivo.seq_arquivo,
                                    configuracao_dcasp_arquivo.nome_arquivo_pertencente,
                                    configuracao_dcasp_arquivo.tipo_registro,
                                    totais_receitas.*,
                                    CASE  
                                          WHEN totais_receitas.valor > totais_receitas.vl_original
                                          THEN 2
                                          ELSE 1
                                     END AS fase
                              FROM  tcemg.configuracao_dcasp_arquivo
                              LEFT  JOIN  tcemg.configuracao_dcasp_registros using (seq_arquivo)

                              LEFT  JOIN (
                                      SELECT  conta_receita.cod_estrutural,
                                              arrecadacao_receita.exercicio,
                                              receita.vl_original,
                                              SUM(arrecadacao_receita.vl_arrecadacao) AS valor

                                          FROM  tesouraria.arrecadacao_receita

                                           JOIN  orcamento.receita
                                          ON  arrecadacao_receita.cod_receita = receita.cod_receita
                                         AND  arrecadacao_receita.exercicio = receita.exercicio

                                        JOIN  orcamento.conta_receita
                                          ON  conta_receita.cod_conta = receita.cod_conta
                                         AND  conta_receita.exercicio = receita.exercicio

                                       WHERE  arrecadacao_receita.exercicio = '".$this->getDado('exercicio')."'
                                         AND  receita.cod_entidade IN (".$this->getDado('entidades').")

                                        GROUP  BY conta_receita.cod_estrutural,
                                                    arrecadacao_receita.exercicio,
                                                    receita.vl_original
                                    )  AS totais_receitas
                                ON  configuracao_dcasp_registros.exercicio = '".$this->getDado('exercicio')."'
                               AND  replace(configuracao_dcasp_registros.conta_orc_receita, '.', '') = 
                                       replace(totais_receitas.cod_estrutural, '.', '')
                            )  AS campos

                       WHERE  campos.nome_arquivo_pertencente = 'BO'
                       AND  campos.tipo_registro = 10";
        }

        public function montaRecuperaDadosBalancoOrcamentario20()
        {
            return "
                SELECT  COALESCE(vlSaldoExercicioAnteriorSuperavitFinan.valor, 0.00) AS vl_saldo_exercicio_anterior_superavit_finan, 
                        COALESCE(vlSaldoExercicioAnteriorReaberturaCreditoAdicio.valor, 0.00) AS vl_saldo_exercicio_anterior_reabertura_credito_adicio
                 FROM  (
                          SELECT  SUM(valor_lancamento.vl_lancamento) AS valor
                            FROM  contabilidade.lancamento

                            JOIN  contabilidade.valor_lancamento
                              ON  valor_lancamento.exercicio = lancamento.exercicio
                             AND  valor_lancamento.cod_entidade = lancamento.cod_entidade
                             AND  valor_lancamento.tipo = lancamento.tipo
                             AND  valor_lancamento.cod_lote = lancamento.cod_lote
                             AND  valor_lancamento.sequencia = lancamento.sequencia

                            LEFT  JOIN contabilidade.conta_credito
                              ON  conta_credito.cod_lote = valor_lancamento.cod_lote
                             AND  conta_credito.tipo = valor_lancamento.tipo
                             AND  conta_credito.sequencia = valor_lancamento.sequencia
                             AND  conta_credito.exercicio = valor_lancamento.exercicio
                             AND  conta_credito.tipo_valor = valor_lancamento.tipo_valor
                             AND  conta_credito.cod_entidade = valor_lancamento.cod_entidade

                            LEFT  JOIN contabilidade.conta_debito
                              ON  conta_debito.cod_lote = valor_lancamento.cod_lote
                             AND  conta_debito.tipo = valor_lancamento.tipo
                             AND  conta_debito.sequencia = valor_lancamento.sequencia
                             AND  conta_debito.exercicio = valor_lancamento.exercicio
                             AND  conta_debito.tipo_valor = valor_lancamento.tipo_valor
                             AND  conta_debito.cod_entidade = valor_lancamento.cod_entidade

                            -- plano de contas de crédito
                            LEFT  JOIN contabilidade.plano_analitica AS plano_analitica_credito
                              ON  plano_analitica_credito.exercicio = conta_credito.exercicio
                             AND  plano_analitica_credito.cod_plano = conta_credito.cod_plano

                            LEFT  JOIN contabilidade.plano_conta AS plano_conta_credito
                              ON  plano_conta_credito.exercicio = plano_analitica_credito.exercicio
                             AND  plano_conta_credito.cod_conta = plano_analitica_credito.cod_conta

                            -- plano de contas de débito
                            LEFT  JOIN contabilidade.plano_analitica AS plano_analitica_debito
                              ON  plano_analitica_debito.exercicio = conta_debito.exercicio
                             AND  plano_analitica_debito.cod_plano = conta_debito.cod_plano

                            LEFT  JOIN contabilidade.plano_conta AS plano_conta_debito
                              ON  plano_conta_debito.exercicio = plano_analitica_debito.exercicio
                             AND  plano_conta_debito.cod_conta = plano_analitica_debito.cod_conta

                           WHERE  (plano_conta_credito.cod_estrutural LIKE '1%' OR plano_conta_debito.cod_estrutural LIKE '2%')
                             AND  lancamento.exercicio = '".$this->getDado('exercicio')."'
                             AND  lancamento.cod_entidade IN (".$this->getDado('entidades').")

                    ) AS vlSaldoExercicioAnteriorSuperavitFinan,
                    (
                          SELECT  SUM(suplementacao_suplementada.valor) AS valor
                              FROM  orcamento.suplementacao

                            JOIN  orcamento.suplementacao_suplementada
                              ON  suplementacao_suplementada.exercicio = suplementacao.exercicio
                             AND  suplementacao_suplementada.cod_suplementacao = suplementacao.cod_suplementacao

                           WHERE  EXTRACT(MONTH FROM suplementacao.dt_suplementacao) > 8
                             AND  suplementacao.exercicio = '".$this->getDado('exercicio')."'
                    ) AS vlSaldoExercicioAnteriorReaberturaCreditoAdicio

                    GROUP BY vlSaldoExercicioAnteriorSuperavitFinan.valor, vlSaldoExercicioAnteriorReaberturaCreditoAdicio.valor
            ";
        }

        public function montaRecuperaDadosBalancoOrcamentario30()
        {
            return "
                SELECT  -- DESPESAS ORÇAMENTÁRIAS -> DESPESAS CORRENTES -> PESSOAL E ENCARGOS SOCIAIS
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlPessoalEncarSociais'
                                 THEN campos.vl_original
                                 ELSE 0.00
                             END
                        ) AS vl_pessoal_encar_social_dotacao_inicial,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlPessoalEncarSociais'
                                 THEN campos.vl_suplementar
                                  ELSE 0.00
                               END
                         ) AS vl_pessoal_encar_social_dotacao_atualizada,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlPessoalEncarSociais'
                                 THEN campos.vl_empenhado
                                 ELSE 0.00
                             END
                        ) AS vl_pessoal_encar_social_despesas_empenhadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlPessoalEncarSociais'
                                 THEN campos.vl_liquidado
                                 ELSE 0.00
                             END
                        ) AS vl_pessoal_encar_social_despesas_liquidadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlPessoalEncarSociais'
                                 THEN campos.vl_pago
                                 ELSE 0.00
                             END
                        ) AS vl_pessoal_encar_social_despesas_pagas,

                        -- DESPESAS ORÇAMENTÁRIAS -> DESPESAS CORRENTES -> JUROS E ENCARGOS DA DÍVIDA
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlJurosEncarDividas'
                                  THEN campos.vl_original
                                  ELSE 0.00
                             END
                        ) AS vl_juros_encar_dividas_dotacao_inicial,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlJurosEncarDividas'
                                  THEN campos.vl_suplementar
                                  ELSE 0.00
                             END
                        ) AS vl_juros_encar_dividas_dotacao_atualizada,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlJurosEncarDividas'
                                  THEN campos.vl_empenhado
                                  ELSE 0.00
                             END
                        ) AS vl_juros_encar_dividas_despesas_empenhadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlJurosEncarDividas'
                                  THEN campos.vl_liquidado
                                  ELSE 0.00
                             END
                        ) AS vl_juros_encar_dividas_despesas_liquidadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlJurosEncarDividas'
                                  THEN campos.vl_pago
                                  ELSE 0.00
                             END
                        ) AS vl_juros_encar_dividas_despesas_pagas,

                        -- DESPESAS ORÇAMENTÁRIAS -> DESPESAS CORRENTES -> OUTRAS DESPESAS CORRENTES
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlOutrasDespCorrentes'
                                  THEN campos.vl_original
                                 ELSE 0.00
                             END
                        ) AS vl_outras_desp_correntes_dotacao_inicial,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlOutrasDespCorrentes'
                                  THEN campos.vl_suplementar
                                 ELSE 0.00
                             END
                        ) AS vl_outras_desp_correntes_dotacao_atualizada,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlOutrasDespCorrentes'
                                  THEN campos.vl_empenhado
                                 ELSE 0.00
                             END
                        ) AS vl_outras_desp_correntes_despesas_empenhadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlOutrasDespCorrentes'
                                  THEN campos.vl_liquidado
                                 ELSE 0.00
                             END
                        ) AS vl_outras_desp_correntes_despesas_liquidadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlOutrasDespCorrentes'
                                  THEN campos.vl_pago
                                 ELSE 0.00
                             END
                        ) AS vl_outras_desp_correntes_despesas_pagas,

                          -- DESPESAS ORÇAMENTÁRIAS -> DESPESAS DE CAPITAL -> INVESTIMENTOS
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlInvestimentos'
                                 THEN campos.vl_original
                                  ELSE 0.00
                             END
                         ) AS vl_investimentos_dotacao_inicial,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlInvestimentos'
                                 THEN campos.vl_suplementar
                                  ELSE 0.00
                             END
                         ) AS vl_investimentos_dotacao_atualizada,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlInvestimentos'
                                 THEN campos.vl_empenhado
                                  ELSE 0.00
                             END
                         ) AS vl_investimentos_despesas_empenhadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlInvestimentos'
                                 THEN campos.vl_liquidado
                                  ELSE 0.00
                             END
                         ) AS vl_investimentos_despesas_liquidadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlInvestimentos'
                                 THEN campos.vl_pago
                                  ELSE 0.00
                             END
                         ) AS vl_investimentos_despesas_pagas,

                         -- DESPESAS ORÇAMENTÁRIAS -> DESPESAS DE CAPITAL -> INVERSÕES FINANCEIRAS
                        SUM(
                                CASE WHEN campos.nome_tag = 'vlInverFinanceira'
                                 THEN campos.vl_original
                                  ELSE 0.00
                             END
                         ) AS vl_inver_financeira_dotacao_inicial,
                        SUM(
                                CASE WHEN campos.nome_tag = 'vlInverFinanceira'
                                 THEN campos.vl_suplementar
                                  ELSE 0.00
                             END
                         ) AS vl_inver_financeira_dotacao_atualizada,
                        SUM(
                                CASE WHEN campos.nome_tag = 'vlInverFinanceira'
                                 THEN campos.vl_empenhado
                                  ELSE 0.00
                             END
                         ) AS vl_inver_financeira_despesas_empenhadas,
                        SUM(
                                CASE WHEN campos.nome_tag = 'vlInverFinanceira'
                                 THEN campos.vl_liquidado
                                  ELSE 0.00
                             END
                         ) AS vl_inver_financeira_despesas_liquidadas,
                        SUM(
                                CASE WHEN campos.nome_tag = 'vlInverFinanceira'
                                 THEN campos.vl_pago
                                  ELSE 0.00
                             END
                         ) AS vl_inver_financeira_despesas_pagas,

                          -- DESPESAS ORÇAMENTÁRIAS -> DESPESAS DE CAPITAL -> AMORTIZAÇÃO DA DÍVIDA
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDivida'
                                 THEN campos.vl_original
                                  ELSE 0.00
                             END
                        ) AS vl_amortiza_divida_dotacao_inicial,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDivida'
                                 THEN campos.vl_suplementar
                                  ELSE 0.00
                             END
                        ) AS vl_amortiza_divida_dotacao_atualizada,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDivida'
                                 THEN campos.vl_empenhado
                                  ELSE 0.00
                             END
                        ) AS vl_amortiza_divida_despesas_empenhadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDivida'
                                 THEN campos.vl_liquidado
                                  ELSE 0.00
                             END
                        ) AS vl_amortiza_divida_despesas_liquidadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDivida'
                                 THEN campos.vl_pago
                                  ELSE 0.00
                             END
                        ) AS vl_amortiza_divida_despesas_pagas,

                         -- RESERVA DE CONTIGÊNCIA
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlReservaContingencia'
                                 THEN campos.vl_original
                                  ELSE 0.00
                               END
                        ) AS vl_reserva_contingencia_dotacao_inicial,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlReservaContingencia'
                                 THEN campos.vl_suplementar
                                  ELSE 0.00
                               END
                        ) AS vl_reserva_contingencia_dotacao_atualizada,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlReservaContingencia'
                                 THEN campos.vl_empenhado
                                  ELSE 0.00
                               END
                        ) AS vl_reserva_contingencia_despesas_empenhadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlReservaContingencia'
                                 THEN campos.vl_liquidado
                                  ELSE 0.00
                               END
                        ) AS vl_reserva_contingencia_despesas_liquidadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlReservaContingencia'
                                 THEN campos.vl_pago
                                  ELSE 0.00
                               END
                        ) AS vl_reserva_contingencia_despesas_pagas,

                        -- AMORTIZAÇÃO DA DÍVIDA / REFINANCIAMENTO -> AMORTIZAÇÃO DA DÍVIDA INTERNA -> DÍVIDA MOBILIÁRIA
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDividaInterMobiliaria'
                                 THEN campos.vl_original
                                 ELSE 0.00
                             END
                        ) AS vl_amortiza_divida_inter_mobiliaria_dotacao_inicial,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDividaInterMobiliaria'
                                 THEN campos.vl_suplementar
                                 ELSE 0.00
                             END
                        ) AS vl_amortiza_divida_inter_mobiliaria_dotacao_atualizada,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDividaInterMobiliaria'
                                 THEN campos.vl_empenhado
                                 ELSE 0.00
                             END
                        ) AS vl_amortiza_divida_inter_mobiliaria_despesas_empenhadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDividaInterMobiliaria'
                                 THEN campos.vl_liquidado
                                 ELSE 0.00
                             END
                        ) AS vl_amortiza_divida_inter_mobiliaria_despesas_liquidadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDividaInterMobiliaria'
                                 THEN campos.vl_pago
                                 ELSE 0.00
                             END
                        ) AS vl_amortiza_divida_inter_mobiliaria_despesas_pagas,

                        -- AMORTIZAÇÃO DA DÍVIDA / REFINANCIAMENTO -> AMORTIZAÇÃO DA DÍVIDA INTERNA -> OUTRAS DÍVIDAS
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '4.6.9.0.76.00%'
                                   OR campos.cod_estrutural LIKE '4.6.9.0.77.00%'
                                 THEN campos.vl_original
                                  ELSE 0.00
                             END
                        ) AS vl_amortiza_outras_dividas_internas_dotacao_inicial,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '4.6.9.0.76.00%'
                                   OR campos.cod_estrutural LIKE '4.6.9.0.77.00%'
                                 THEN campos.vl_suplementar
                                  ELSE 0.00
                             END
                        ) AS vl_amortiza_outras_dividas_internas_dotacao_atualizada,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '4.6.9.0.76.00%'
                                   OR campos.cod_estrutural LIKE '4.6.9.0.77.00%'
                                 THEN campos.vl_empenhado
                                  ELSE 0.00
                             END
                        ) AS vl_amortiza_outras_dividas_internas_despesas_empenhadas,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '4.6.9.0.76.00%'
                                   OR campos.cod_estrutural LIKE '4.6.9.0.77.00%'
                                 THEN campos.vl_liquidado
                                  ELSE 0.00
                             END
                        ) AS vl_amortiza_outras_dividas_internas_despesas_liquidadas,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '4.6.9.0.76.00%'
                                   OR campos.cod_estrutural LIKE '4.6.9.0.77.00%'
                                 THEN campos.vl_pago
                                  ELSE 0.00
                             END
                        ) AS vl_amortiza_outras_dividas_internas_despesas_pagas,

                        -- AMORTIZAÇÃO DA DÍVIDA / REFINANCIAMENTO -> AMORTIZAÇÃO DA DÍVIDA INTERNA -> OUTRAS DÍVIDAS
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDividaExterMobiliaria'
                                 THEN campos.vl_original
                                 ELSE 0.00
                               END
                         ) AS vl_amortiza_divida_exter_mobiliaria_dotacao_inicial,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDividaExterMobiliaria'
                                 THEN campos.vl_suplementar
                                 ELSE 0.00
                               END
                         ) AS vl_amortiza_divida_exter_mobiliaria_dotacao_atualizada,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDividaExterMobiliaria'
                                 THEN campos.vl_empenhado
                                 ELSE 0.00
                               END
                         ) AS vl_amortiza_divida_exter_mobiliaria_despesas_empenhadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDividaExterMobiliaria'
                                 THEN campos.vl_empenhado
                                 ELSE 0.00
                               END
                         ) AS vl_amortiza_divida_exter_mobiliaria_despesas_liquidadas,
                        SUM(
                            CASE WHEN campos.nome_tag = 'vlAmortizaDividaExterMobiliaria'
                                 THEN campos.vl_pago
                                 ELSE 0.00
                               END
                         ) AS vl_amortiza_divida_exter_mobiliaria_despesas_pagas,

                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '4.6.9.0.76.00%'
                                   OR campos.cod_estrutural LIKE '4.6.9.0.77.00%'
                                 THEN campos.vl_original
                                 ELSE 0.00
                             END
                         ) AS vl_amortiza_outras_dividas_externas_dotacao_inicial,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '4.6.9.0.76.00%'
                                   OR campos.cod_estrutural LIKE '4.6.9.0.77.00%'
                                 THEN campos.vl_suplementar
                                 ELSE 0.00
                             END
                         ) AS vl_amortiza_outras_dividas_externas_dotacao_atualizada,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '4.6.9.0.76.00%'
                                   OR campos.cod_estrutural LIKE '4.6.9.0.77.00%'
                                 THEN campos.vl_empenhado
                                 ELSE 0.00
                             END
                         ) AS vl_amortiza_outras_dividas_externas_despesas_empenhadas,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '4.6.9.0.76.00%'
                                   OR campos.cod_estrutural LIKE '4.6.9.0.77.00%'
                                 THEN campos.vl_liquidado
                                 ELSE 0.00
                             END
                         ) AS vl_amortiza_outras_dividas_externas_despesas_liquidadas,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '4.6.9.0.76.00%'
                                   OR campos.cod_estrutural LIKE '4.6.9.0.77.00%'
                                 THEN campos.vl_pago
                                 ELSE 0.00
                             END
                         ) AS vl_amortiza_outras_dividas_externas_despesas_pagas,

                        -- SUPERÁVIT
                        0.00 AS vl_superavit_dotacao_inicial,
                        0.00 AS vl_superavit_dotacao_atualizada,
                        0.00 AS vl_superavit_despesas_empenhadas,
                        0.00 AS vl_superavit_despesas_liquidadas,
                        0.00 AS vl_superavit_despesas_pagas,

                        -- RESERVA DO RPPS
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '9.9.9.9.99.99%'
                                  AND campos.cod_subfuncao = 997
                                  THEN campos.vl_original
                                  ELSE 0.00
                               END
                         ) AS vl_reserva_rpps_dotacao_inicial,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '9.9.9.9.99.99%'
                                  AND campos.cod_subfuncao = 997
                                  THEN campos.vl_suplementar
                                  ELSE 0.00
                               END
                         ) AS vl_reserva_rpps_dotacao_atualizada,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '9.9.9.9.99.99%'
                                  AND campos.cod_subfuncao = 997
                                  THEN campos.vl_empenhado
                                  ELSE 0.00
                               END
                         ) AS vl_reserva_rpps_despesas_empenhadas,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '9.9.9.9.99.99%'
                                  AND campos.cod_subfuncao = 997
                                  THEN campos.vl_liquidado
                                  ELSE 0.00
                               END
                         ) AS vl_reserva_rpps_despesas_liquidadas,
                        SUM(
                            CASE WHEN campos.cod_estrutural LIKE '9.9.9.9.99.99%'
                                  AND campos.cod_subfuncao = 997
                                  THEN campos.vl_pago
                                  ELSE 0.00
                               END
                         ) AS vl_reserva_rpps_despesas_pagas

                        FROM (
                               SELECT  DISTINCT
                                       totais_despesas.*,
                                       configuracao_dcasp_arquivo.nome_tag,
                                       configuracao_dcasp_arquivo.nome_arquivo_pertencente,
                                       configuracao_dcasp_arquivo.tipo_registro
                                FROM   (
                                            SELECT  despesas.cod_estrutural, 
                                                    despesas.vl_original, 
                                                    despesas.vl_suplementar,
                                                    despesas.cod_despesa,
                                                    despesas.dt_criacao,
                                                    despesas.cod_subfuncao,
                                                    COALESCE(SUM(empenhos.vl_empenhado), 0.00) AS vl_empenhado,
                                                    COALESCE(SUM(empenhos.vl_liquidado), 0.00) AS vl_liquidado,
                                                    COALESCE(SUM(empenhos.vl_liquidacao_paga), 0.00) AS vl_pago
                                             FROM  (
                                                        SELECT  conta_despesa.cod_estrutural,
                                                                despesa.cod_despesa, 
                                                                despesa.dt_criacao,
                                                                despesa.vl_original, 
                                                                despesa.cod_subfuncao,
                                                                COALESCE(SUM(suplementacao_suplementada.valor), 0.00) AS vl_suplementar

                                                          FROM  orcamento.despesa

                                                          JOIN  orcamento.conta_despesa
                                                            ON  conta_despesa.exercicio = despesa.exercicio
                                                           AND  conta_despesa.cod_conta = despesa.cod_conta

                                                     LEFT JOIN  orcamento.suplementacao_suplementada
                                                            ON  suplementacao_suplementada.exercicio = despesa.exercicio
                                                           AND  suplementacao_suplementada.cod_despesa = despesa.cod_despesa

                                                     LEFT JOIN  orcamento.suplementacao
                                                            ON  suplementacao.exercicio = suplementacao_suplementada.exercicio
                                                           AND  suplementacao.cod_suplementacao = suplementacao_suplementada.cod_suplementacao

                                                     LEFT JOIN  orcamento.suplementacao_reducao
                                                            ON  suplementacao_reducao.exercicio = suplementacao.exercicio
                                                           AND  suplementacao_reducao.cod_suplementacao = suplementacao.cod_suplementacao
                                                           AND  suplementacao_reducao.cod_despesa = despesa.cod_despesa

                                                     LEFT JOIN  orcamento.suplementacao_anulada
                                                            ON  suplementacao_anulada.exercicio = suplementacao.exercicio
                                                           AND  suplementacao_anulada.cod_suplementacao = suplementacao.cod_suplementacao

                                                         WHERE  despesa.exercicio = '".$this->getDado('exercicio')."'
                                                           AND  suplementacao_anulada.cod_suplementacao IS NULL
                                                           AND  despesa.cod_entidade IN (".$this->getDado('entidades').")

                                                      GROUP BY  conta_despesa.cod_estrutural, 
                                                                  despesa.cod_despesa, 
                                                                  despesa.dt_criacao, 
                                                                  despesa.vl_original, 
                                                                  despesa.cod_subfuncao

                                                      ORDER BY  despesa.cod_despesa
                                                    ) AS despesas

                                        LEFT  JOIN (
                                                        SELECT  pre_empenho.exercicio,
                                                                pre_empenho.cod_pre_empenho, 
                                                                pre_empenho.exercicio, 
                                                                pre_empenho_despesa.cod_despesa, 
                                                                  
                                                                COALESCE(SUM(item_pre_empenho.vl_total), 0.00) AS vl_empenhado,
                                                                COALESCE(SUM(nota_liquidacao_item.vl_total), 0.00) AS vl_liquidado,
                                                                COALESCE(SUM(nota_liquidacao_item_anulado.vl_anulado), 0.00) AS vl_anulado,
                                                                COALESCE(SUM(nota_liquidacao_paga.vl_pago), 0.00) AS vl_liquidacao_paga
                                                  
                                                          FROM  empenho.pre_empenho

                                                          JOIN  empenho.item_pre_empenho
                                                            ON  item_pre_empenho.exercicio = pre_empenho.exercicio
                                                           AND  item_pre_empenho.cod_pre_empenho = pre_empenho.cod_pre_empenho
                                                         
                                                          JOIN  empenho.pre_empenho_despesa
                                                            ON  pre_empenho_despesa.exercicio = pre_empenho.exercicio
                                                           AND  pre_empenho_despesa.cod_pre_empenho = pre_empenho.cod_pre_empenho

                                                          JOIN  empenho.empenho
                                                            ON  empenho.cod_pre_empenho = pre_empenho.cod_pre_empenho
                                                           AND  empenho.exercicio = pre_empenho.exercicio

                                                          LEFT  JOIN empenho.empenho_anulado
                                                            ON  empenho_anulado.exercicio = empenho.exercicio
                                                           AND  empenho_anulado.cod_entidade = empenho.cod_entidade
                                                           AND  empenho_anulado.cod_empenho = empenho.cod_empenho

                                                          LEFT  JOIN empenho.nota_liquidacao
                                                            ON  empenho.exercicio = nota_liquidacao.exercicio_empenho
                                                           AND  empenho.cod_entidade = nota_liquidacao.cod_entidade
                                                           AND  empenho.cod_empenho  = nota_liquidacao.cod_empenho

                                                          LEFT  JOIN empenho.nota_liquidacao_item
                                                            ON  nota_liquidacao.exercicio = nota_liquidacao_item.exercicio
                                                           AND  nota_liquidacao.cod_entidade = nota_liquidacao_item.cod_entidade
                                                           AND  nota_liquidacao.cod_nota = nota_liquidacao_item.cod_nota

                                                          LEFT  JOIN empenho.nota_liquidacao_item_anulado
                                                            ON  nota_liquidacao_item_anulado.exercicio = nota_liquidacao_item.exercicio
                                                           AND  nota_liquidacao_item_anulado.cod_nota = nota_liquidacao_item.cod_nota
                                                           AND  nota_liquidacao_item_anulado.num_item = nota_liquidacao_item.num_item
                                                           AND  nota_liquidacao_item_anulado.exercicio_item = nota_liquidacao_item.exercicio_item
                                                           AND  nota_liquidacao_item_anulado.cod_pre_empenho = nota_liquidacao_item.cod_pre_empenho
                                                           AND  nota_liquidacao_item_anulado.cod_entidade = nota_liquidacao_item.cod_entidade

                                                          LEFT  JOIN empenho.nota_liquidacao_paga
                                                            ON  nota_liquidacao_paga.exercicio = nota_liquidacao.exercicio
                                                           AND  nota_liquidacao_paga.cod_entidade = nota_liquidacao.cod_entidade
                                                           AND  nota_liquidacao_paga.cod_nota = nota_liquidacao.cod_nota

                                                          LEFT  JOIN empenho.nota_liquidacao_paga_anulada
                                                            ON  nota_liquidacao_paga_anulada.exercicio = nota_liquidacao_paga.exercicio
                                                           AND  nota_liquidacao_paga_anulada.cod_entidade = nota_liquidacao_paga.cod_entidade
                                                           AND  nota_liquidacao_paga_anulada.cod_nota = nota_liquidacao_paga.cod_nota

                                                         WHERE  pre_empenho.exercicio = '".$this->getDado('exercicio')."'
                                                      	   AND  empenho_anulado.cod_empenho IS NULL
                                                     	   AND  nota_liquidacao_paga_anulada.cod_nota IS NULL

                                                         GROUP  BY pre_empenho.cod_pre_empenho, pre_empenho.exercicio, pre_empenho_despesa.cod_despesa
                                                	) AS empenhos

                                                ON  empenhos.cod_despesa = despesas.cod_despesa

                                             GROUP  BY despesas.cod_estrutural, despesas.vl_original, despesas.vl_suplementar, despesas.cod_despesa, despesas.dt_criacao, despesas.cod_subfuncao
                                        )  AS totais_despesas

                                  JOIN  tcemg.configuracao_dcasp_registros
                                    ON  configuracao_dcasp_registros.exercicio = '".$this->getDado('exercicio')."'
                                   AND  REPLACE(configuracao_dcasp_registros.conta_orc_despesa, '.', '') = REPLACE(totais_despesas.cod_estrutural, '.', '')

                                  JOIN  tcemg.configuracao_dcasp_arquivo USING (seq_arquivo)
	                        ) AS campos
	            WHERE  campos.nome_arquivo_pertencente = 'BO'
                  AND  campos.tipo_registro = 30
            ";
        }

    }