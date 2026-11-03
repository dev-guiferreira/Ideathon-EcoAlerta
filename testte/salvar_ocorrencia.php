
<?php

header("Content-Type: application/json; charset=utf-8");

require_once "conexao.php";

try {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Método não permitido.");
    }

    $latitude = filter_var(
        $_POST["latitude"] ?? null,
        FILTER_VALIDATE_FLOAT
    );

    $longitude = filter_var(
        $_POST["longitude"] ?? null,
        FILTER_VALIDATE_FLOAT
    );

    $precisao = filter_var(
        $_POST["precisao_gps"] ?? null,
        FILTER_VALIDATE_FLOAT
    );

    $tipo = trim($_POST["tipo_problema"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");

    if ($latitude === false || $longitude === false ||
        $latitude < -90 || $latitude > 90 ||
        $longitude < -180 || $longitude > 180) {
        throw new Exception("Localização inválida.");
    }

    if ($tipo === "") {
        throw new Exception("Informe o tipo de problema.");
    }

    if (!isset($_FILES["foto"]) ||
        $_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
        throw new Exception("Envie uma fotografia válida.");
    }

    $arquivo = $_FILES["foto"];

    if ($arquivo["size"] > 5 * 1024 * 1024) {
        throw new Exception("A imagem deve ter até 5 MB.");
    }

    // Verifica o tipo real do arquivo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($arquivo["tmp_name"]);

    $tiposPermitidos = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp"
    ];

    if (!isset($tiposPermitidos[$mime])) {
        throw new Exception("Formato de imagem não permitido.");
    }

    // Pasta para armazenar as imagens
    $pasta = __DIR__ . "/uploads/";

    if (!is_dir($pasta)) {
        mkdir($pasta, 0755, true);
    }

    $nomeArquivo = bin2hex(random_bytes(16))
        . "." . $tiposPermitidos[$mime];

    $caminho = $pasta . $nomeArquivo;

    if (!move_uploaded_file(
        $arquivo["tmp_name"],
        $caminho
    )) {
        throw new Exception("Erro ao salvar fotografia.");
    }

    $sql = "INSERT INTO ocorrencias_arvores
    (foto, latitude, longitude, precisao_gps,
     tipo_problema, descricao)
    VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "uploads/" . $nomeArquivo,
        $latitude,
        $longitude,
        $precisao === false ? null : $precisao,
        $tipo,
        $descricao
    ]);

    echo json_encode([
        "sucesso" => true,
        "mensagem" => "Ocorrência cadastrada!",
        "id" => $pdo->lastInsertId()
    ]);

} catch (Throwable $e) {

    http_response_code(400);

    echo json_encode([
        "sucesso" => false,
        "mensagem" => $e->getMessage()
    ]);
}
