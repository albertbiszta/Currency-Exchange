import { Chart as ChartJs } from 'chart.js';

class Chart {
  private currentChart: ChartJs<'line', Array<string>, unknown>;
  private currencyCode: string = 'usd';
  private numberOfDays: number = 7;
  private localStorageKey: string = 'lastSelectedCurrencyOnChart';

  constructor() {
    this.getChart();
    const select = document.querySelector('select#currency-chart-select') as HTMLSelectElement;
    const lastSelectedCurrencyIndex = localStorage.getItem(this.localStorageKey);
    if (lastSelectedCurrencyIndex) {
      select.selectedIndex = parseInt(lastSelectedCurrencyIndex);
    }

    select.addEventListener('change', () => {
      this.currencyCode = select.value;
      localStorage.setItem(this.localStorageKey, String(select.selectedIndex));
      this.getChart();
    })
    const numberOfDaysInput = document.querySelector('input#currency-chart-number-of-days') as HTMLInputElement;
    numberOfDaysInput.addEventListener('change', () => {
      if (parseInt(numberOfDaysInput.value) > 90 || parseInt(numberOfDaysInput.value) < 0) {
        alert('The range of days is from 1 to 90.');
      } else {
        this.numberOfDays = parseInt(numberOfDaysInput.value);
        this.getChart();
      }
      numberOfDaysInput.value = String(this.numberOfDays);
    })
  }

  getChart() {
    fetch('/api/currency/chart', {
      method: 'POST',
      body: JSON.stringify({
        currencyCode: this.currencyCode,
        numberOfDays: this.numberOfDays
      })
    })
      .then(chartConfig => chartConfig.json())
      .then(chartConfig => this.buildChart(chartConfig));
  }

  buildChart(chartConfig) {
    this.currentChart && this.currentChart.destroy();
    this.currentChart = new ChartJs('currencyChart', chartConfig);
  }
}

const homeChart = new Chart();
