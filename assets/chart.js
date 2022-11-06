import { Chart as ChartJs } from 'chart.js';

class Chart {
  currentChart = '';
  currency = 'usd';
  numberOfDays = 7;

  constructor() {
    this.getChart(this.currency, this.numberOfDays);
    document.querySelector('select#currency-chart-select').addEventListener('change', (e) => {
      this.currency = e.target.value;
      this.getChart();
    })
    const numberOfDaysInput = document.querySelector('input#currency-chart-number-of-days');
    numberOfDaysInput.addEventListener('change', (e) => {
      if (parseInt(e.target.value) > 90 || parseInt(e.target.value) < 0) {
        alert('The range of days is from 1 to 90.');
      } else {
        this.numberOfDays = parseInt(e.target.value);
        this.getChart();
      }
      numberOfDaysInput.value = this.numberOfDays;
    })
  }

  getChart() {
    fetch('/api/currency/chart', {
      method: 'POST',
      body: JSON.stringify({
        currency: this.currency,
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

  createChart(labels, rates) {
    this.currentChart && this.currentChart.destroy();
    this.currentChart = new ChartJs('currencyChart', {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          data: rates,
          borderColor: 'rgb(34, 72, 196)',
          fill: false,
          label: this.currency.toUpperCase() + ' fluctuations in recent days',
        },]
      },
      options: { legend: { display: false } }
    });
  }

  formatRate(rate) {
    return (Number(rate)).toPrecision(4);
  }
}

const chart = new Chart();
