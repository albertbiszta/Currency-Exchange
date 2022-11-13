class Exchange {
    private hiddenTargetCurrencyIndex: number = 0;
    private amountField: HTMLInputElement;
    private primaryCurrency: HTMLSelectElement;
    private targetCurrency: HTMLSelectElement;

    public constructor() {
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

    private watchPrimaryCurrencyChange(): void {
        this.setTargetCurrencyOptions();
        this.showResult();
    }

    private setTargetCurrencyOptions(): void {
        this.changeHiddenAttribute(false);
        this.hiddenTargetCurrencyIndex = this.primaryCurrency.selectedIndex;
        this.changeHiddenAttribute(true);
        if (this.targetCurrency.selectedIndex === this.hiddenTargetCurrencyIndex) {
            this.selectTargetCurrencyOption(this.hiddenTargetCurrencyIndex === 0 ? 1 : 0)
        }
    }

    private changeHiddenAttribute(value: boolean): void {
        this.targetCurrency.options[this.hiddenTargetCurrencyIndex].hidden = value;
    }

    private selectTargetCurrencyOption(index): void {
        this.targetCurrency.options[index].selected = true;
    }

    private showResult(): void {
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
                const infoField = document.querySelector('div.alert-primary') as HTMLDivElement;
                infoField.innerHTML = result;
                infoField.hidden = false;
            });
    }
}

const exchange = new Exchange();
