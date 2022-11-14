export default abstract class StorageElement {
    private readonly key: string;

    public constructor(localStorageKey: string) {
        this.key = localStorageKey;
    }

    public handle(element: HTMLElement): void {
        const lastValue = localStorage.getItem(this.key);
        if (lastValue) {
            this.changeElementValue(element, lastValue);
        }
    }

    public save(value: string) {
        localStorage.setItem(this.key, value);
    }

    protected abstract changeElementValue(element: HTMLElement, lastValue: string): void
}
