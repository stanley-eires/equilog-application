<script setup>
import { Link } from '@inertiajs/vue3';
import Index from "@/Pages/MyAccount/Messages/Index.vue";
import ProfilePicture from '@/Components/ProfilePicture.vue';
import { humanizeTime } from '@/helpers';
import moment from "moment";
import { computed, onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
let props = defineProps( { title: String, message: Object, stats: Object, segment: String } );

onMounted( () => {
    if ( usePage().props.auth.user.id == props.message.receiver_id && !props.message.read_at ) {
        useForm( {
            id: [ props.message.id ],
            read_at: moment().format( 'lll' ),
        } ).put( route( 'myaccount.updatemessage' ) )
    }
} );
let usertype = computed( () => props.segment == 'sent' ? 'sender_deleted_at' : 'receiver_deleted_at' );


</script>

<template>
    <Index :title="title" :stats="stats">
        <div class="card card-body" style="min-height:70vh">
            <div class="d-flex justify-content-between align-items-center my-3">
                <Link class="btn btn-sm p-0" :href="route('myaccount.messages', [segment])"><i
                    class="fa fa-arrow-left me-1"></i></Link>
                <div>
                    <Link method="PUT" :data="{ [usertype]: moment().format('lll'), segment, id: [message.id] }"
                        :href="route('myaccount.updatemessage')" class="btn py-0"><i class="fa fa-trash-o me-1"></i> Delete
                    </Link>
                    <Link v-if="segment == 'inbox' && !message.read_at" class="btn py-0" method="PUT"
                        :data="{ read_at: '', segment, id: [message.id] }" :href="route('myaccount.updatemessage')"><i
                        class="fa fa-envelope-o me-1"></i> Mark as unread</Link>
                </div>
            </div>
            <h3 class="mt-0 font-size-20 mb-3">{{ message.subject }}</h3>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-start mb-4">
                    <ProfilePicture :value="message.profile_picture" size="50">
                    </ProfilePicture>
                    <div class="flex-grow-1 ms-2">
                        <h5 class="font-size-14 my-1">{{ message.sender_name }}</h5>
                        <small class="text-muted">{{ message.email }}</small>
                    </div>
                </div>
                <small class="font-size-10"><span
                        class="d-none d-md-inline">{{ moment(message.created_at).format('lll') }}</span>
                    ({{ humanizeTime(message.created_at) }})</small>
            </div>
            <div v-html="message.message"></div>
        </div>
    </Index>
</template>