const initApp = () => {
  const mobileMenu = document.querySelector("#mobileMenu");
  const hamburgerBtn = document.querySelector("#hamburger-btn");
  let articles = document.querySelectorAll("article.sendToPost");

  const toggleMenu = () => {
    mobileMenu.classList.toggle("invisible");
    mobileMenu.classList.toggle("animate-open-menu");
    mobileMenu.classList.toggle("scale-0");
    mobileMenu.classList.toggle("opacity-0");
    hamburgerBtn.classList.toggle("toggle-btn");
  };
  hamburgerBtn.addEventListener("click", toggleMenu);
  mobileMenu.addEventListener("click", toggleMenu);

  function sendToPost(articles) {
    if (articles) {
      articles.forEach((article) => {
        let link = article.querySelector("a.articleLink");
        article.addEventListener("click", function () {
          if (link && link.href) {
            window.location.href = link.href;
          }
        });
      });
    }
  }
  sendToPost(articles);

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
