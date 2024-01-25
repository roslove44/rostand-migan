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

  function serviceIllustrationAnimation() {
    // Fonction à exécuter lorsque l'élément entre dans la fenêtre d'affichage
    function handleIntersection(entries, observer) {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          // Ajoutez "is-visible" et "is-loaded" lorsque l'élément est visible
          setTimeout(() => {
            entry.target.classList.add("is-visible");
          }, 800);
        } else {
          // Retirez "is-visible" et "is-loaded" si vous le souhaitez lorsque l'élément n'est plus visible
          entry.target.classList.remove("is-visible");
        }
      });
    }

    // Création de l'instance de l'Intersection Observer
    let observer = new IntersectionObserver(handleIntersection);

    // Ciblage des éléments à observer
    document.querySelectorAll(".illustration").forEach((illustration) => {
      observer.observe(illustration);
    });
  }
  serviceIllustrationAnimation();

  function imageAnimation() {
    function handleIntersection(entries, observer) {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          setTimeout(() => {
            entry.target.classList.add("is-loaded");
          }, 600);
        } else {
          entry.target.classList.remove("is-loaded");
        }
      });
    }
    let imageObserver = new IntersectionObserver(handleIntersection);

    document.querySelectorAll("img, svg, i, .svg, .stack").forEach((image) => {
      imageObserver.observe(image);
    });
  }

  imageAnimation();
};

document.addEventListener("DOMContentLoaded", initApp);
