class Chart {
  numberOfDaysValue;

  constructor() {
    const input = document.querySelector('input#currency-chart-number-of-days');
    this.setNumberOfDays(input.value);
    input.addEventListener('change', () => {
      this.setNumberOfDays(input.value)
      window.location.pathname.includes('/days') ? this.changeNumberOfDaysInUrl() : this.redirectToUrlWithDays();
    })
  }

  setNumberOfDays(value) {
    this.numberOfDaysValue = parseInt(value);
  }

  get urlWithDaysPath() {
    return `/days/${this.numberOfDaysValue}`;
  }

  changeNumberOfDaysInUrl() {
    window.location.pathname = window.location.pathname.replace(/\/days\/\d+/, this.urlWithDaysPath);
  }

  redirectToUrlWithDays() {
    window.location.pathname = window.location.pathname + this.urlWithDaysPath;
  }
}

const currencyChart = new Chart();
