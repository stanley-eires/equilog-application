<script setup>
import Index from "@/Pages/MyAccount/Messages/Index.vue";
import { truncate } from 'lodash'
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { humanizeTime } from '@/helpers';
defineProps( { title: String, inbox: Object, stats: Object } );

let stripTags = ( ( html ) => {
    let tmp = document.createElement( "DIV" );
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || "";
} )

</script>

<template>
    <Index :title="title" :stats="stats">
        <div class="card " style="min-height:50vh">
            <div class="table-responsive">
                <table class="table table-centered table-hover">
                    <tbody>
                        <template v-if="inbox.data.length > 0">

                            <tr v-for="(msg, index) in inbox.data" :key="index" :class="{ 'fw-bold': !msg.read_at }">
                                <td style="width:2px">
                                    <div class="form-check">
                                        <input id="my-input" class="form-check-input" type="checkbox">
                                        <label for="my-input" class="form-check-label"></label>
                                    </div>
                                </td>
                                <td style="width:10%" class="text-nowrap">
                                    <Link :href="route('myaccount.messages', ['inbox', msg.id])" class="title text-body">
                                    {{ msg.sender_name }}</Link>
                                </td>
                                <td style="max-width:240px" class="text-truncate">
                                    <Link :href="route('myaccount.messages', ['inbox', msg.id])" class="subjet text-body">
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
        </div>
    </Index>
</template>