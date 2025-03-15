class selectModel {
  constructor(list) {
    // interface List {
    //     id: number;
    //     name: string;
    //     query: string;
    //     userId: number;
    // }
    this.list = list;
  }
  update(data) {
    const editedQuery = this.list.find((e) => e.id === data.id);
    if (editedQuery) {
      editedQuery.name = data.name;
      editedQuery.query = data.query;
    } else this.list.push(data);
  }
}
class selectView {
  constructor(selector) {
    this.$selector = selector;
  }
  createOptions(list) {
    list.forEach((elem) => this.addOption(elem));
    $(this.$selector).val("-1");
  }
  addOption({ id, name }) {
    $(this.$selector).append(`<option value="${id}">${name}</option>`);
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
            id,
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
    // interface DBTableStringData {
    //     someColumn: someType;
    // }
    // interface Data {
    //     id: number;
    //     queryName: string,
    //     queryText: string,
    //     queryResult: DBTableStringData[],
    // }
    function handleChangedSelect(data) {
      this.store = data;
      $("#queryText").text(this.store.queryText);
      $("#table").empty();
    }

    this.view.addChangeListener(handleChangedSelect.bind(this));
  }
  //   interface Data {
  //     id: number;
  //     name: string;
  //     query: string;
  //     userID: string;
  //   }
  update(data) {
    const modelLength = this.model.list.length;
    this.model.update(data);
    modelLength < this.model.list.length &&
      this.view.addOption(this.model.list.at(-1));
    this.store.queryName = data.name;
    this.store.queryText = data.query;
  }
}
export { selectModel, selectView, selectController };
