<?php
// Migration: add payment conditions to vendas table
$conn = new mysqli('127.0.0.1', 'maposuser', 'uuSS676ikajdu@sld123!', 'mapos', 3306);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error . "\n");

$cols = [
    'cond_aceite'           => "DECIMAL(10,2) NOT NULL DEFAULT 0",
    'cond_aceite_pct'       => "DECIMAL(5,2) NOT NULL DEFAULT 0",
    'cond_entrega_serv'     => "DECIMAL(10,2) NOT NULL DEFAULT 0",
    'cond_entrega_serv_pct' => "DECIMAL(5,2) NOT NULL DEFAULT 0",
    'cond_pix'              => "DECIMAL(10,2) NOT NULL DEFAULT 0",
    'cond_pix_pct'          => "DECIMAL(5,2) NOT NULL DEFAULT 0",
    'cond_parcelado'        => "DECIMAL(10,2) NOT NULL DEFAULT 0",
    'cond_parcelas'         => "INT NOT NULL DEFAULT 0",
];

foreach ($cols as $col => $def) {
    $r = $conn->query("SHOW COLUMNS FROM vendas LIKE '$col'");
    if ($r->num_rows == 0) {
        $conn->query("ALTER TABLE vendas ADD COLUMN $col $def");
        echo "Added $col\n";
    } else {
        echo "$col already exists\n";
    }
}

$conn->close();
echo "Done.\n";
