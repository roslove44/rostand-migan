const initApp = () => {
  let mobileMenu = document.querySelector("#mobileMenu");
  let hamburgerBtn = document.querySelector("#hamburger-btn");

  const toggleMenu = () => {
    mobileMenu.classList.toggle("hidden");
    hamburgerBtn.classList.toggle("toggle-btn");
  };

  hamburgerBtn.addEventListener("click", toggleMenu);
};

document.addEventListener("DOMContentLoaded", initApp);
