<script setup>
import { truncate } from 'lodash'
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { humanizeTime } from '@/helpers';
import moment from "moment";
import { computed, ref } from 'vue';
let props = defineProps( { inbox: Object, type: String } );
let stripTags = ( ( html ) => {
    let tmp = document.createElement( "DIV" );
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || "";
} )
let id = ref( [] )
let selectAll = ( ev ) => {
    id.value = ev.target.checked ? props.inbox.data.map( e => e.id ) : [];
}
let usertype = computed( () => props.segment == 'sent' ? 'sender_deleted_at' : 'receiver_deleted_at' );

</script>
<template>
    <div class="table-responsive">
        <table class="table table-centered table-hover">
            <thead class="">
                <tr>
                    <td style="width:2px">
                        <div class="form-check">
                            <input @change="selectAll($event)" class="form-check-input" type="checkbox">
                        </div>
                    </td>
                    <td colspan="4">
                        <template v-if="id.length > 0">
                            <Link
                                :data="{ [usertype]: moment().format('lll'), id, segment, read_at: moment().format('lll') }"
                                title="Delete" method="PUT" :preserve-state="false" :href="route('myaccount.updatemessage')"
                                class="btn py-0"><i class="fa fa-trash-o me-1"></i>
                            </Link>
                            <Link :data="{ id, segment, read_at: moment().format('lll') }" title="Mark as read"
                                class="btn py-0" method="PUT" :preserve-state="false"
                                :href="route('myaccount.updatemessage')"><i class="fa fa-envelope-open-o me-1"></i> </Link>
                        </template>
                        <Link v-else title="Refresh" as="button" class="btn btn-sm"
                            :href="route('myaccount.messages', type)"><i class="fa fa-refresh" aria-hidden="true"></i>
                        </Link>
                    </td>
                </tr>
            </thead>
            <tbody>
                <template v-if="inbox.data.length > 0">

                    <tr v-for="(msg, index) in inbox.data" :key="index"
                        :class="{ 'fw-bold': type == 'inbox' && !msg.read_at }">
                        <td style="width:2px">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" :value="msg.id" v-model="id">
                            </div>
                        </td>
                        <td style="width:10%" class="text-nowrap">

                            <Link :href="route('myaccount.messages', [type, msg.id])" class="title text-body">
                            <strong>{{ type == 'inbox' ? 'From' : 'To' }}: </strong>{{ msg.sender_name }}</Link>
                        </td>
                        <td style="max-width:240px" class="text-truncate">
                            <Link :href="route('myaccount.messages', [type, msg.id])" class="subjet text-body">
                            {{ msg.subject }}
                            – <span class="text-muted small"
                                v-html="truncate(stripTags(msg.message), { length: 100 })"></span>
                            </Link>
                        </td>
                        <td style="width:70px" class="text-nowrap small">{{ humanizeTime(msg.created_at) }}</td>
                    </tr>
                </template>
                <tr v-else class="lead">
                    <td colspan="4">No message found</td>
                </tr>
            </tbody>
        </table>
        <div class="card-body">
            <pagination class="mt-2" :links="inbox.links" />
        </div>
    </div>
</template>

