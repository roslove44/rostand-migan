import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["close"];
  connect() {
    console.log(this.element);
    if (this.element) {
      setTimeout(() => {
        this.element.classList.add("opacity-0");
        this.element.classList.add("scale-0");
        setTimeout(() => {
          this.element.remove();
        }, 500);
      }, 10000);
    }
  }
  remove() {
    this.element.remove();
  }
}
