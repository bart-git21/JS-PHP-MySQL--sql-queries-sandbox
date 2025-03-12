class selectModel {
  constructor(list) {
    this.list = list;
  }
}
class selectView {
  constructor() {
    this.select = $("#queriesSelect");
  }
  option({ id, name }) {
    return `
            <option value="${id}">${name}</option>
        `;
  }
  addChangeListener(callback) {
    this.select.on("change", function (event) {
      const queryId = event.target.value;
      $.ajax({
        url: `../../server/queries.php?id=${queryId}`,
        method: "POST",
        data: JSON.stringify({ id: queryId }),
        headers: { contentType: "application/json" },
      })
        .done((response) => {
          callback({
            queryId,
            queryName: response.query.name,
            queryText: response.query.query,
            queryResult: response.queryResult,
          });
        })
        .fail()
        .always();
    });
  }
}
class selectController {
  constructor(view, model) {
    this.view = view;
    this.model = model;
    this.store = {};
  }
  apendOptions() {
    this.model.list.forEach((elem) =>
      $("#queriesSelect").append(this.view.option(elem))
    );
    $("#queriesSelect").val("-1");
  }
  startChangeListener() {
    function handleChangedSelect(data) {
      this.store = data;
      $("#queryText").text(this.store.queryText);
      $("#table").html("");
    }

    this.view.addChangeListener(handleChangedSelect.bind(this));
  }
  update(data) {
    this.model.list.forEach((e) => {
      if (e.id === data.id) {
        e.name = data.name;
        e.query = data.query;
      }
    });
    if (!this.model.list.find(e => e.id === data.id)) {
        this.model.list.push(data);
        $("#queriesSelect").append(this.view.option(this.model.list.at(-1)))
    }
    this.store.queryName = data.name;
    this.store.queryText = data.query;
  }
}
export { selectModel, selectView, selectController };
