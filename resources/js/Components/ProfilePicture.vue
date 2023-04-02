<script setup>
import { ref } from "vue";
import { useForm } from '@inertiajs/vue3';
import VueAvatar from "@webzlodimir/vue-avatar";
import "@webzlodimir/vue-avatar/dist/style.css";

let props = defineProps( {
    name: String,
    value: String,
    size: {
        type: [ String, Number ],
        default: '100',
    },
    userId: {
        type: [ String, Number ],
        required: true
    }
} )

let src = ref( props.value ? `/storage/${ props.value }` : '/assets/img/user.png' );
let error = ref( null );
const form = useForm( {
    _method: 'put',
    id: props.userId,
    profile_picture: null,
} );

let fileUploadForm = ref( null );

let handleAddFile = ( ev ) => {

    form.profile_picture = ev.target.files[ 0 ];
    form.post( route( 'user.update', [ props.userId ] ), {
        preserveScroll: true, onSuccess: () => {
            let reader = new FileReader();
            reader.onload = () => {
                src.value = reader.result
            };
            reader.readAsDataURL( ev.target.files[ 0 ] )
        },
    } )
}
</script>

<template>
    <vue-avatar class="wave-effect" @click.prevent='$refs.fileUploadForm.click()' :img-src="src" background-color="#f5f6f8"
        :size="props.size"></vue-avatar>
    <input class="form-control-sm form-control d-none" :disabled="!props.userId" @change='handleAddFile($event)'
        ref='fileUploadForm' type="file" accept='image/*'>
</template>

