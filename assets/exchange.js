class Exchange {
    constructor() {
        const formName = 'exchange_form';
        this.amountField = document.getElementById(`${formName}_amount`);
        this.primaryCurrency = document.getElementById(`${formName}_primaryCurrency`);
        this.targetCurrency = document.getElementById(`${formName}_targetCurrency`);
        this.amountField.addEventListener('change', () => {
            fetch('/public/get-conversion-result', {
                method: 'POST',
                body: JSON.stringify({
                    'primaryCurrency': this.primaryCurrency.value,
                    'targetCurrency': this.targetCurrency.value,
                    amount: parseInt(this.amountField.value)
                })
            })
                .then(result => result.json())
                .then(result => console.log(result));
        });

    }

    showConversionResult() {
        console.log(this.primaryCurrency.value);

    }
}

const exchange = new Exchange();
