<script setup>
import { onMounted, ref } from 'vue';
import counterUp from 'counterup2';
const props = defineProps( {
    delay: {
        default: 100,
        type: [ Number, String ]
    },
    duration: {
        default: 1200,
        type: [ Number, String ]
    },
    number: {
        type: [ Number, String ],
    }
} )
let counter = ref( null );

onMounted( () => {
    counterUp( counter.value, {
        duration: props.duration,
        delay: props.delay,
    } )
} )
</script>

<template>
    <span ref="counter">{{ number }}</span>
</template>


<style></style>