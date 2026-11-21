-- Adiciona coluna engenheiro na tabela execucoes
ALTER TABLE `execucoes`
  ADD COLUMN `engenheiro` VARCHAR(150) NULL DEFAULT NULL AFTER `status`,
  ADD COLUMN `tipo_instalacao` VARCHAR(100) NULL DEFAULT NULL AFTER `engenheiro`,
  ADD COLUMN `data_inicio_instalacao` DATE NULL DEFAULT NULL AFTER `tipo_instalacao`,
  ADD COLUMN `data_fim_instalacao` DATE NULL DEFAULT NULL AFTER `data_inicio_instalacao`;
