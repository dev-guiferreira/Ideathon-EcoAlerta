<?php
require_once "conexao.php";

// Busca todas as ocorrências pendentes.
$sql = "SELECT id, foto, tipo_problema, descricao, status, latitude, longitude, data_registro
        FROM ocorrencias_arvores
        WHERE LOWER(TRIM(status)) = 'pendente'
        ORDER BY data_registro DESC";

$stmt = $pdo->query($sql);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Protege os textos exibidos no HTML.
function e($valor)
{
    return htmlspecialchars((string) ($valor ?? ""), ENT_QUOTES, "UTF-8");
}

// Retorna o caminho da fotografia existente.
function fotoUrl($foto)
{
    $nome = basename(str_replace("\\", "/", (string) $foto));

    if ($nome === "" || $nome === "." || $nome === "..") {
        return "";
    }

    // Verifica a pasta principal de imagens.
    $arquivo = __DIR__ . "/../uploads/" . $nome;

    if (is_file($arquivo)) {
        return "../uploads/" . rawurlencode($nome);
    }

    // Verifica a pasta de imagens antigas.
    $arquivoAntigo = __DIR__ . "/uploads/" . $nome;

    if (is_file($arquivoAntigo)) {
        return "uploads/" . rawurlencode($nome);
    }

    return "";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Consulte as ocorrências ambientais que aguardam atendimento no EcoAlerta.">
    <title>EcoAlerta | Ocorrências pendentes</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <main class="main">
        <!-- Cabeçalho da página -->
        <h1 class="page-title">Ocorrências pendentes</h1>
        <p class="page-subtitle">Registros que ainda aguardam análise ou atendimento.</p>

        <!-- Exibe o total de ocorrências pendentes -->
        <div class="list-summary">
            <strong><?= count($itens) ?></strong>
            ocorrência(s) aguardando atendimento
        </div>

        <!-- Lista as ocorrências cadastradas -->
        <?php if (empty($itens)): ?>
            <section class="panel empty">
                Não há ocorrências pendentes no momento.
            </section>
        <?php else: ?>
            <section class="occurrence-grid">
                <?php foreach ($itens as $item): ?>
                    <article class="occurrence-card">
                        <!-- Exibe a fotografia da ocorrência -->
                        <?php $imagem = fotoUrl($item["foto"] ?? ""); ?>

                        <?php if ($imagem !== ""): ?>
                            <img class="occurrence-photo"
                                 src="<?= e($imagem) ?>"
                                 alt="Fotografia da ocorrência #<?= (int) $item["id"] ?>"
                                 loading="lazy">
                        <?php else: ?>
                            <div class="photo-missing">
                                Fotografia não disponível
                            </div>
                        <?php endif; ?>

                        <!-- Exibe as informações da ocorrência -->
                        <div class="occurrence-content">
                            <div class="section-heading">
                                <h2><?= e($item["tipo_problema"]) ?></h2>
                                <span class="status status-pending">Pendente</span>
                            </div>

                            <!-- Exibe a descrição do problema -->
                            <p>
                                <?= e($item["descricao"] ?: "Sem descrição informada.") ?>
                            </p>

                            <!-- Exibe o número e a data do registro -->
                            <small>
                                Registro #<?= (int) $item["id"] ?>
                                ·
                                <?php if (!empty($item["data_registro"])): ?>
                                    <?= e(date("d/m/Y H:i", strtotime($item["data_registro"]))) ?>
                                <?php else: ?>
                                    Data não informada
                                <?php endif; ?>
                            </small>

                            <!-- Verifica e exibe a localização -->
                            <?php
                            $latitude = $item["latitude"];
                            $longitude = $item["longitude"];

                            $coordenadasValidas = is_numeric($latitude) &&
                                is_numeric($longitude) &&
                                $latitude >= -90 && $latitude <= 90 &&
                                $longitude >= -180 && $longitude <= 180;
                            ?>

                            <?php if ($coordenadasValidas): ?>
                                <a class="text-link"
                                   href="https://www.openstreetmap.org/?mlat=<?= rawurlencode((string) $latitude) ?>&mlon=<?= rawurlencode((string) $longitude) ?>#map=17/<?= rawurlencode((string) $latitude) ?>/<?= rawurlencode((string) $longitude) ?>"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    Ver localização
                                </a>
                            <?php else: ?>
                                <small>Localização não disponível.</small>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
