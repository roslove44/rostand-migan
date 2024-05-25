import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  connect() {
    let typedSpans = document.querySelectorAll("span.typed-cursor");
    if (typedSpans.length > 0) {
      typedSpans[0].remove();
    }
  }
}
