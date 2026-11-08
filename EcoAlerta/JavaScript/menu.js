const botaoMenu = document.getElementById("menu-toggle");
const menuNavegacao = document.getElementById("menu-nav");

// Controla a abertura e o fechamento do menu
if (botaoMenu && menuNavegacao) {
    // Abre ou fecha o menu ao clicar no botão
    botaoMenu.addEventListener("click", () => {
        const aberto = menuNavegacao.classList.toggle("aberto");
        botaoMenu.setAttribute("aria-expanded", String(aberto));
        botaoMenu.setAttribute("aria-label", aberto ? "Fechar menu" : "Abrir menu");
    });

    // Fecha o menu ao clicar fora dele
    document.addEventListener("click", evento => {
        if (!botaoMenu.contains(evento.target) && !menuNavegacao.contains(evento.target)) {
            menuNavegacao.classList.remove("aberto");
            botaoMenu.setAttribute("aria-expanded", "false");
            botaoMenu.setAttribute("aria-label", "Abrir menu");
        }
    });

    // Fecha o menu ao pressionar Esc
    document.addEventListener("keydown", evento => {
        if (evento.key === "Escape") {
            menuNavegacao.classList.remove("aberto");
            botaoMenu.setAttribute("aria-expanded", "false");
            botaoMenu.setAttribute("aria-label", "Abrir menu");
            botaoMenu.focus();
        }
    });
}
