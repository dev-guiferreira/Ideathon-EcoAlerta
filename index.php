<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Árvores</title>

    <link rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
        }

        input, select, textarea, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        button {
            background: #198754;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 6px;
        }

        #mapa {
            height: 300px;
            margin: 15px 0;
        }
    </style>
</head>

<body>

    <h2>Registrar árvore problemática</h2>

    <form id="formulario">

        <label>Fotografia da árvore:</label>
        <input type="file"
               name="foto"
               accept="image/jpeg,image/png,image/webp"
               capture="environment"
               required>

        <label>Tipo de problema:</label>

        <select name="tipo_problema" required>
            <option value="">Selecione</option>
            <option value="Galhos na calçada">
                Galhos na calçada
            </option>
            <option value="Galhos na rua">
                Galhos na rua
            </option>
            <option value="Risco de queda">
                Risco de queda
            </option>
            <option value="Raízes danificando calçada">
                Raízes danificando calçada
            </option>
            <option value="Outro">
                Outro
            </option>
        </select>

        <label>Descrição:</label>

        <textarea name="descricao"
                  placeholder="Descreva o problema..."></textarea>

        <button type="button" onclick="capturarGPS()">
            Capturar minha localização
        </button>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <input type="hidden" name="precisao_gps" id="precisao_gps">

        <div id="mapa"></div>

        <button type="submit">
            Enviar ocorrência
        </button>

    </form>

    <p id="resultado"></p>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const mapa = L.map("mapa").setView([-15.78, -47.93], 5);

        L.tileLayer(
            "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            {
                attribution: "&copy; OpenStreetMap"
            }
        ).addTo(mapa);

        let marcador = null;

        function atualizarMapa(lat, lng) {

            mapa.setView([lat, lng], 17);

            if (marcador) {
                mapa.removeLayer(marcador);
            }

            marcador = L.marker([lat, lng]).addTo(mapa);
        }

        function capturarGPS() {

            navigator.geolocation.getCurrentPosition(

                function(posicao) {

                    const lat = posicao.coords.latitude;
                    const lng = posicao.coords.longitude;

                    document.getElementById("latitude").value = lat;
                    document.getElementById("longitude").value = lng;
                    document.getElementById("precisao_gps").value =
                        posicao.coords.accuracy;

                    atualizarMapa(lat, lng);
                },

                function(erro) {
                    alert("Erro ao obter localização: " + erro.message);
                },

                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }

        // Permite corrigir a localização clicando no mapa
        mapa.on("click", function(e) {

            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
            document.getElementById("precisao_gps").value = "";

            atualizarMapa(lat, lng);
        });

        // Envio dos dados para o PHP
        document.getElementById("formulario")
        .addEventListener("submit", async function(e) {

            e.preventDefault();

            const latitude = document.getElementById("latitude").value;
            const longitude = document.getElementById("longitude").value;

            if (!latitude || !longitude) {
                alert("Capture ou marque a localização no mapa.");
                return;
            }

            const dados = new FormData(this);
            const resultado = document.getElementById("resultado");

            resultado.textContent = "Enviando ocorrência...";

            try {

                const resposta = await fetch("salvar_ocorrencia.php", {
                    method: "POST",
                    body: dados
                });

                const retorno = await resposta.json();

                if (!resposta.ok || !retorno.sucesso) {
                    throw new Error(retorno.mensagem);
                }

                resultado.textContent =
                    "Ocorrência cadastrada! Número: " + retorno.id;

                this.reset();

                if (marcador) {
                    mapa.removeLayer(marcador);
                    marcador = null;
                }

            } catch (erro) {

                resultado.textContent = "Erro: " + erro.message;

            }
        });
    </script>

</body>
</html>