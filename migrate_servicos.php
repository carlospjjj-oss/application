<?php
// Migration: add margem and subTotal columns to servicos_vendas
// Run once: php migrate_servicos.php

$conn = new mysqli('127.0.0.1', 'maposuser', 'uuSS676ikajdu@sld123!', 'mapos', 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

// Check if margem column exists
$r = $conn->query("SHOW COLUMNS FROM servicos_vendas LIKE 'margem'");
if ($r->num_rows == 0) {
    $conn->query("ALTER TABLE servicos_vendas ADD COLUMN margem DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER quantidade");
    echo "Added margem column\n";
} else {
    echo "margem column already exists\n";
}

// Check if subTotal column exists
$r2 = $conn->query("SHOW COLUMNS FROM servicos_vendas LIKE 'subTotal'");
if ($r2->num_rows == 0) {
    $conn->query("ALTER TABLE servicos_vendas ADD COLUMN subTotal DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER margem");
    echo "Added subTotal column\n";
} else {
    echo "subTotal column already exists\n";
}

// Update existing rows: subTotal = preco * quantidade (margem 0%)
$conn->query("UPDATE servicos_vendas SET subTotal = preco * quantidade WHERE subTotal = 0");
echo "Updated existing rows subTotal\n";

// Add desconto_servico columns to vendas table
$r3 = $conn->query("SHOW COLUMNS FROM vendas LIKE 'desconto_servico'");
if ($r3->num_rows == 0) {
    $conn->query("ALTER TABLE vendas ADD COLUMN desconto_servico DECIMAL(10,2) NOT NULL DEFAULT 0");
    $conn->query("ALTER TABLE vendas ADD COLUMN tipo_desconto_servico VARCHAR(10) NOT NULL DEFAULT 'real'");
    $conn->query("ALTER TABLE vendas ADD COLUMN valor_desconto_servico DECIMAL(10,2) NOT NULL DEFAULT 0");
    echo "Added desconto_servico columns to vendas\n";
} else {
    echo "desconto_servico columns already exist\n";
}

$conn->close();
echo "Done.\n";
