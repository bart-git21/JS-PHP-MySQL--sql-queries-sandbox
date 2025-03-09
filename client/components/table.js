class TableModel {
  constructor(list) {
    this.list = list;
  }
}
class TableView {
  table([...args]) {
    let html = "";
    for (let col of args) {
      html += `<th scope="col">${col}</th>`;
    }
    return `
        <table class="table table-sm table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    ${html}
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    `;
  }
  row(object) {
    let html = "";
    for (let key in object) {
      key === "login" || key === "id"
        ? (html += `<th scope="row">${object[key]}</th>`)
        : (html += `<td>${object[key]}</td>`);
    }
    return `
                <tr>
                    ${html}
                </tr>`;
  }
  display(initTitles, data) {
    const title = initTitles ? initTitles : Object.keys(data[0]);
    $("#table").html(this.table(title));
    for (let item of data) {
      const requiredData = initTitles
        ? Object.assign(
            {},
            { login: item.login, name: item.name, query: item.query }
          )
        : item;
      $("#tableBody").append(this.row(requiredData));
    }
  }
}
class TableController {
  constructor(view, model) {
    this.view = view;
    this.model = model;
  }
  display(initTitles) {
    this.view.display(initTitles, this.model.list);
  }
}

export {TableModel, TableView, TableController};
