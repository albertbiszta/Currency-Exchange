class Chart {
  private numberOfDaysValue: number;

  constructor() {
    const input = document.querySelector('input#currency-chart-number-of-days') as HTMLInputElement;
    this.setNumberOfDays(input.value);
    input.addEventListener('change', () => {
      this.setNumberOfDays(input.value)
      window.location.pathname.includes('/days') ? this.changeNumberOfDaysInUrl() : this.redirectToUrlWithDays();
    })
  }

  getUrlWithDaysPath(): string {
    return `/days/${this.numberOfDaysValue}`;
  }

  setNumberOfDays(value): void {
    this.numberOfDaysValue = parseInt(value);
  }

  changeNumberOfDaysInUrl(): void {
    window.location.pathname = window.location.pathname.replace(/\/days\/\d+/, this.getUrlWithDaysPath());
  }

  redirectToUrlWithDays(): void {
    window.location.pathname = window.location.pathname + this.getUrlWithDaysPath();
  }
}

const currencyChart = new Chart();
