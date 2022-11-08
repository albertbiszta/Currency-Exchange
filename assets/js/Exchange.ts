class Exchange {
    private hiddenTargetCurrencyIndex: number = 0;
    private amountField: HTMLInputElement;
    private primaryCurrency: HTMLSelectElement;
    private targetCurrency: HTMLSelectElement;

    constructor() {
        const formName: string = 'exchange_form';
        this.amountField = document.getElementById(`${formName}_amount`) as HTMLInputElement;
        this.primaryCurrency = document.getElementById(`${formName}_primaryCurrency`) as HTMLSelectElement;
        this.targetCurrency = document.getElementById(`${formName}_targetCurrency`) as HTMLSelectElement;
        this.amountField.addEventListener('change', () => isNaN(Number(this.amountField.value)) ? alert('Amount must be a number!') : this.showResult());
        this.primaryCurrency.addEventListener('change', () => this.watchPrimaryCurrencyChange());
        this.targetCurrency.addEventListener('change', () => this.showResult());
        this.changeHiddenAttribute(true);
        this.selectTargetCurrencyOption(1);
    }

    watchPrimaryCurrencyChange(): void {
        this.setTargetCurrencyOptions();
        this.showResult();
    }

    setTargetCurrencyOptions(): void {
        this.changeHiddenAttribute(false);
        this.hiddenTargetCurrencyIndex = this.primaryCurrency.selectedIndex;
        this.changeHiddenAttribute(true);
        if (this.targetCurrency.selectedIndex === this.hiddenTargetCurrencyIndex) {
            this.selectTargetCurrencyOption(this.hiddenTargetCurrencyIndex === 0 ? 1 : 0)
        }
    }

    changeHiddenAttribute(value: boolean): void {
        this.targetCurrency.options[this.hiddenTargetCurrencyIndex].hidden = value;
    }

    selectTargetCurrencyOption(index): void {
        this.targetCurrency.options[index].selected = true;
    }

    showResult(): void {
        fetch('/api/currency/conversion', {
            method: 'POST',
            body: JSON.stringify({
                'primaryCurrency': this.primaryCurrency.value,
                'targetCurrency': this.targetCurrency.value,
                amount: parseInt(this.amountField.value)
            })
        })
            .then(result => result.json())
            .then(result => {
                const alert = document.querySelector('div.alert-primary') as HTMLDivElement;
                alert.innerHTML = result;
                alert.hidden = false;
            });
    }
}

const exchange = new Exchange();
