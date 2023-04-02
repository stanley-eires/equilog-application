<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { humanizeTime } from '@/helpers';
import moment from 'moment';
import Pagination from '@/Components/Pagination.vue';
import ProfilePicture from '@/Components/ProfilePicture.vue';


defineProps( { title: String, users: Object } );
let state = ref( {
    id: [],
} )

</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="row">
            <div class="col-12">
                <div class="card card-body" style="min-height:50vh">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-start mb-2">
                            <Link class="btn btn-primary btn-sm text-nowrap" :href="route('users.create')"><i
                                class="fa fa-plus-circle me-1"></i> Add New</Link>
                            <div class="ms-1" v-if="state.id.length > 0">
                                <Link method="post" class="btn btn-light mb-1 btn-sm me-1" :href="route('users.actions')"
                                    :data="{ status: 0, id: state.id }" :preserve-state="false"><i
                                    class="fa fa-times me-1 text-danger"></i>
                                Block</Link>
                                <Link :href="route('users.actions')" :data="{ status: 1, id: state.id }" method="post"
                                    :preserve-state="false" class="btn btn-light btn-sm me-1 mb-1"><i
                                    class="fa fa-undo me-1 text-primary"></i>
                                Unblock</Link>
                                <Link :href="route('users.delete')" :data="{ id: state.id }" method="delete"
                                    :preserve-state="false" class="btn btn-outline-danger btn-sm mb-1 me-1"><i
                                    class="fa fa-trash me-1"></i>
                                Delete</Link>
                            </div>
                            <div class="dropdown open ms-2">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="triggerId">
                                    <Link as="button" method="POST" class="dropdown-item" :href="route('seedings.users')">
                                    Seed Users
                                    </Link>
                                    <Link as="button" method="POST" class="dropdown-item"
                                        :href="route('seedings.testmail')">
                                    Test Email
                                    </Link>
                                </div>
                            </div>
                        </div>
                        <form action="">
                            <div class="input-group border shadow-sm ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-0"><i
                                            class="fa fa-search"></i></span>
                                </div>
                                <input class="form-control border-0 " type="search" placeholder="Search...">
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table  table-centered table-striped">
                            <thead class="text-nowrap">
                                <tr>
                                    <th style="width:2px">
                                        <div class="form-check pt-0">
                                            <input class="form-check-input" type="checkbox">
                                        </div>
                                    </th>
                                    <th>Users</th>
                                    <th></th>
                                    <th>Status</th>
                                    <th>Courses</th>
                                    <th>Date Joined</th>
                                    <th>Last Login</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="users.data.length > 0">
                                    <tr v-for="user in users.data" :key="user.id">
                                        <td>
                                            <div class="form-check pt-0">
                                                <input class="form-check-input" type="checkbox" :value="user.id"
                                                    v-model="state.id">
                                            </div>
                                        </td>
                                        <td class="text-uppercase">
                                            <div class="d-flex position-relative align-items-center">
                                                <div class="flex-shrink-0">
                                                    <ProfilePicture :value="user.profile_picture" size="25">
                                                    </ProfilePicture>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <Link :href="route('user.single', [user.id, 'profile'])">
                                                    {{ user.name }}</Link>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown open">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button" id="triggerId" data-bs-toggle="dropdown"><i
                                                        class="fa fa-cog me-1" aria-hidden="true"></i> <span
                                                        class="d-none d-md-inline">Actions</span></button>
                                                <div class="dropdown-menu">
                                                    <Link :href="route('user.edit', [user.id])" class="dropdown-item"><i
                                                        class="fa fa-cog me-1"></i> Edit
                                                    Candidate</Link>
                                                    <Link :href="route('user.single', [user.id, 'profile'])"
                                                        class="dropdown-item">
                                                    <i class="fa fa-search-plus me-1"></i>
                                                    View Profile</Link>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">

                                            <Link preserve-scroll v-if="user.status" class="btn p-0 "
                                                :href="route('users.actions')" :data="{ status: 0, id: [user.id] }"
                                                method="post"><i class="fa fa-check-circle me-1 text-success"></i>Active
                                            </Link>

                                            <Link preserve-scroll v-else class="btn p-0" :href="route('users.actions')"
                                                :data="{ status: 1, id: [user.id] }" method="post"><i
                                                class="fa fa-times-circle me-1  text-danger"></i>
                                            Block</Link>
                                        </td>
                                        <td class="fst-italic link-primary text-nowrap">
                                            <Link :href="route('user.single', [user.id, 'courses'])">
                                            {{ user.courses_count }}
                                            course{{ user.courses_count > 1 ? 's' : '' }}</Link>
                                        </td>
                                        <td class="text-nowrap">{{ moment(user.created_at).format('L') }}</td>
                                        <td class="text-capitalize fst-italic text-nowrap">
                                            {{ humanizeTime(user.login_at) }}
                                        </td>
                                        <td><a :href="`mailto:${user.email}`" class="btn btn-link"><i
                                                    class="fa fa-envelope me-1"></i>Send
                                                Message</a>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-else class="lead">
                                    <td colspan="8">No result found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination class="mt-5" :links="users.links" />
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
