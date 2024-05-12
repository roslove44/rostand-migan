import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["postsContainer"];
  connect() {
    const posts = this.postsContainerTarget.querySelectorAll("article");
    for (const post of posts) {
      let postPageUrl = post.querySelector("a.articleLink").href;
      post.addEventListener("click", function () {
        if (postPageUrl) {
          Turbo.visit(postPageUrl);
        }
      });
    }
  }
}
