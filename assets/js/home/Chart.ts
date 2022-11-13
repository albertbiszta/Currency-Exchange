import { Chart as ChartJs } from 'chart.js';
import NumberOfDaysInput from './chart/NumberOfDaysInput';
import CurrencyCodeSelect from './chart/CurrencyCodeSelect';

class Chart {
    private currentChart: ChartJs<'line', Array<string>, unknown>;

    public constructor() {
        const event = new Event('currencyChartFormChange');
        const currencyCodeSelect = new CurrencyCodeSelect();
        const numberOfDaysInput = new NumberOfDaysInput();
        currencyCodeSelect.handleChange(event);
        numberOfDaysInput.handleChange(event);
        document.addEventListener('currencyChartFormChange', () => {
            this.build(currencyCodeSelect.value, numberOfDaysInput.value);
        });
        this.build(currencyCodeSelect.value, numberOfDaysInput.value);
    }

    private build(currencyCode: string, numberOfDays: number): void {
        fetch('/api/currency/chart', {
            method: 'POST',
            body: JSON.stringify({
                currencyCode,
                numberOfDays
            })
        })
            .then(chartConfig => chartConfig.json())
            .then(chartConfig => {
                this.currentChart && this.currentChart.destroy();
                this.currentChart = new ChartJs('currencyChart', chartConfig);
            });
    }
}

const homeChart = new Chart();
