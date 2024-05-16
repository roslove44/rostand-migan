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
      const clef = event.target.elements.clef;
      const value = event.target.elements.value;

      if (!(clef && value)) {
        this.addFlash("danger", "La clé et la valeur ne peuvent être vides.");
        return;
      }
      if (!event.submitter.dataset.submit) {
        const data = { _token: _token, clef: clef.value, value: value.value };
        this.#postInfo(data);
        return;
      }
      if (event.submitter.dataset.submit == "update") {
        const oldData = {
          clef: clef.dataset.value,
          value: value.dataset.value,
        };
        const data = { _token: _token, clef: clef.value, value: value.value };
        this.#updateInfo(oldData, data);
        return;
      }
    });
  }

  showAddForm(id, action, elementToReplace, data) {
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
    if (id == "updateForm") {
      addFormModel
        .querySelector("tr button[type='submit']")
        .setAttribute("data-submit", "update");
    }
    if (data) {
      addFormModel
        .querySelector("tr input[name='clef']")
        .setAttribute("value", data.clef);
      addFormModel
        .querySelector("tr input[name='clef']")
        .setAttribute("data-value", data.clef);
      addFormModel
        .querySelector("tr input[name='value']")
        .setAttribute("data-value", data.value);
      addFormModel
        .querySelector("tr input[name='value']")
        .setAttribute("value", data.value);
    }
    if (!elementToReplace) {
      this.infosContainerTarget.prepend(addFormModel);
      return;
    }
    elementToReplace.insertAdjacentElement(
      "beforebegin",
      addFormModel.querySelector("tr")
    );
    elementToReplace.setAttribute("data-ref", data.clef + "-" + data.value);
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
    const clef = infoContainer.querySelector(".clef").textContent;
    const value = infoContainer.querySelector(".value").textContent;
    const oldData = { clef, value };

    this.showAddForm("updateForm", "Mettre à jour", infoContainer, oldData);
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

  #updateInfo(oldData, data) {
    if (oldData.clef == data.clef && data.value == oldData.value) {
      this.addFlash("info", "Aucun changement détecté");
      return;
    }

    const update_url = "/api/info/update/" + oldData.clef;
    // Configuration de la requête
    const requestOptions = {
      method: "UPDATE",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    };
    fetch(update_url, requestOptions)
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
        document
          .querySelector(`tr[data-ref="${oldData.clef}-${oldData.value}"]`)
          .remove();
        const info = data.content;
        this.addInfoView(info.clef, info.value);
        this.addFlash("success", data.message);
      })
      .catch((error) => {
        this.addFlash("danger", "Une erreur technique s'est produite");
      });
  }
}
