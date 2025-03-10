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
        url: "../../server/queries.php",
        method: "POST",
        data: JSON.stringify({ id: queryId }),
        headers: { contentType: "application/json" },
      })
        .done((response) => {
          callback({
            queryId,
            queryText: response.query,
            queryResult: response.userResult,
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
    this.store.queryText = data.query;
  }
}
export { selectModel, selectView, selectController };
