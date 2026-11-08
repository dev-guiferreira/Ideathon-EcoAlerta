
<?php
header("Content-Type: application/json; charset=utf-8");
require_once "conexao.php";

// Valida os dados e registra uma nova ocorrência.
try {
    // Verifica se a requisição utiliza POST.
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Método não permitido.");
    }

    // Valida as coordenadas e a precisão do GPS.
    $latitude = filter_var($_POST["latitude"] ?? null, FILTER_VALIDATE_FLOAT);
    $longitude = filter_var($_POST["longitude"] ?? null, FILTER_VALIDATE_FLOAT);
    $precisao = filter_var($_POST["precisao_gps"] ?? null, FILTER_VALIDATE_FLOAT);

    // Recebe e remove espaços desnecessários dos campos.
    $tipo = trim($_POST["tipo_problema"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");

    // Verifica se as coordenadas são válidas.
    if ($latitude === false || $longitude === false || $latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        throw new Exception("Localização inválida.");
    }

    // Verifica se o tipo de problema foi informado.
    if ($tipo === "") {
        throw new Exception("Informe o tipo de problema.");
    }

    // Verifica se uma fotografia foi enviada corretamente.
    if (!isset($_FILES["foto"]) || $_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
        throw new Exception("Envie uma fotografia válida.");
    }

    $arquivo = $_FILES["foto"];

    // Limita o tamanho da imagem a 5 MB.
    if ($arquivo["size"] > 5 * 1024 * 1024) {
        throw new Exception("A imagem deve ter até 5 MB.");
    }

    // Identifica o formato real da imagem.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($arquivo["tmp_name"]);

    // Define os formatos de imagem permitidos.
    $tiposPermitidos = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp"
    ];

    // Bloqueia formatos não permitidos.
    if (!isset($tiposPermitidos[$mime])) {
        throw new Exception("Formato de imagem não permitido.");
    }

    // Define a pasta onde as imagens serão armazenadas.
    $pasta = __DIR__ . "/../uploads/";

    // Cria a pasta caso ela não exista.
    if (!is_dir($pasta)) {
        mkdir($pasta, 0755, true);
    }

    // Gera um nome aleatório para a fotografia.
    $nomeArquivo = bin2hex(random_bytes(16)) . "." . $tiposPermitidos[$mime];
    $caminho = $pasta . $nomeArquivo;

    // Move a fotografia para a pasta de uploads.
    if (!move_uploaded_file($arquivo["tmp_name"], $caminho)) {
        throw new Exception("Erro ao salvar fotografia.");
    }

    // Prepara a inserção dos dados no banco.
    $sql = "INSERT INTO ocorrencias_arvores
        (foto, latitude, longitude, precisao_gps, tipo_problema, descricao)
        VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    // Salva os dados da ocorrência no banco.
    $stmt->execute([
        "uploads/" . $nomeArquivo,
        $latitude,
        $longitude,
        $precisao === false ? null : $precisao,
        $tipo,
        $descricao
    ]);

    // Retorna a confirmação do cadastro em JSON.
    echo json_encode([
        "sucesso" => true,
        "mensagem" => "Nova ocorrência criada e enviada para a gestão, agradecemos pela denúncia!",
        "id" => $pdo->lastInsertId()
    ]);

} catch (Throwable $e) {

    // Retorna o erro caso alguma etapa falhe.
    http_response_code(400);

    echo json_encode([
        "sucesso" => false,
        "mensagem" => $e->getMessage()
    ]);
}
