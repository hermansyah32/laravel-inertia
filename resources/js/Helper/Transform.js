import Logger from "./Logger";

export class DataPaging {
  constructor({
    current_page,
    last_page,
    from,
    to,
    total,
    per_page,
    path,
    first_page_url,
    last_page_url,
    next_page_url,
    prev_page_url,
  }) {
    this.current_page = current_page;
    this.last_page = last_page;
    this.from = from;
    this.to = to;
    this.total = total;
    this.per_page = per_page;
    this.path = path;
    this.first_page_url = first_page_url;
    this.last_page_url = last_page_url;
    this.next_page_url = next_page_url;
    this.prev_page_url = prev_page_url;
  }
}

export class TransformConstants {
  static list(object) {
    const result = {};
    try {
      const temp = Object.entries(object);
      temp.forEach((element) => {
        result[`${element[0]}`] = Object.values(element[1]);
      });
    } catch (error) {
      Logger.log("TransformConstants.list :>> ", error);
    }

    return result;
  }

  static valueOf(object, id) {
    try {
      const temp = Object.values(object);
      const result = temp.filter((item) => {
        return item.id === id;
      });
      if (result.length < 1) return "";
      return result[0].name;
    } catch (error) {
      return "";
    }
  }
}

export class TransformData {
  /**
   * @typedef {Object} Paginate
   * @property {DataPaging} paging - Data paging structure
   * @property {Array} data - Data array
   */

  /**
   * Transform Laravel pagination result
   * @returns {Paginate|undefined}
   */
  static paginate(data) {
    if (!data || data.length < 1) return data;

    const paginationData = new DataPaging({ ...data });
    return { paging: paginationData, data: data.data };
  }
}
