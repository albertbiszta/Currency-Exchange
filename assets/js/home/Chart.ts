import { Chart as ChartJs } from 'chart.js';
import StorageInput from '../storage/StorageInput';
import StorageSelect from '../storage/StorageSelect';

class Chart {
    private readonly selectLocalStorageKey: string = 'lastSelectedCurrencyOnChart';
    private readonly inputLocalStorageKey: string = 'lastSelectedNumberOfDaysOnChart';
    private currentChart: ChartJs<'line', Array<string>, unknown>;
    private currencyCode: string = 'eur';
    private numberOfDays: number = 7;
    private numberOfDaysInput: HTMLInputElement;

    public constructor() {
        this.handleCurrencyChange();
        this.handleNumberOfDaysChange();
        this.build();
    }

    private handleCurrencyChange(): void {
        const currencySelect = document.querySelector('select#currency-chart-select') as HTMLSelectElement;
        (new StorageSelect(this.selectLocalStorageKey)).handle(currencySelect);
        this.currencyCode = currencySelect.value;
        currencySelect.addEventListener('change', () => {
            this.currencyCode = currencySelect.value;
            this.build();
        })
    }

    private handleNumberOfDaysChange(): void {
        this.numberOfDaysInput = document.querySelector('input#currency-chart-number-of-days') as HTMLInputElement;
        const storageInput = new StorageInput(this.inputLocalStorageKey);
        storageInput.handle(this.numberOfDaysInput);
        this.setNumberOfDays();
        this.numberOfDaysInput.addEventListener('change', () => {
            if (parseInt(this.numberOfDaysInput.value) > 90 || parseInt(this.numberOfDaysInput.value) < 0) {
                alert('The range of days is from 1 to 90.');
                this.setNumberOfDaysInputValue();
            } else {
                this.setNumberOfDays();
                this.setNumberOfDaysInputValue();
                storageInput.save(this.numberOfDaysInput.value);
                this.build();
            }
        })
    }

    private setNumberOfDays(): void {
        this.numberOfDays = parseInt(this.numberOfDaysInput.value);
    }

    private setNumberOfDaysInputValue(): void {
        this.numberOfDaysInput.value = String(this.numberOfDays);
    }

    private build(): void {
        fetch('/api/currency/chart', {
            method: 'POST',
            body: JSON.stringify({
                currencyCode: this.currencyCode,
                numberOfDays: this.numberOfDays
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
