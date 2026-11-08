//Leaflet 
const mapaRegistro = L.map("mapa-form").setView([-21.22, -50.87], 13);

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapaRegistro);

let marcadorRegistro = null;
let circuloPrecisao = null;

const formulario = document.getElementById("formulario");
const resultado = document.getElementById("resultado");
const statusLocalizacao = document.getElementById("localizacao-status");
const botaoGps = document.getElementById("btn-gps");
const campoLatitude = document.getElementById("latitude");
const campoLongitude = document.getElementById("longitude");
const campoPrecisao = document.getElementById("precisao_gps");

// Atualiza o mapa e os campos com a localização escolhida
function definirLocalizacao(latitude, longitude, precisao = null) {
    campoLatitude.value = latitude;
    campoLongitude.value = longitude;
    campoPrecisao.value = precisao ?? "";

    mapaRegistro.setView([latitude, longitude], 17);

    if (marcadorRegistro) mapaRegistro.removeLayer(marcadorRegistro);
    marcadorRegistro = L.marker([latitude, longitude]).addTo(mapaRegistro);

    if (circuloPrecisao) mapaRegistro.removeLayer(circuloPrecisao);
    circuloPrecisao = null;

    if (Number(precisao) > 0) {
        circuloPrecisao = L.circle([latitude, longitude], {
            radius: Number(precisao),
            color: "#315b40",
            fillColor: "#315b40",
            fillOpacity: 0.10,
            weight: 1
        }).addTo(mapaRegistro);
    }

    if (Number(precisao) > 1000) {
        statusLocalizacao.textContent =
            `Localização aproximada. O aparelho informou margem de erro de ${Math.round(precisao)} metros. Se necessário, ajuste o ponto clicando no mapa.`;
    } else if (Number(precisao) > 0) {
        statusLocalizacao.textContent =
            `Localização capturada. Precisão estimada: ${Math.round(precisao)} metros.`;
    } else {
        statusLocalizacao.textContent = "Ponto selecionado manualmente no mapa.";
    }
}

// solicita a localização atual do dispositivo
botaoGps.addEventListener("click", () => {
    if (!("geolocation" in navigator)) {
        statusLocalizacao.textContent = "Este navegador não oferece suporte à localização. Marque o ponto diretamente no mapa.";
        return;
    }

    if (!window.isSecureContext) {
        statusLocalizacao.textContent =
            "A localização exige uma conexão segura (HTTPS) ou acesso pelo localhost. Abra o projeto pelo servidor local.";
        return;
    }

    botaoGps.disabled = true;
    botaoGps.textContent = "Obtendo localização...";
    statusLocalizacao.textContent =
        "Aguardando o dispositivo. Em computadores, a localização pode ser menos precisa que no celular.";

    navigator.geolocation.getCurrentPosition(
        posicao => {
            definirLocalizacao(
                posicao.coords.latitude,
                posicao.coords.longitude,
                posicao.coords.accuracy
            );
            botaoGps.disabled = false;
            botaoGps.textContent = "Atualizar minha localização";
        },
        erro => {
            const mensagens = {
                1: "Permissão negada. Autorize a localização nas configurações do navegador.",
                2: "O dispositivo não conseguiu determinar a posição. Ative os serviços de localização ou marque o ponto no mapa.",
                3: "A solicitação demorou demais. Tente novamente em uma área aberta."
            };

            statusLocalizacao.textContent = mensagens[erro.code] ||
                "Não foi possível obter a localização. Marque o ponto no mapa.";
            botaoGps.disabled = false;
            botaoGps.textContent = "Tentar novamente";
        },
        {
            enableHighAccuracy: true,
            timeout: 45000,
            maximumAge: 0
        }
    );
});

// permite selecionar a localização diretamente no mapa
mapaRegistro.on("click", evento => {
    definirLocalizacao(evento.latlng.lat, evento.latlng.lng);
});

// exibe uma prévia da fotografia selecionada.
document.getElementById("foto").addEventListener("change", evento => {
    const arquivo = evento.target.files[0];
    const preview = document.getElementById("preview");

    if (!arquivo) {
        preview.style.display = "none";
        preview.removeAttribute("src");
        return;
    }

    if (arquivo.size > 5 * 1024 * 1024) {
        alert("A imagem deve ter no máximo 5 MB.");
        evento.target.value = "";
        preview.style.display = "none";
        return;
    }

    if (preview.dataset.urlAnterior) {
        URL.revokeObjectURL(preview.dataset.urlAnterior);
    }

    preview.dataset.urlAnterior = URL.createObjectURL(arquivo);
    preview.src = preview.dataset.urlAnterior;
    preview.style.display = "block";
});

// converte a resposta do servidor para JSON
async function lerRespostaJson(resposta) {
    const texto = await resposta.text();

    try {
        return texto ? JSON.parse(texto) : {};
    } catch {
        throw new Error("O servidor não retornou uma resposta válida. Verifique a configuração do PHP.");
    }
}

// envia os dados do formulário para o PHP
formulario.addEventListener("submit", async evento => {
    evento.preventDefault();

    if (!campoLatitude.value || !campoLongitude.value) {
        resultado.textContent = "Obtenha a localização ou selecione um ponto no mapa antes de enviar.";
        resultado.className = "form-feedback erro";
        return;
    }

    const botaoEnviar = formulario.querySelector('button[type="submit"]');
    botaoEnviar.disabled = true;
    botaoEnviar.textContent = "Enviando...";
    resultado.textContent = "Enviando ocorrência...";
    resultado.className = "form-feedback";

    try {
        const resposta = await fetch("salvar_ocorrencia.php", {
            method: "POST",
            body: new FormData(formulario)
        });

        const retorno = await lerRespostaJson(resposta);

        if (!resposta.ok || !retorno.sucesso) {
            throw new Error(retorno.mensagem || "Não foi possível registrar a ocorrência.");
        }

        resultado.textContent = `Ocorrência #${retorno.id} registrada com sucesso.`;
        resultado.className = "form-feedback sucesso";
        formulario.reset();

        if (marcadorRegistro) mapaRegistro.removeLayer(marcadorRegistro);
        if (circuloPrecisao) mapaRegistro.removeLayer(circuloPrecisao);
        marcadorRegistro = null;
        circuloPrecisao = null;
        statusLocalizacao.textContent = "Nenhuma localização selecionada.";
        campoLatitude.value = "";
        campoLongitude.value = "";
        campoPrecisao.value = "";
    } catch (erro) {
        resultado.textContent = erro.message;
        resultado.className = "form-feedback erro";
    } finally {
        botaoEnviar.disabled = false;
        botaoEnviar.textContent = "Enviar ocorrência";
    }
});

// limpa os dados e a prévia ao reiniciar o formulário
formulario.addEventListener("reset", () => {
    setTimeout(() => {
        const preview = document.getElementById("preview");

        if (preview.dataset.urlAnterior) {
            URL.revokeObjectURL(preview.dataset.urlAnterior);
            delete preview.dataset.urlAnterior;
        }

        preview.style.display = "none";
        campoLatitude.value = "";
        campoLongitude.value = "";
        campoPrecisao.value = "";

        if (marcadorRegistro) mapaRegistro.removeLayer(marcadorRegistro);
        if (circuloPrecisao) mapaRegistro.removeLayer(circuloPrecisao);
        marcadorRegistro = null;
        circuloPrecisao = null;
    }, 0);
});
