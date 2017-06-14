import _Vue = require("vue");

declare global {

    interface JQueryStatic {
        material: any;
    }

    interface Window {
        // test: any;
    }

    const Vue: typeof _Vue;
}
