-- Tabela de execuções (vinculada à OS)
CREATE TABLE IF NOT EXISTS `execucoes` (
  `idExecucao`          INT(11)      NOT NULL AUTO_INCREMENT,
  `os_id`               INT(11)      NOT NULL,
  `status`              VARCHAR(45)  NOT NULL DEFAULT 'Aberto',
  -- checklist instalação elétrica
  `chk_quadro`          TINYINT(1)   NOT NULL DEFAULT 0,
  `chk_disjuntor`       TINYINT(1)   NOT NULL DEFAULT 0,
  `chk_cabos`           TINYINT(1)   NOT NULL DEFAULT 0,
  `chk_fixacao`         TINYINT(1)   NOT NULL DEFAULT 0,
  `chk_teste`           TINYINT(1)   NOT NULL DEFAULT 0,
  `chk_orientacao`      TINYINT(1)   NOT NULL DEFAULT 0,
  -- checklist documentação (reutiliza mesmos campos da OS)
  `doc_projeto_eletrico`          TINYINT(1) NOT NULL DEFAULT 0,
  `doc_as_built`                  TINYINT(1) NOT NULL DEFAULT 0,
  `doc_croqui`                    TINYINT(1) NOT NULL DEFAULT 0,
  `doc_diagrama_unifilar`         TINYINT(1) NOT NULL DEFAULT 0,
  `doc_memorial_descritivo`       TINYINT(1) NOT NULL DEFAULT 0,
  `doc_memorial_calculo`          TINYINT(1) NOT NULL DEFAULT 0,
  `doc_laudo_tecnico`             TINYINT(1) NOT NULL DEFAULT 0,
  `doc_art_projeto`               TINYINT(1) NOT NULL DEFAULT 0,
  `doc_art_execucao`              TINYINT(1) NOT NULL DEFAULT 0,
  `doc_relatorio_fotografico`     TINYINT(1) NOT NULL DEFAULT 0,
  `doc_relatorio_comissionamento` TINYINT(1) NOT NULL DEFAULT 0,
  `doc_orientacao_operacao`       TINYINT(1) NOT NULL DEFAULT 0,
  `doc_orientacao_emergencia`     TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idExecucao`),
  UNIQUE KEY `uq_execucao_os` (`os_id`),
  CONSTRAINT `fk_execucao_os`
    FOREIGN KEY (`os_id`) REFERENCES `os` (`idOs`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Anexos da execução (separados dos anexos da OS)
CREATE TABLE IF NOT EXISTS `anexos_execucao` (
  `idAnexo`      INT(11)      NOT NULL AUTO_INCREMENT,
  `execucao_id`  INT(11)      NOT NULL,
  `anexo`        VARCHAR(45)  NULL,
  `thumb`        VARCHAR(45)  NULL,
  `url`          VARCHAR(300) NULL,
  `path`         VARCHAR(300) NULL,
  PRIMARY KEY (`idAnexo`),
  CONSTRAINT `fk_anexo_execucao`
    FOREIGN KEY (`execucao_id`) REFERENCES `execucoes` (`idExecucao`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
