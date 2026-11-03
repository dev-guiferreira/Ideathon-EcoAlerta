function capturarGPS() {

    if (!navigator.geolocation) {
        alert("Seu navegador não suporta GPS.");
        return;
    }

    navigator.geolocation.getCurrentPosition(

        function(posicao) {

            const latitude = posicao.coords.latitude;
            const longitude = posicao.coords.longitude;
            const precisao = posicao.coords.accuracy;

            document.getElementById("latitude").value = latitude;
            document.getElementById("longitude").value = longitude;
            document.getElementById("precisao_gps").value = precisao;

            alert("Localização capturada com sucesso!");

        },

        function(erro) {
            alert("Não foi possível obter sua localização: "
                + erro.message);
        },

        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        }
    );
}
