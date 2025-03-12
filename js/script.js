/**
 * December 2024 - Original JQuery code translated to vanilla
 *      JavaScript by ChatGPT-4o, then amended manually
 */
import { API_BASE_URL } from "./info.js";
import { createElement } from "./utils.js";

/**
 *  Initial poets load
 */
fetch(`${API_BASE_URL}?entity=catalog&information=authors`)
  .then((response) => response.json())
  .then((data) => {
    const cmbPoets = document.getElementById("cmbPoets");
    data.forEach((author) => {
      const option = createElement("option", { text: author });
      cmbPoets.appendChild(option);
    });
    // The first poet is selected automatically
    cmbPoets.dispatchEvent(new Event("change"));
  });

/**
 *  Poet selection
 */
document.getElementById("cmbPoets").addEventListener("change", function () {
  const selectedPoet = this.options[this.selectedIndex].text;

  // Load poet information
  fetch(`${API_BASE_URL}?entity=author&information=info&name=${encodeURIComponent(selectedPoet)}`)
    .then((response) => response.json())
    .then((info) => {
      let dates = `${info.birthPlace}, ${info.birthDate}`;
      if (info.deathDate !== undefined) {
        dates += `- ${info.deathPlace}, ${info.deathDate}`;
      }
      document.getElementById("poetInfo").textContent = dates;
    });

  // Load poet's list of poems
  fetch(`${API_BASE_URL}?entity=author&information=poems&name=${encodeURIComponent(selectedPoet)}`)
    .then((response) => response.json())
    .then((data) => {
      const poemsContainer = document.getElementById("poems");
      poemsContainer.innerHTML = "";

      const container = new DocumentFragment();
      data.forEach((poemTitle) => {
        const poemArticle = createElement("article");
        const poemHeader = createElement("h3", { text: poemTitle, class: "closedPoem" });

        poemHeader.addEventListener("click", poemHandler);

        poemArticle.appendChild(poemHeader);
        container.appendChild(poemArticle);
      });
      poemsContainer.append(container);
    });
});

/**
 * Handles the click event on the title of a poem,
 * which causes the text of the poem to be loaded
 */
function poemHandler() {
  const poemHeader = this;
  const poemArticle = poemHeader.parentNode;
  const selectedPoet = document.getElementById("cmbPoets").options[document.getElementById("cmbPoets").selectedIndex].text;

  // If the poem text is visible, remove it
  const poemTextDiv = poemArticle.querySelector("div");
  if (poemTextDiv) {
    poemHeader.classList.remove("openPoem");
    poemHeader.classList.add("closedPoem");
    poemTextDiv.remove();
  } else {
    // If the poem is not visible, load it

    // Original title: I heard a Fly buzz - when I died -
    // Title in API request: I-heard-a-Fly-buzz-/-when-I-died-/
    let poemTitle = poemHeader.textContent.replaceAll("-", "/").replaceAll(" ", "-");

    fetch(`${API_BASE_URL}?entity=poem&information=text&authorName=${encodeURIComponent(selectedPoet)}&title=${encodeURIComponent(poemTitle)}`)
      .then((response) => response.json())
      .then((data) => {
        const poemTextDiv = createElement("div", { html: data.text });
        poemHeader.classList.remove("closedPoem");
        poemHeader.classList.add("openPoem");
        poemArticle.appendChild(poemTextDiv);
      });
  }
}
