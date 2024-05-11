import { Controller } from "@hotwired/stimulus";

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
  static targets = ["portfolioProjects", "filterActions"];
  connect() {
    console.log(
      "Hello Stimulus! Edit me in assets/controllers/hello_controller.js"
    );
  }

  filter(event) {
    const projects = this.portfolioProjectsTarget.children;
    const button = event.currentTarget;
    this.setButtonActive(button);
    const filter = event.currentTarget.dataset.filter;
    if (filter == "*") {
      this.showAll(projects);
    } else {
      console.log(filter);
      this.showSpecificCategory(projects, filter);
    }
  }

  setButtonActive(button) {
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

  showAll(projects) {
    for (const project of projects) {
      project.classList.remove("isNotVisible");
      project.classList.add("animate-portfolio-action");
      setTimeout(() => {
        project.style.display = "flex";
      }, 300);
    }
  }

  showSpecificCategory(projects, filter) {
    for (const project of projects) {
      if (project.classList.contains(filter)) {
        project.classList.remove("isNotVisible");
        // project.classList.add("animate-portfolio-action");
        setTimeout(() => {
          project.style.display = "flex";
        }, 300);
      } else {
        project.classList.add("isNotVisible");
      }
    }

    setTimeout(() => {
      for (const project of projects) {
        if (project.classList.contains("isNotVisible")) {
          project.style.display = "none";
        }
      }
    }, 300);
  }
}
