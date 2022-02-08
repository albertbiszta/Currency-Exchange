class Exchange {
    constructor() {
        const formName = 'exchange_form';
        this.amountField = document.getElementById(`${formName}_amount`);
        this.primaryCurrency = document.getElementById(`${formName}_primaryCurrency`);
        this.targetCurrency = document.getElementById(`${formName}_targetCurrency`);
        this.amountField.addEventListener('change', () => isNaN(this.amountField.value) ? alert('Amount must be a number!') : this.showResult());
        this.primaryCurrency.addEventListener('change', () => this.showResult());
        this.targetCurrency.addEventListener('change', () => this.showResult());
    }

    showResult() {
        fetch('/public/get-conversion-result', {
            method: 'POST',
            body: JSON.stringify({'primaryCurrency': this.primaryCurrency.value, 'targetCurrency': this.targetCurrency.value, amount: parseInt(this.amountField.value)})
        })
            .then(result => result.json())
            .then(result => {
                const alert = document.querySelector('div.alert-primary');
                alert.innerHTML = `${this.amountField.value} ${this.primaryCurrency.value} = ${result} ${this.targetCurrency.value}`;
                alert.hidden = false;
            });
    }


}
const exchange = new Exchange();
