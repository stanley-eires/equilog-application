<script setup>
import { Toast } from 'bootstrap';
import moment from 'moment';
import { onMounted, ref } from 'vue';
defineProps( {
    title: String,
    content: String,
    status: String
} )

let toastele = ref( null )
let time;
onMounted( () => {
    time = Date.now();
    let toast = new Toast( toastele.value )
    toast.show();
}, )
</script>

<template>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 100000">
        <div class="toast-container">
            <div ref="toastele" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true"
                data-bs-delay="60000">
                <div class="toast-header" :class="`bg-${status ?? 'white'} ${status ? 'text-white' : 'text-dark'}`">
                    <strong class="me-auto">{{ title }}</strong>
                    <small v-if="time">{{ moment(time).fromNow() }}</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body" v-if="content" v-html="content">
                </div>
            </div>
        </div>
    </div>
</template>


<style></style>