<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import ProfilePicture from '@/Components/ProfilePicture.vue';
let props = defineProps( { title: String, stats: Object } );
</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-body h-100">
                    <div class="mail-list">
                        <Link :href="route('myaccount.messages', ['inbox'])"
                            :class="{ 'text-primary': ['MyAccount/Messages/Inbox', 'MyAccount/Messages/Single'].includes($page.component) }">
                        <i class="fa fa-envelope-open font-size-16 align-middle me-2"></i>
                        Inbox <span class="ms-1 float-end" v-if="stats.unread_count > 0">({{ stats.unread_count }})</span>
                        </Link>
                        <a href="#" class="disabled"><i class="fa fa-paper-plane font-size-16 align-middle me-2"></i>
                            Sent </a>
                        <a href="#" class="disabled"><i class="fa fa-trash font-size-16 align-middle me-2"></i>
                            Trashed </a>
                    </div>
                    <hr>
                    <h6>Administrators/Support</h6>
                    <div class="mail-list">
                        <Link :href="route('myaccount.messages', ['compose'])" :data="{ type: 'personal', id: user.id }"
                            class="d-flex text-reset" v-for="user in stats.administrators" :key="user.id">
                        <div class="flex-shrink-0">
                            <ProfilePicture size="25" :value="user.profile_picture"></ProfilePicture>
                        </div>
                        <div class="flex-grow-1 ms-2 small lh-sm ">
                            <p class="mb-0">{{ user.name }}</p>
                            <p class="text-muted text-truncate small mb-0">{{ user.email }}</p>
                        </div>
                        </Link>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <form action="" class="card col-md-4 shadow-sm">
                    <div class="input-group border shadow-sm ">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0"><i class="fa fa-search"></i></span>
                        </div>
                        <input class="form-control border-0 " type="search" placeholder="Search...">
                    </div>
                </form>

                <slot></slot>
            </div>
        </div>
    </AuthenticatedLayout>
</template>