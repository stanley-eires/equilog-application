<script setup>
import Index from "@/Pages/MyAccount/Messages/Index.vue";
import ProfilePicture from '@/Components/ProfilePicture.vue';
import { humanizeTime } from '@/helpers';
import moment from "moment";
import { onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
let props = defineProps( { title: String, message: Object, stats: Object } );

onMounted( () => {
    if ( !props.message.read_at ) {
        useForm( {
            read_at: moment().format( 'lll' ),
        } ).put( route( 'myaccount.updatemessage', [ props.message.id ] ) )
    }
} )

</script>

<template>
    <Index :title="title" :stats="stats">
        <div class="card card-body" style="min-height:70vh">
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-start mb-4">
                    <ProfilePicture :value="message.profile_picture" size="50">
                    </ProfilePicture>
                    <div class="flex-grow-1 ms-2">
                        <h5 class="font-size-14 my-1">{{ message.sender_name }}</h5>
                        <small class="text-muted">{{ message.email }}</small>
                    </div>
                </div>
                <small>{{ moment(message.created_at).format('lll') }} ({{ humanizeTime(message.created_at) }})</small>
            </div>

            <h4 class="mt-0 font-size-16">{{ message.subject }}</h4>
            <div v-html="message.message"></div>
        </div>
    </Index>
</template>