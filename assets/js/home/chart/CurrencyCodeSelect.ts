import { ChartFormElementInterface } from './ChartFormElementInterface';
import StorageSelect from '../../storage/StorageSelect';

export default class CurrencyCodeSelect implements ChartFormElementInterface {
    readonly localStorageKey: string = 'lastSelectedCurrencyOnChart';
    readonly element: HTMLSelectElement;
    storage: StorageSelect;
    value: string;

    public constructor() {
        this.element = document.querySelector('select#currency-chart-select') as HTMLSelectElement;
        this.storage = new StorageSelect(this.localStorageKey);
        this.storage.handle(this.element);
    }

    public handleChange(eventToRun: Event): void {
        this.value = this.element.value;
        this.element.addEventListener('change', () => {
            this.value = this.element.value;
            document.dispatchEvent(eventToRun);
        });
    }
}
