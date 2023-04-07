<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from 'vue';
import Editor from '@tinymce/tinymce-vue';
let props = defineProps( {
    settings: Object
} );


const form = useForm( {
    allow_manual_registration: props.settings?.allow_manual_registration ?? true,
    allow_google_sso: props.settings?.allow_google_sso ?? false,
    google_client_id: props.settings?.google_client_id ?? '888479091012-0sfqr1n21hq3odmu1t277i4v349ghm8b.apps.googleusercontent.com',
    google_secret: props.settings?.google_secret ?? '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',
} )

let handleSave = () => form.post( route( 'admin.site-settings.save', [ 'auth' ] ) );

</script>
<template>
    <div>
        <h5 class="card-title mb-3">Authentication Options</h5>
        <div class="form-check form-switch mb-3">
            <label class="form-check-label" for="allow_manual_registration">Allow manual creation of account</label>
            <input class="form-check-input" type="checkbox" id="allow_manual_registration"
                v-model="form.allow_manual_registration">
        </div>
        <div class="form-check form-switch mb-3">
            <label class="form-check-label" for="allow_google_sso">Allow Single Sign On (SSO) with google</label>
            <input class="form-check-input" type="checkbox" id="allow_google_sso" v-model="form.allow_google_sso">
        </div>
        <fieldset v-if="form.allow_google_sso">
            <hr>
            <legend class="h6">Google OAuth Credentials</legend>
            <div class=" mt-3">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" required v-model="form.google_client_id">
                    <label>Client ID</label>
                </div>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" required v-model="form.google_secret">
                <label>Client secret</label>
            </div>
        </fieldset>
        <button @click.prevent="handleSave" :disabled="form.processing" class="btn btn-primary mt-3"><span
                v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span> Save
            Changes</button>
    </div>
</template>