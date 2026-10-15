const menuToggle = document.querySelector(".header_menu_toggle");
const headerNav = document.querySelector(".header_nav");

if (menuToggle && headerNav) {
  menuToggle.addEventListener("click", () => {
    const isOpen = headerNav.classList.toggle("is-open");

    menuToggle.setAttribute("aria-expanded", isOpen);
    menuToggle.setAttribute(
      "aria-label",
      isOpen ? "Fermer le menu" : "Ouvrir le menu",
    );
  });
}
