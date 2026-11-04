<?php
require_once "conexao.php";
$total = (int) $pdo->query("SELECT COUNT(*) FROM ocorrencias_arvores")->fetchColumn();
$pendentes = (int) $pdo->query("SELECT COUNT(*) FROM ocorrencias_arvores WHERE LOWER(TRIM(status)) = 'pendente'")->fetchColumn();
$andamento = (int) $pdo->query("SELECT COUNT(*) FROM ocorrencias_arvores WHERE LOWER(TRIM(status)) IN ('em andamento', 'em_andamento', 'andamento')")->fetchColumn();
$resolvidas = (int) $pdo->query("SELECT COUNT(*) FROM ocorrencias_arvores WHERE LOWER(TRIM(status)) IN ('resolvida', 'resolvido', 'concluida', 'concluída', 'concluido', 'concluído')")->fetchColumn();
$ultimas = $pdo->query("SELECT id, tipo_problema, descricao, status, data_registro FROM ocorrencias_arvores ORDER BY data_registro DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
function e($valor) { return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoAlerta | Painel geral</title><link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
<?php include "menu.php"; ?>
<main class="main">
    <h1 class="page-title">Painel geral</h1>
    <p class="page-subtitle">Acompanhe as ocorrências registradas no EcoAlerta.</p>
    <section class="stats-grid" aria-label="Resumo das ocorrências">
        <article class="stat-card"><span>Total de ocorrências</span><strong><?= $total ?></strong><a href="ocorrencias.php">Ver todas</a></article>
        <article class="stat-card"><span>Pendentes</span><strong><?= $pendentes ?></strong><a href="pendentes.php">Ver pendentes</a></article>
        <article class="stat-card"><span>Em andamento</span><strong><?= $andamento ?></strong></article>
        <article class="stat-card"><span>Resolvidas</span><strong><?= $resolvidas ?></strong></article>
    </section>
    <section class="panel section-panel">
        <div class="section-heading"><h2>Registros recentes</h2><a class="text-link" href="ocorrencias.php">Ver todas</a></div>
        <?php if (!$ultimas): ?><p class="empty">Nenhuma ocorrência cadastrada até o momento.</p>
        <?php else: ?><div class="table-wrap"><table><thead><tr><th>Nº</th><th>Problema</th><th>Status</th><th>Data</th></tr></thead><tbody>
        <?php foreach ($ultimas as $item): ?><tr><td>#<?= (int)$item['id'] ?></td><td><?= e($item['tipo_problema']) ?></td><td><span class="status"><?= e($item['status'] ?: 'Pendente') ?></span></td><td><?= e(date('d/m/Y H:i', strtotime($item['data_registro']))) ?></td></tr><?php endforeach; ?>
        </tbody></table></div><?php endif; ?>
    </section>
</main>
</body></html>
