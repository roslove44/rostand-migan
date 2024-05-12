import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["submitBtn"];
  connect() {
    document.addEventListener("turbo:submit-start", () => {
      const loading = this.submitBtnTarget.querySelector("svg");
      const sendMessage = this.submitBtnTarget.querySelector("span");

      sendMessage.classList.add("hidden");

      loading.querySelector("path").classList.remove("opacity-0");
      loading.querySelector("circle").classList.remove("opacity-0");

      loading.querySelector("path").classList.add("opacity-75");
      loading.querySelector("circle").classList.add("opacity-25");
    });
  }
}
