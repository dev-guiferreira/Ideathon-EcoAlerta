<!-- Página de cadastro de novas ocorrências. -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Registre problemas com árvores e ajude a cuidar da cidade.">
    <title>EcoAlerta | Registrar ocorrência</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
</head>
<body>
    <?php include "menu.php"; ?>

    <main class="main">
        <h1 class="page-title">Registrar ocorrência</h1>
        <p class="page-subtitle">Informe o problema encontrado e marque o local no mapa.</p>

        <section class="panel">
            <form id="formulario" enctype="multipart/form-data">
                <div class="field">
                    <label for="foto">Fotografia</label>
                    <input type="file" id="foto" name="foto"
                           accept="image/jpeg,image/png,image/webp" capture="environment" required>
                    <img id="preview" class="photo-preview" alt="Prévia da fotografia">
                    <small class="form-help">Formatos JPG, PNG ou WEBP. Limite de 5 MB.</small>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="tipo_problema">Tipo de problema</label>
                        <select id="tipo_problema" name="tipo_problema" required>
                            <option value="">Selecione</option>
                            <option value="Galhos na calçada">Galhos na calçada</option>
                            <option value="Galhos na rua">Galhos na rua</option>
                            <option value="Árvore caída na calçada">Árvore caída na calçada</option>
                            <option value="Árvore caída na rua">Árvore caída na rua</option>
                            <option value="Risco de queda">Risco de queda</option>
                            <option value="Raízes danificando calçada">Raízes danificando calçada</option>
                            <option value="Raízes danificando a rua">Raízes danificando a rua</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao"
                                  placeholder="Descreva o problema encontrado..."></textarea>
                    </div>
                </div>

                <div class="field">
                    <label>Localização</label>
                    <button class="btn btn-light" id="btn-gps" type="button">
                        Usar minha localização
                    </button>
                    <small id="localizacao-status">Permita o acesso à localização ou selecione um ponto no mapa.</small>
                    <div id="mapa-form"></div>
                    <small class="form-help">Você também pode clicar no mapa para ajustar o ponto manualmente.</small>
                </div>

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="hidden" name="precisao_gps" id="precisao_gps">

                <div class="form-actions">
                    <button class="btn" type="submit">Enviar ocorrência</button>
                </div>
                <p id="resultado" class="form-feedback" role="status" aria-live="polite"></p>
            </form>
        </section>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="../JavaScript/registro.js"></script>
</body>
</html>
