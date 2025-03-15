class selectModel {
  constructor(list) {
    this.list = list;
  }
}
class selectView {
  constructor(selector) {
    this.$selector = selector;
  }
  createOptions(list) {
    list.forEach((elem) =>
      $("#queriesSelect").append(this.option(elem))
    );
    $("#queriesSelect").val("-1");
  }
  option({ id, name }) {
    return `
            <option value="${id}">${name}</option>
        `;
  }
  addChangeListener(callback) {
    $(this.$selector).on("change", function (event) {
      const id = event.target.value;
      $.ajax({
        url: `../../server/queries.php?id=${id}`,
        method: "POST",
        data: JSON.stringify({ id }),
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
  create() {
    this.view.createOptions(this.model.list);
  }
  startChangeListener() {
    function handleChangedSelect(data) {
      this.store = data;
      $("#queryText").text(this.store.queryText);
      $("#table").empty();
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
    if (!this.model.list.find((e) => e.id === data.id)) {
      this.model.list.push(data);
      $("#queriesSelect").append(this.view.option(this.model.list.at(-1)));
    }
    this.store.queryName = data.name;
    this.store.queryText = data.query;
  }
}
export { selectModel, selectView, selectController };
