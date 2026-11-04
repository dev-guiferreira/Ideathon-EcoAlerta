const botaoMenu = document.getElementById("menu-toggle");
const menuNavegacao = document.getElementById("menu-nav");

if (botaoMenu && menuNavegacao) {
    botaoMenu.addEventListener("click", () => {
        const aberto = menuNavegacao.classList.toggle("aberto");
        botaoMenu.setAttribute("aria-expanded", String(aberto));
        botaoMenu.setAttribute("aria-label", aberto ? "Fechar menu" : "Abrir menu");
    });

    document.addEventListener("click", evento => {
        if (!botaoMenu.contains(evento.target) && !menuNavegacao.contains(evento.target)) {
            menuNavegacao.classList.remove("aberto");
            botaoMenu.setAttribute("aria-expanded", "false");
            botaoMenu.setAttribute("aria-label", "Abrir menu");
        }
    });

    document.addEventListener("keydown", evento => {
        if (evento.key === "Escape") {
            menuNavegacao.classList.remove("aberto");
            botaoMenu.setAttribute("aria-expanded", "false");
            botaoMenu.setAttribute("aria-label", "Abrir menu");
            botaoMenu.focus();
        }
    });
}
