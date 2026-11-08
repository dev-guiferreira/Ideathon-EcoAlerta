<?php
require_once "conexao.php";

// Busca todas as ocorrências cadastradas.
$sql = "SELECT id, foto, tipo_problema, descricao, status, latitude, longitude, data_registro
        FROM ocorrencias_arvores
        ORDER BY data_registro DESC";
$stmt = $pdo->query($sql);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Protege os dados exibidos no HTML.
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

    $arquivo = __DIR__ . "/../uploads/" . $nome;

    if (is_file($arquivo)) {
        return "../uploads/" . rawurlencode($nome);
    }

    // Verifica imagens antigas.
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
    <title>EcoAlerta | Todas as ocorrências</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <main class="main">
        <h1 class="page-title">Todas as ocorrências</h1>
        <p class="page-subtitle">Consulte todos os problemas registrados pela comunidade.</p>
        <div class="list-summary">
            <strong><?= count($itens) ?></strong>
            ocorrência(s) registrada(s)
        </div>

        <?php if (empty($itens)): ?>
            <section class="panel empty">
                Nenhuma ocorrência cadastrada até o momento.
            </section>
        <?php else: ?>
            <section class="occurrence-grid">
                <?php foreach ($itens as $item): ?>
                    <article class="occurrence-card">
                        <?php $imagem = fotoUrl($item["foto"] ?? ""); ?>

                        <?php if ($imagem !== ""): ?>
                            <img class="occurrence-photo"
                                 src="<?= e($imagem) ?>"
                                 alt="Foto da ocorrência #<?= (int) $item["id"] ?>"
                                 loading="lazy">
                        <?php else: ?>
                            <div class="photo-missing">
                                Fotografia não disponível
                            </div>
                        <?php endif; ?>

                        <div class="occurrence-content">
                            <div class="section-heading">
                                <h2><?= e($item["tipo_problema"]) ?></h2>
                                <span class="status">
                                    <?= e($item["status"] ?: "Pendente") ?>
                                </span>
                            </div>

                            <p> <?= e($item["descricao"] ?: "Sem descrição informada.") ?> </p>

                            <small>
                                Registro #<?= (int) $item["id"] ?>
                                ·
                                <?= !empty($item["data_registro"])
                                    ? e(date("d/m/Y H:i", strtotime($item["data_registro"])))
                                    : "Data não informada"
                                ?>
                            </small>

                            <?php if ($item["latitude"] !== null && $item["longitude"] !== null): ?>
                                <a class="text-link"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   href="https://www.openstreetmap.org/?mlat=<?= rawurlencode($item["latitude"]) ?>&mlon=<?= rawurlencode($item["longitude"]) ?>#map=17/<?= rawurlencode($item["latitude"]) ?>/<?= rawurlencode($item["longitude"]) ?>">
                                    Ver localização
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
