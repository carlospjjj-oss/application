-- Fase 1: Clientes
ALTER TABLE clientes ADD COLUMN terceiro TINYINT(1) NOT NULL DEFAULT 0 AFTER fornecedor;

-- Fase 3: GED em Visita Tecnica
CREATE TABLE visita_tecnica_midia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visita_tecnica_id INT NOT NULL,
    tipo ENUM('foto','video') NOT NULL,
    arquivo VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (visita_tecnica_id) REFERENCES visita_tecnica(id) ON DELETE CASCADE
);

-- Fase 3: Vendas -> OS
ALTER TABLE vendas ADD COLUMN convertida TINYINT(1) NOT NULL DEFAULT 0 AFTER status;
ALTER TABLE vendas ADD COLUMN os_id INT NULL AFTER convertida;

-- Correcao: coluna vendedor (esquecida na migration original)
ALTER TABLE vendas ADD COLUMN IF NOT EXISTS vendedor VARCHAR(255) NULL AFTER usuarios_id;

-- Tabela de serviços de vendas
CREATE TABLE IF NOT EXISTS `servicos_vendas` (
  `idServicos_venda` INT(11) NOT NULL AUTO_INCREMENT,
  `servicos_id` INT(11) NOT NULL,
  `vendas_id` INT(11) NOT NULL,
  `preco` VARCHAR(15) NULL,
  `quantidade` DOUBLE NULL,
  `subTotal` VARCHAR(15) NULL,
  PRIMARY KEY (`idServicos_venda`),
  INDEX `fk_servicos_vendas_vendas1` (`vendas_id` ASC),
  INDEX `fk_servicos_vendas_servicos1` (`servicos_id` ASC),
  CONSTRAINT `fk_servicos_vendas_vendas1`
    FOREIGN KEY (`vendas_id`)
    REFERENCES `vendas` (`idVendas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servicos_vendas_servicos1`
    FOREIGN KEY (`servicos_id`)
    REFERENCES `servicos` (`idServicos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
