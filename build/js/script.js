const initApp = () => {
  const mobileMenu = document.querySelector("#mobileMenu");
  const hamburgerBtn = document.querySelector("#hamburger-btn");

  const toggleMenu = () => {
    mobileMenu.classList.toggle("hidden");
    hamburgerBtn.classList.toggle("toggle-btn");
  };

  hamburgerBtn.addEventListener("click", toggleMenu);
  mobileMenu.addEventListener("click", toggleMenu);

  const dropdowns = document.querySelectorAll(".dropdown");
  dropdowns.forEach((dropdown) => {
    let dropdownBtn = dropdown.querySelector("button");
    let dropdownMenu = dropdown.querySelector(".dropdown-menu");
    const toggleStackList = (event) => {
      event.preventDefault();
      dropdownMenu.classList.toggle("sm:opacity-0");
      dropdownMenu.classList.toggle("sm:pointer-events-none");
    };

    const handleClickOutside = (event) => {
      if (
        dropdownMenu.contains(event.target) ||
        dropdownBtn.contains(event.target)
      ) {
        return;
      }
      if (!dropdownMenu.classList.contains("sm:opacity-0")) {
        dropdownMenu.classList.add("sm:opacity-0");
        dropdownMenu.classList.toggle("sm:pointer-events-none");
      }
    };

    dropdownBtn.addEventListener("click", toggleStackList);
    document.addEventListener("click", handleClickOutside);

    function randomOpacity() {
      const elements = document.querySelectorAll(
        ".a-one, .a-two, .a-three, .a-four, .a-five, .a-six, .a-seven, .a-eight, .a-nine"
      );

      elements.forEach((element) => {
        const randomOpacityValue = Math.random();
        element.style.opacity = randomOpacityValue;
      });
    }

    setInterval(randomOpacity, 500);
  });
};

document.addEventListener("DOMContentLoaded", initApp);
