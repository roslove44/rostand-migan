import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["hamburgerBtn", "menuList"];
  connect() {
    const menuList = this.menuListTarget;
    const hamburgerBtn = this.hamburgerBtnTarget;
    addEventListener("turbo:before-cache", (event) => {
      menuList.classList.remove("animate-close-menu");
      hamburgerBtn.classList.remove("toggle-btn");
      menuList.classList.remove("is-visible");
    });
  }

  toggleMenu() {
    const menuList = this.menuListTarget;
    const hamburgerBtn = this.hamburgerBtnTarget;
    if (menuList && hamburgerBtn) {
      if (hamburgerBtn.classList.contains("toggle-btn")) {
        menuList.classList.remove("is-visible");
        menuList.classList.add("animate-close-menu");
        setTimeout(() => {
          hamburgerBtn.classList.remove("toggle-btn");
          menuList.classList.remove("animate-close-menu");
        }, 300);
      } else {
        hamburgerBtn.classList.add("toggle-btn");
        menuList.classList.add("is-visible");
        menuList.classList.remove("invisible");
      }
    }
  }
}
