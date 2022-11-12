import StorageElement from './StorageElement';

export default class StorageSelect extends StorageElement {
    protected changeElementValue(element: HTMLInputElement, lastValue: string): void {
        element.value = lastValue;
    }
}