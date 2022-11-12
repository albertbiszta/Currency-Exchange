class Chart {
  private numberOfDaysValue: number;

  public constructor() {
    const input = document.querySelector('input#currency-chart-number-of-days') as HTMLInputElement;
    this.setNumberOfDays(input.value);
    input.addEventListener('change', () => {
      this.setNumberOfDays(input.value)
      window.location.pathname.includes('/days') ? this.changeNumberOfDaysInUrl() : this.redirectToUrlWithDays();
    })
  }

  private changeNumberOfDaysInUrl(): void {
    window.location.pathname = window.location.pathname.replace(/\/days\/\d+/, this.getUrlWithDaysPath());
  }

  private getUrlWithDaysPath(): string {
    return `/days/${this.numberOfDaysValue}`;
  }

  private redirectToUrlWithDays(): void {
    window.location.pathname = window.location.pathname + this.getUrlWithDaysPath();
  }

  private setNumberOfDays(value): void {
    this.numberOfDaysValue = parseInt(value);
  }
}

const currencyChart = new Chart();
