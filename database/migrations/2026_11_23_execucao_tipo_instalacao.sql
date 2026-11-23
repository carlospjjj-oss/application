-- Adiciona campos de instalação e engenheiro na tabela execucoes
ALTER TABLE `execucoes`
  ADD COLUMN IF NOT EXISTS `tipo_instalacao`        VARCHAR(300) NULL AFTER `status`,
  ADD COLUMN IF NOT EXISTS `data_inicio_instalacao` DATE         NULL AFTER `tipo_instalacao`,
  ADD COLUMN IF NOT EXISTS `data_fim_instalacao`    DATE         NULL AFTER `data_inicio_instalacao`,
  ADD COLUMN IF NOT EXISTS `engenheiro`             VARCHAR(150) NULL AFTER `data_fim_instalacao`;
