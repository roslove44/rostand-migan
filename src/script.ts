const initApp = () => {
  const mobileMenu:HTMLElement|null = document.querySelector("#mobileMenu");
  const hamburgerBtn:HTMLButtonElement|null = document.querySelector("#hamburger-btn");
  let articles:NodeListOf<HTMLElement> = document.querySelectorAll("article.sendToPost");
  let illustrations:NodeListOf<HTMLElement> = document.querySelectorAll(".illustration");
  let imageListForAnimation:NodeListOf<HTMLElement> = document.querySelectorAll("img, svg, i, .svg, .stack")

  const toggleMenu = () => {
    mobileMenu?.classList.toggle("invisible");
    mobileMenu?.classList.toggle("animate-open-menu");
    mobileMenu?.classList.toggle("scale-0");
    mobileMenu?.classList.toggle("opacity-0");
    hamburgerBtn?.classList.toggle("toggle-btn");
  };
  hamburgerBtn?.addEventListener("click", toggleMenu);
  mobileMenu?.addEventListener("click", toggleMenu);

  function sendToPost(articles:NodeListOf<HTMLElement>) {
    if (articles) {
      articles.forEach((article) => {
        let link:HTMLAnchorElement|null = article.querySelector("a.articleLink");
        article.addEventListener("click", function () {
          if (link && link.href) {
            window.location.href = link.href;
          }
        });
      });
    }
  }

  function serviceIllustrationAnimation(illustrations:NodeListOf<HTMLElement>) {
    // Fonction à exécuter lorsque l'élément entre dans la fenêtre d'affichage
    function handleIntersection(entries:Array<IntersectionObserverEntry>, observer:IntersectionObserver) {
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
    illustrations.forEach((illustration) => {
      observer.observe(illustration);
    });
  }

  function imageAnimation(list:NodeListOf<HTMLElement>) {
    function handleIntersection(entries:Array<IntersectionObserverEntry>, observer:IntersectionObserver) {
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

    list.forEach((image) => {
      imageObserver.observe(image);
    });
  }

  // Run functions
  sendToPost(articles);
  serviceIllustrationAnimation(illustrations);
  imageAnimation(imageListForAnimation);

}

document.addEventListener("DOMContentLoaded", initApp);