<script setup>
import Index from "@/Pages/MyAccount/Messages/Index.vue";
import { useForm } from "@inertiajs/vue3";
import Editor from '@tinymce/tinymce-vue';
import ProfilePicture from '@/Components/ProfilePicture.vue';

let props = defineProps( { title: String, option: Object, stats: Object } );
let form = useForm( {
    mode: props.option.mode,
    id: props.option.data.id,
    subject: '',
    message: '',
    as_email: true,
} );
let handleSubmit = () => {
    form.post( route( 'myaccount.sendmessage' ) );
}

</script>

<template>
    <Index :title="title" :stats="stats">
        <form method="post" @submit.prevent="handleSubmit" class="card card-body">
            <label for="">Recepients</label>
            <div class="d-flex mb-3" v-if="option.mode == 'group'">
                <div class="flex-shrink-0">
                    <i class="fa fa-users me-1  fa-3x "></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <p class="mb-0">{{ option.data.name }} </p>
                    <small class="small">{{ option.data.count }} users</small>
                </div>
            </div>
            <div class="d-flex mb-3" v-else>
                <div class="flex-shrink-0">
                    <ProfilePicture :value="option.data.profile_picture" size="40">
                    </ProfilePicture>
                </div>
                <div class="flex-grow-1 ms-3">
                    <p class="mb-0">{{ option.data.name }} </p>
                    <small class="small">{{ option.data.email }}</small>
                </div>
            </div>
            <div class="form-group mb-3">
                <input type="text" placeholder="Subject" v-model="form.subject" required class="form-control"
                    maxlength="100">
            </div>
            <div class="mb-3">
                <editor v-model="form.message" api-key="dk2z3jooyy5g31ivw3ah1ohaaj5jhc9pyq35bfwvcktbj3hl" :init="{
                    height: '220',
                    menubar: true,
                    plugins: 'code'
                }" class="w-100 form-control"></editor>
            </div>
            <div class="my-3">
                <strong>Dynamic Insert:</strong>
                <code>{$name}</code> <code>{$email}</code> (Would replace tags with actual values when exist)
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" v-model="form.as_email" type="checkbox" id="flexSwitchCheckDefault">
                <label class="form-check-label" for="flexSwitchCheckDefault">Send a copy of this message to
                    {{ option.mode == 'group' ? "recepients email addresses" : "recepient email address" }} </label>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"> <i class="fa fa-paper-plane-o me-2" aria-hidden="true"></i>
                    Send
                    Message</button>
            </div>
        </form>
    </Index>
</template>