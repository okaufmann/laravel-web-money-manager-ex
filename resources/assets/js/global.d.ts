import _Vue = require("vue");
import _moment = require("moment");

declare global {

    interface JQueryStatic {
        material: any;
    }

    interface Window {
        // test: any;
    }

    const Vue: typeof _Vue;
    const moment: typeof _moment;
    const Lang: ILaravelJsLocalization;
}

interface ILaravelJsLocalization {
    /**
     * Returns a translation message with the given replacements.
     *
     * @param key {string} The key of the message.
     * @param replacements {object} The replacements to be done in the message.
     *
     * @return {string} The translation message, if not found the given key.
     */
    get(key: string, replacements: any): string;

    /**
     * Returns a translation message.
     *
     * @param key
     */
    get(key: string): string;

    /**
     * Set messages source.
     *
     * @param messages {object} The messages source.
     *
     * @return void
     */
    setMessages(any): void;

    /**
     * Returns true if the key is defined on the messages source.
     *
     * @param key {string} The key of the message.
     *
     * @return {boolean} true if the given key is defined on the messages source, otherwise false.
     */
    has(key: string): boolean;

    /**
     * Set the current locale.
     *
     * @param locale {string} The locale to set.
     *
     * @return void
     */
    setLocale(locale: string): void;

    /**
     * Get the current locale.
     *
     * @return {string} The current locale.
     */
    getLocale(): string;

    /**
     * Gets the plural or singular form of the message specified based on an integer value.
     *
     * @param key {string} The key of the message.
     * @param count {integer} The number of elements.
     * @param replacements {object} The replacements to be done in the message.
     *
     * @return {string} The translation message according to an integer value.
     */
    choice(key: string, count: number, replacements: any[]);
}

