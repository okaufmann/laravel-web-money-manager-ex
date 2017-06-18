<template>
    <vuetable ref="vuetable"
              api-url="https://vuetable.ratiw.net/api/users"
              :fields="['name', 'email', 'birthdate']"
    ></vuetable>
</template>

<script lang="ts">
    let Vuetable = require('vuetable-2/src/components/Vuetable');

    export default {
        components: {
            Vuetable
        }
    }
</script>