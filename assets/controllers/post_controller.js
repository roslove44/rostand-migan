import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["copyToClipboardMessage"];
  connect() {}

  copyPostLinkToClipboard(event) {
    event.preventDefault();
    const currentUrl = window.location.href;
    if (navigator.clipboard.writeText(currentUrl)) {
      const copyButton = event.currentTarget;
      copyButton.classList.add("hidden");
      copyButton.classList.remove("flex");

      const successMessage = this.copyToClipboardMessageTargets;

      successMessage.forEach((element) => {
        element.classList.remove("opacity-0");
        element.classList.remove("scale-50");
        element.classList.add("scale-100");

        setTimeout(() => {
          element.classList.remove("scale-100");
          element.classList.add("opacity-0");
          element.classList.add("scale-50");

          setTimeout(() => {
            copyButton.classList.remove("hidden");
            copyButton.classList.add("flex");
          }, 300);
        }, 5000);
      });
    }
  }
}
