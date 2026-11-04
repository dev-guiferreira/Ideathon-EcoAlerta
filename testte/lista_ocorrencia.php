
<?php

header("Content-Type: application/json; charset=utf-8");

require_once "conexao.php";

try {

    $sql = "SELECT
                id,
                foto,
                latitude,
                longitude,
                tipo_problema,
                descricao,
                status,
                data_registro
            FROM ocorrencias_arvores
            ORDER BY data_registro DESC";

    $stmt = $pdo->query($sql);

    echo json_encode(
        $stmt->fetchAll(PDO::FETCH_ASSOC),
        JSON_UNESCAPED_UNICODE
    );

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        "erro" => "Não foi possível consultar as ocorrências."
    ]);
}
