import { Chart as ChartJs } from 'chart.js';

class Chart {
  private currentChart: ChartJs<'line', Array<string>, unknown>;
  private currencyCode: string = 'usd';
  private numberOfDays: number = 7;

  constructor() {
    this.getChart();
    const select = document.querySelector('select#currency-chart-select') as HTMLSelectElement;
    select.addEventListener('change', () => {
      this.currencyCode = select.value;
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
      .then(result => result.json())
      .then(result => {
        const labels = [];
        const rates = [];
        result.forEach((day) => {
          labels.push(day.date)
          rates.push(this.formatRate(day.rate))
        });
        this.createChart(labels, rates);
      });
  }

  createChart(labels: Array<string>, rates: Array<string>) {
    this.currentChart && this.currentChart.destroy();
    this.currentChart = new ChartJs('currencyChart', {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          data: rates,
          borderColor: 'rgb(34, 72, 196)',
          fill: false,
          label: this.currencyCode.toUpperCase() + ' fluctuations in recent days',
        },
        ]
      },
    });
  }

  formatRate(rate) {
    return (Number(rate)).toPrecision(4);
  }
}

const homeChart = new Chart();
