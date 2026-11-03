async function carregarArvores() {

    const resposta = await fetch("listar_ocorrencias.php");

    if (!resposta.ok) {
        throw new Error("Erro ao consultar ocorrências.");
    }

    const arvores = await resposta.json();

    arvores.forEach(arvore => {

        const lat = Number(arvore.latitude);
        const lng = Number(arvore.longitude);

        const marcador = L.marker([lat, lng]).addTo(mapa);

        marcador.bindPopup(`
            <strong>Ocorrência #${arvore.id}</strong><br>
            ${arvore.tipo_problema}<br>
            Status: ${arvore.status}<br>
            <img src="${encodeURI(arvore.foto)}"
                 alt="Foto da ocorrência"
                 width="150">
        `);

    });
}

carregarArvores().catch(console.error);
