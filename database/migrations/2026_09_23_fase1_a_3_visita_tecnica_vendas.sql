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
