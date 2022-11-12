import StorageElement from './StorageElement';

export default class StorageSelect extends StorageElement {
    public handle(element:HTMLSelectElement): void {
        super.handle(element);
        element.addEventListener('change', () => this.save(String(element.selectedIndex)));
    }

    protected changeElementValue(element: HTMLSelectElement, lastValue: string): void {
        element.selectedIndex = parseInt(lastValue);
    }
}