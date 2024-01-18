let menuOpenBtn = document.querySelector("#menuOpenBtn");
let menuCloseBtn = document.querySelector("#menuCloseBtn");
let mobileMenu = document.querySelector("#mobileMenu");

menuOpenBtn.addEventListener("click", () => {
  mobileMenu.classList.remove("hidden");
  menuCloseBtn.classList.remove("hidden");
  menuOpenBtn.classList.add("hidden");
  mobileMenu.classList.add("menu-open-animation");
});

menuCloseBtn.addEventListener("click", () => {
  mobileMenu.classList.add("menu-close-animation");
  setTimeout(() => {
    mobileMenu.classList.add("hidden");
    menuCloseBtn.classList.add("hidden");
    menuOpenBtn.classList.remove("hidden");
    mobileMenu.classList.remove("menu-close-animation");
  }, 250);
});
