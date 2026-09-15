-- Coluna nota_fiscal adicionada em lancamentos (não utilizada atualmente, upload usa a coluna "anexo" já existente)
ALTER TABLE lancamentos ADD COLUMN IF NOT EXISTS nota_fiscal VARCHAR(255) NULL AFTER observacoes;
