import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["infosContainer"];
  connect() {
    this.addFormModel = document.getElementById("addFormModel").content;
    this.messageModel = document.getElementById("messageModel").content;
    this.infoViewModel = document.getElementById("infoViewModel").content;

    addEventListener("submit", (event) => {
      event.preventDefault();
      const _token = event.submitter.dataset.token;
      const clef = event.target.elements.clef.value;
      const value = event.target.elements.value.value;

      if (clef && value) {
        const data = { _token: _token, clef: clef, value: value };
        const message = null;
        this.#postInfo(data);
      } else {
        this.addFlash("danger", "La clé et la valeur ne peuvent être vides.");
      }
    });

    this.#getInfos();
  }

  showAddForm() {
    if (document.querySelector("#addForm")) {
      return;
    }
    const addFormModel = this.addFormModel.cloneNode(true);
    addFormModel.querySelector("tr").setAttribute("id", "addForm");
    this.infosContainerTarget.prepend(addFormModel);
  }
  removeAddForm() {
    this.removeAllFlash();
    if (document.querySelector("#addForm")) {
      document.querySelector("#addForm").remove();
    }
  }

  addFlash(badge, message) {
    const messageModel = this.messageModel.cloneNode(true);
    messageModel.querySelector("tr").classList.add("flash-message");
    messageModel.querySelector("label").classList.add("alert-" + badge);
    messageModel.querySelector("span.message").textContent = message;
    this.infosContainerTarget.prepend(messageModel);
  }
  removeFlash(event) {
    const tr = event.target.parentNode.parentNode.parentNode;
    tr.remove();
  }
  removeAllFlash() {
    if (document.querySelectorAll(".flash-message")) {
      document.querySelectorAll(".flash-message").forEach((e) => {
        e.remove();
      });
    }
  }

  addInfoView(clef, value, id) {
    const infoViewModel = this.infoViewModel.cloneNode(true);
    infoViewModel.querySelector("tr").removeAttribute("data-gu");
    infoViewModel.querySelector("tr").removeAttribute("data-token");
    infoViewModel.querySelector("tr .clef").textContent = clef;
    infoViewModel.querySelector("tr .value").textContent = value;
    this.infosContainerTarget.prepend(infoViewModel);
  }
  deleteInfoView() {}

  #postInfo(data) {
    const post_url = this.addFormModel.querySelector("tr").dataset.pu;
    // Configuration de la requête
    const requestOptions = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    };
    fetch(post_url, requestOptions)
      .then((response) => {
        // if (!response.ok) {throw new Error("Erreur lors de la requête");}
        return response.json();
      })
      .then((data) => {
        data = JSON.parse(data);
        if (data.error) {
          this.addFlash("danger", data.error);
          return;
        }
        this.removeAddForm();
        console.log("Réponse reçue :", data);
        this.addFlash("success", data.message);
      })
      .catch((error) => {
        this.addFlash("danger", "Une erreur technique s'est produite");
      });
  }

  #getInfos() {
    const get_url = this.infoViewModel.querySelector("tr").dataset.gu;
    const _token = this.infoViewModel.querySelector("tr").dataset.token;
    const data = { _token: _token };
    // Configuration de la requête
    const requestOptions = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    };
    this.addFlash("warning", "En cours........");

    fetch(get_url, requestOptions)
      .then((response) => {
        // if (!response.ok) {throw new Error("Network response was not ok " + response.statusText);}
        this.removeAllFlash();
        return response.json();
      })
      .then((data) => {
        if (data.error) {
          this.addFlash("danger", data.error);
          return;
        }
        data = JSON.parse(data);
        if (!data.content.length) {
          this.addFlash("warning", "Aucune clé enregistrée pour le moment");
          return;
        }
        console.log(data.content);
        data.content.map((info) => this.addInfoView(info.clef, info.value));
      })
      .catch((error) => {
        this.addFlash("danger", "Une erreur technique s'est produite" + error);
      });
  }
}
