export default class Logger {
  static log(...args) {
    if (process.env.NODE_ENV === "development") {
      console.log(...args);
      return;
    }
  }

  static error(...args) {
    if (process.env.NODE_ENV === "development") {
      console.error(...args);
      return;
    }
  }

  static info(...args) {
    if (process.env.NODE_ENV === "development") {
      console.info(...args);
      return;
    }
  }
}
