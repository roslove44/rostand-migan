import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["infosContainer", "infosCount"];
  connect() {
    this.addFormModel = document.getElementById("addFormModel").content;
    this.messageModel = document.getElementById("messageModel").content;
    this.infoViewModel = document.getElementById("infoViewModel").content;
    this._narnia = document.querySelector('input[name="_narnia"]').value;

    this.#getInfos();
    addEventListener("submit", (event) => {
      event.preventDefault();
      const _token = this._narnia;
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
  }

  showAddForm(id, action, elementToReplace) {
    if (!(id && action)) {
      id = "addForm";
      action = "Ajouter";
    }
    if (document.querySelector("#" + id)) {
      return;
    }
    const addFormModel = this.addFormModel.cloneNode(true);
    addFormModel.querySelector("tr").setAttribute("id", id);
    addFormModel.querySelector("tr button[type='submit']").textContent = action;
    if (!elementToReplace) {
      this.infosContainerTarget.prepend(addFormModel);
      return;
    }
    elementToReplace.insertAdjacentElement(
      "beforebegin",
      addFormModel.querySelector("tr")
    );
  }
  removeAddForm() {
    this.removeAllFlash();
    if (document.querySelector("#addForm")) {
      document.querySelector("#addForm").remove();
    }
    if (document.querySelector("#updateForm")) {
      document.querySelector("#updateForm").remove();
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

  addInfoView(clef, value) {
    const infoViewModel = this.infoViewModel.cloneNode(true);
    infoViewModel.querySelector("tr .clef").textContent = clef;
    infoViewModel.querySelector("tr .clef").setAttribute("data-clef", clef);
    infoViewModel.querySelector("tr .value").textContent = value;
    this.infosContainerTarget.prepend(infoViewModel);
  }
  #infoToDelete(event) {
    return event.target.parentNode.parentNode;
  }
  editInfo(event) {
    this.removeAddForm();
    const infoContainer = event.target.parentNode.parentNode;
    const Oldclef = infoContainer.querySelector(".clef").textContent;
    const oldValue = infoContainer.querySelector(".value").textContent;
    console.log(infoContainer);
    this.showAddForm("updateForm", "Mettre à jour", infoContainer);
  }

  deleteInfo(event) {
    if (confirm("Voulez vous vraiment supprimé cet info ?")) {
      const target =
        this.#infoToDelete(event).querySelector(".clef").textContent;
      const delete_url = "/api/info/delete/" + target;
      const data = { _token: this._narnia };
      // Configuration de la requête
      const requestOptions = {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      };
      fetch(delete_url, requestOptions)
        .then((response) => {
          // if (!response.ok) {throw new Error("Erreur lors de la requête");}
          return response.json();
        })
        .then((data) => {
          if (data.error) {
            this.addFlash("danger", data.message);
            return;
          }
          this.#infoToDelete(event).remove();
          console.log("Réponse reçue :", data);
          this.addFlash("success", data.message);
        })
        .catch((error) => {
          this.addFlash("danger", "Une erreur technique s'est produite");
        });
    }
  }

  #postInfo(data) {
    const post_url = "/api/info/post/";
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
        if (data.error) {
          this.addFlash("danger", data.message);
          return;
        }
        this.removeAddForm();
        console.log("Réponse reçue :", data);
        const info = data.content;
        this.addInfoView(info.clef, info.value);
        this.addFlash("success", data.message);
      })
      .catch((error) => {
        this.addFlash("danger", "Une erreur technique s'est produite");
      });
  }

  #getInfos() {
    const get_url = "/api/info/get/";

    this.addFlash("warning", "En cours........");

    fetch(get_url)
      .then((response) => {
        // if (!response.ok) {throw new Error("Network response was not ok " + response.statusText);}
        this.removeAllFlash();
        return response.json();
      })
      .then((data) => {
        if (data.error) {
          this.addFlash("danger", data.message);
          return;
        }
        console.log(data);
        const content = data.content;
        if (!content.length) {
          this.infosCountTarget.textContent = "0 info";
          this.addFlash("warning", "Aucune clé enregistrée pour le moment");
          return;
        }
        this.infosCountTarget.textContent = content.length + " " + "info(s)";
        content.map((info) => this.addInfoView(info.clef, info.value));
      })
      .catch((error) => {
        this.addFlash("danger", "Une erreur technique s'est produite" + error);
      });
  }
}
