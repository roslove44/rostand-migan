"use strict";
const initApp = () => {
  let illustrations = document.querySelectorAll(".illustration");
  let imageListForAnimation = document.querySelectorAll(
    "img, svg, .svg, .stack"
  );

  function serviceIllustrationAnimation(illustrations) {
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
    illustrations.forEach((illustration) => {
      observer.observe(illustration);
    });
  }
  function imageAnimation(list) {
    function handleIntersection(entries, observer) {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          setTimeout(() => {
            entry.target.classList.add("is-loaded");
          }, 400);
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
  serviceIllustrationAnimation(illustrations);
  imageAnimation(imageListForAnimation);
};

document.addEventListener("turbo:load", initApp);
