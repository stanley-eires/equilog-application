<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import ProfilePicture from '@/Components/ProfilePicture.vue';
let props = defineProps( { title: String, stats: Object } );
</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card card-body h-100">
                    <div class="list-group  list-group-flush list-group-horizontal text-center mb-3">
                        <Link class="list-group-item" :href="route('myaccount.messages', ['inbox'])"
                            :class="{ 'active': ['MyAccount/Messages/Inbox'].includes($page.component) }">
                        <i class="fa fa-envelope-open font-size-16 align-middle me-2"></i>
                        Inbox <span class="ms-1 float-end" v-if="stats.unread_count > 0">({{ stats.unread_count }})</span>
                        </Link>
                        <Link class="list-group-item" :href="route('myaccount.messages', ['sent'])"
                            :class="{ 'active': ['MyAccount/Messages/Sent'].includes($page.component) }">
                        <i class="fa fa-paper-plane font-size-16 align-middle me-2"></i>
                        Sent </Link>
                    </div>
                    <details>
                        <summary>Administrators / Support</summary>
                        <div class="mail-list">
                            <Link :disabled="user.id == $page.props.auth.user.id" as="button"
                                :href="route('myaccount.messages', ['compose'])" :data="{ type: 'personal', id: user.id }"
                                class="btn text-start px-0 d-flex " v-for="user in stats.administrators" :key="user.id">
                            <div class="flex-shrink-0">
                                <ProfilePicture size="25" :value="user.profile_picture"></ProfilePicture>
                            </div>
                            <div class="flex-grow-1 ms-2  lh-sm ">
                                <p class="mb-0">{{ user.name }}</p>
                                <p class="text-muted text-truncate small mb-0">{{ user.email }}</p>
                            </div>
                            </Link>
                        </div>
                    </details>
                </div>
            </div>
            <div class="col-md-9">

                <slot></slot>
            </div>
        </div>
    </AuthenticatedLayout>
</template>