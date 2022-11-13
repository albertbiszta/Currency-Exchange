import StorageElement from '../../storage/StorageElement';

export interface ChartFormElementInterface {
    readonly localStorageKey: string;
    readonly element: HTMLElement;
    storage: StorageElement;
    value: string | number;

    handleChange(eventToRun: Event): void;
}