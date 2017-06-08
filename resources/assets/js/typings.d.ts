import _Vue = require("vue");

declare var Vue: typeof _Vue;
declare global {
    const Vue: typeof _Vue
}

interface Window {
    Vue: typeof _Vue
}