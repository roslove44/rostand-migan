import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["portfolioProjects", "filterActions"];
  connect() {}

  filter(event) {
    const projects = this.portfolioProjectsTarget.children;
    const button = event.currentTarget;
    this.#setButtonActive(button);
    const filter = event.currentTarget.dataset.filter;
    if (filter == "*") {
      this.#showAll(projects);
    } else {
      this.#showSpecificCategory(projects, filter);
    }
  }

  #setButtonActive(button) {
    if (button && button.classList.contains("active")) {
      return;
    }
    const filtersActive =
      this.filterActionsTarget.querySelectorAll("button.active");
    filtersActive.forEach((filter) => {
      filter.classList.remove("active");
    });

    button.classList.add("active");
  }

  #showAll(projects) {
    for (const project of projects) {
      project.classList.remove("isNotVisible");
      project.style.display = "none";
      project.classList.remove("animate-portfolio-action");

      setTimeout(() => {
        project.style.display = "flex";
        project.classList.add("animate-portfolio-action");
      }, 300);
    }
  }

  #showSpecificCategory(projects, filter) {
    for (const project of projects) {
      project.classList.add("isNotVisible");
      project.classList.remove("animate-portfolio-action");
      project.style.display = "none";

      if (project.classList.contains(filter)) {
        project.classList.remove("isNotVisible");
        setTimeout(() => {
          project.style.display = "flex";
          project.classList.add("animate-portfolio-action");
        }, 300);
      }
    }
  }
}
