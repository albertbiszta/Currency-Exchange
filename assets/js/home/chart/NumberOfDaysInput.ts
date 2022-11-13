import { ChartFormElementInterface } from './ChartFormElementInterface';
import StorageInput from '../../storage/StorageInput';

export default class NumberOfDaysInput implements ChartFormElementInterface{
    readonly localStorageKey: string = 'lastSelectedNumberOfDaysOnChart';
    readonly element: HTMLInputElement;
    storage: StorageInput;
    value: number = 7;

    public constructor() {
        this.element = document.querySelector('input#currency-chart-number-of-days') as HTMLInputElement;
        this.storage = new StorageInput(this.localStorageKey);
        this.storage.handle(this.element);
        this.setValue();
    }

    public handleChange(eventToRun: Event): void {
        this.element.addEventListener('change', () => {
            if (!this.isValueInRange()) {
                alert('The range of days is from 1 to 90.');
                this.setElementValue();
                return;
            }
            this.setValue();
            this.setElementValue();
            this.storage.save(this.element.value);
            document.dispatchEvent(eventToRun);
        });
    }
    
    private isValueInRange(): boolean {
        return parseInt(this.element.value) > 0 && parseInt(this.element.value) < 90;
    }

    private setElementValue(): void {
        this.element.value = String(this.value);
    }
    
    private setValue() {
        this.value = parseInt(this.element.value);
    }
}