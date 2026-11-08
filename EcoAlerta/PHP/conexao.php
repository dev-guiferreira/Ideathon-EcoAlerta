
<?php

/* dados de acesso ao banco. */
$host = "localhost";
$banco = "sistema_arvores";
$usuario = "root";
$senha = "";

/* abre a conexão com o banco de dados. */
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {
    http_response_code(500);
    die("Erro na conexão com o banco.");
}
