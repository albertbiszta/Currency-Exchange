import {Chart as ChartJs} from "chart.js";

document.querySelector('select#currency-chart-select').addEventListener('change', (e) => {
    fetch(`/public/currency/get-chart/${e.target.value}`)
        .then(result => result.json())
        .then(result => {
            const labels = [];
            const rates = [];
            result.forEach((day) => {
                labels.push(day.date)
                rates.push(day.rate)
            });
            createChart(labels, rates);
        });
});

function createChart(labels, rates) {
 new ChartJs("currencyChart", {
        type: "line",
        data: {
            labels: labels,
            datasets: [{
                data: rates,
                borderColor: 'rgb(34, 72, 196)',
                fill: false,
                label:  'fluctuations in recent days',
            },]
        },
        options: {
            legend: {display: false}
        }
    });
}

