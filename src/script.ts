const initApp = () => {
  const menulist:HTMLElement|null = document.querySelector("#menulist");
  const hamburgerBtn:HTMLButtonElement|null = document.querySelector("#hamburger-btn");
  let articles:NodeListOf<HTMLElement> = document.querySelectorAll("article.sendToPost");
  let illustrations:NodeListOf<HTMLElement> = document.querySelectorAll(".illustration");
  let imageListForAnimation:NodeListOf<HTMLElement> = document.querySelectorAll("img, svg, .svg, .stack")

  const toggleMenu = () => {
    menulist?.classList.toggle("is-visible");
    if (hamburgerBtn?.classList.contains('toggle-btn')) {
      menulist?.classList.add('animate-close-menu');
    }
    hamburgerBtn?.classList.toggle("toggle-btn");
  };
  hamburgerBtn?.addEventListener("click", toggleMenu);
  menulist?.addEventListener("click", toggleMenu);

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
  sendToPost(articles);
  serviceIllustrationAnimation(illustrations);
  imageAnimation(imageListForAnimation);

  const consoleMessage__ascii = `
    _____               _                      _       _              
  |  __ \\             | |                    | |     | |             
  | |__) |  ___   ___ | |_   __ _  _ __    __| |   __| |  ___ __   __
  |  _  /  / _ \\ / __|| __| / _\` || '_ \\  / _\` |  / _\` | / _ \\\\ \\ / /
  | | \\ \\ | (_) |\\__ \\| |_ | (_| || | | || (_| | | (_| ||  __/ \\ V / 
  |_|  \\_\\ \\___/ |___/ \\__| \\__,_||_| |_| \\__,_|  \\__,_| \\___|  \\_/`;
  
  const consoleMessage__partOne = "\n\nHey there 👋 \n";
  const consoleMessage__partTwo = "Want to explore the source code ? 🕵️‍♂️ \n\n";
  const consoleMessage__partThree = "Visit the github repository https://github.com/roslove44/rostand-migan \n\n\n"; 
  const consoleMessage__partFour = "Also, you can contact me on https://twitter.com/migan_rostand or via hello@rostand.dev ✨."

  console.log(consoleMessage__ascii, consoleMessage__partOne + consoleMessage__partTwo + consoleMessage__partThree + consoleMessage__partFour);

}

document.addEventListener("DOMContentLoaded", initApp);