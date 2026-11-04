// Coordenadas API
const mapa = L.map("mapa").setView([-21.2201, -50.8698], 12);

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapa);

const lista = document.getElementById("lista");
const contador = document.getElementById("contador");
const limites = [];

function escaparHTML(valor) {
    return String(valor ?? "").replace(/[&<>"']/g, caractere => ({
        "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
    })[caractere]);
}

function dataFormatada(valor) {
    if (!valor) return "Data não informada";
    const data = new Date(valor.replace(" ", "T"));
    return Number.isNaN(data.getTime()) ? "Data não informada" : data.toLocaleDateString("pt-BR");
}

async function carregarOcorrencias() {
    try {
        const resposta = await fetch("lista_ocorrencia.php");
        const texto = await resposta.text();
        let ocorrencias;

        try {
            ocorrencias = texto ? JSON.parse(texto) : [];
        } catch {
            console.error("Resposta inesperada do servidor:", texto);
            throw new Error("O servidor não retornou dados válidos. Confira a conexão PHP com o banco.");
        }

        if (!resposta.ok || !Array.isArray(ocorrencias)) {
            throw new Error(ocorrencias.erro || "Não foi possível consultar as ocorrências.");
        }

        contador.textContent = `${ocorrencias.length} registro(s)`;
        lista.innerHTML = "";

        if (!ocorrencias.length) {
            lista.innerHTML = '<div class="empty">Ainda não há ocorrências cadastradas.</div>';
            return;
        }

        ocorrencias.forEach(ocorrencia => {
            const lat = Number(ocorrencia.latitude);
            const lng = Number(ocorrencia.longitude);
            if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

            const ponto = [lat, lng];
            limites.push(ponto);
            const marcador = L.marker(ponto).addTo(mapa);
            const foto = ocorrencia.foto
                ? `<br><img src="${encodeURI(ocorrencia.foto)}" alt="Foto da ocorrência" style="width:150px;max-height:100px;object-fit:cover;border-radius:5px;margin-top:8px">`
                : "";

            marcador.bindPopup(`
                <strong>Ocorrência #${escaparHTML(ocorrencia.id)}</strong><br>
                ${escaparHTML(ocorrencia.tipo_problema)}<br>
                Status: ${escaparHTML(ocorrencia.status || "Pendente")}${foto}
            `);

            const item = document.createElement("button");
            item.type = "button";
            item.className = "occurrence-item";
            item.innerHTML = `
                <strong>#${escaparHTML(ocorrencia.id)} · ${escaparHTML(ocorrencia.tipo_problema)}</strong>
                <small>Status: ${escaparHTML(ocorrencia.status || "Pendente")}<br>
                Registrada em ${dataFormatada(ocorrencia.data_registro)}</small>
            `;
            item.addEventListener("click", () => {
                mapa.setView(ponto, 17);
                marcador.openPopup();
            });
            lista.appendChild(item);
        });

        if (limites.length) mapa.fitBounds(limites, { padding: [25, 25], maxZoom: 15 });
    } catch (erro) {
        contador.textContent = "Não disponível";
        lista.innerHTML = `<div class="empty">${escaparHTML(erro.message)}</div>`;
    }
}

carregarOcorrencias();
