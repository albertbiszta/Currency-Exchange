class Exchange {
  hiddenTargetCurrencyIndex = 0;

  constructor() {
    const formName = 'exchange_form';
    this.amountField = document.getElementById(`${formName}_amount`);
    this.primaryCurrency = document.getElementById(`${formName}_primaryCurrency`);
    this.targetCurrency = document.getElementById(`${formName}_targetCurrency`);
    this.amountField.addEventListener('change', () => isNaN(this.amountField.value) ? alert('Amount must be a number!') : this.showResult());
    this.primaryCurrency.addEventListener('change', () => this.watchPrimaryCurrencyChange());
    this.targetCurrency.addEventListener('change', () => this.showResult());
    this.changeHiddenAttribute(true);
    this.selectTargetCurrencyOption(1);
  }

  watchPrimaryCurrencyChange() {
    this.setTargetCurrencyOptions();
    this.showResult();
  }

  setTargetCurrencyOptions() {
    this.changeHiddenAttribute(false);
    this.hiddenTargetCurrencyIndex = this.primaryCurrency.selectedIndex;
    this.changeHiddenAttribute(true);
    if (this.targetCurrency.selectedIndex === this.hiddenTargetCurrencyIndex) {
      this.selectTargetCurrencyOption(this.hiddenTargetCurrencyIndex === 0 ? 1 : 0)
    }
  }

  changeHiddenAttribute(value) {
    this.targetCurrency.options[this.hiddenTargetCurrencyIndex].hidden = value;
  }

  selectTargetCurrencyOption(index) {
    this.targetCurrency.options[index].selected = true;
  }

  showResult() {
    fetch('/public/api/currency/conversion', {
      method: 'POST',
      body: JSON.stringify({
        'primaryCurrency': this.primaryCurrency.value,
        'targetCurrency': this.targetCurrency.value,
        amount: parseInt(this.amountField.value)
      })
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
