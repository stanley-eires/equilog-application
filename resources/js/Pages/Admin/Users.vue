<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { humanizeTime } from '@/helpers';
import moment from 'moment';
import Pagination from '@/Components/Pagination.vue';
import ProfilePicture from '@/Components/ProfilePicture.vue';


let props = defineProps( { title: String, users: Object, cohorts: Object, all_count: Number } );
let id = ref( [] )
let selectAll = ( ev ) => {
    id.value = ev.target.checked ? props.users.data.map( e => e.id ) : [];
}
let create_cohort_form = useForm( {
    name: '',
    description: ''
} )

</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="modal fade" id="cohort-create" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">New Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="modal-body"
                        @submit.prevent="create_cohort_form.post(route('cohort.save'), { 'preserve-state': false })">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" maxlength="15" v-model="create_cohort_form.name"
                                required autofocus>
                            <label>Cohort Name *</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" v-model="create_cohort_form.description"></textarea>
                            <label>Cohort Description</label>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn me-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" :disabled="create_cohort_form.processing" class="btn btn-primary"><span
                                    v-if="create_cohort_form.processing"
                                    class="spinner-border spinner-border-sm me-2"></span> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="card card-body p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Groups</h6>
                        <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#cohort-create">
                            <i class="fa fa-plus-circle"></i>
                        </button>
                    </div>
                    <ul class="list-group list-group-flush ">
                        <li class="list-group-item px-0  d-flex align-items-center justify-content-between">
                            <Link class="text-body w-100" :href="route('admin.users')">All ({{ all_count }})</Link>
                        </li>
                        <li v-for="i in cohorts" :key="i.id"
                            class="list-group-item px-0 d-flex align-items-center justify-content-between">
                            <Link :title="i.description" class="text-body w-100"
                                :href="route('admin.users', [{ cgi: i.id, cgn: i.name }])">{{ i.name }}
                            <span class="badge alert-primary   rounded-pill">{{ i.count }}</span>
                            </Link>
                            <div>
                                <div class="btn-group" role="group">
                                    <Link title="Send message to this group" v-if='i.count > 1' class="btn btn-sm"
                                        :href="route('myaccount.messages', ['compose'])"
                                        :data="{ type: 'group', id: i.id }">
                                    <i class="fa fa-envelope-o"></i>
                                    </Link>
                                    <Link title="Remove group" class="btn btn-sm" method="POST"
                                        :href="route('cohort.delete')" as="button" :data="{ id: i.id, _method: 'delete' }"
                                        :preserve-state="false"><i class="fa fa-trash-o"></i></Link>
                                    <Link title="Add selected users to this group" method="POST"
                                        :href="route('cohort.add-users')" as="button" :data="{ users: id, cohort: i.id }"
                                        :preserve-state="false" v-if="id.length > 0" class="btn btn-sm"><i
                                        class="fa fa-plus-square"></i></Link>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card card-body" style="min-height:50vh">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-start mb-2">
                            <template v-if="id.length == 0">
                                <Link class="btn btn-primary btn-sm text-nowrap" :href="route('users.create')"><i
                                    class="fa fa-plus-circle me-1"></i> Add New</Link>
                                <div class="dropdown open ms-2" v-if="$page.props.debug">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="triggerId">
                                        <Link as="button" method="POST" class="dropdown-item"
                                            :href="route('seedings.users')">
                                        Seed Users</Link>
                                        <Link as="button" method="POST" class="dropdown-item"
                                            :href="route('seedings.groups')">
                                        Seed Group</Link>
                                        <Link as="button" method="POST" class="dropdown-item"
                                            :href="route('seedings.testmail')">
                                        Test Email
                                        </Link>
                                    </div>
                                </div>
                            </template>
                            <div class="ms-1 d-flex" v-else>
                                <Link method="post" class="btn btn-light mb-1 btn-sm me-1" :href="route('users.actions')"
                                    :data="{ status: 0, id: id }" :preserve-state="false"><i
                                    class="fa fa-times me-1 text-danger"></i>
                                Block</Link>
                                <Link :href="route('users.actions')" :data="{ status: 1, id: id }" method="post"
                                    :preserve-state="false" class="btn btn-light btn-sm me-1 mb-1"><i
                                    class="fa fa-undo me-1 text-primary"></i>
                                Unblock</Link>
                                <Link
                                    onclick="return confirm('Are you sure you want to delete this user(s)? This action is irreversible')"
                                    :href="route('users.delete')" :data="{ id: id }" method="delete" :preserve-state="false"
                                    class="btn btn-outline-danger btn-sm mb-1 me-1"><i class="fa fa-trash me-1"></i>
                                Delete</Link>
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
                        <table class="table  table-centered table-striped table-sm small">
                            <thead class="text-nowrap">
                                <tr>
                                    <th style="width:2px">
                                        <div class="form-check pt-0">
                                            <input @change="selectAll($event)" class="form-check-input" type="checkbox">
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
                                                    v-model="id">
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
                                        <td>
                                            <Link :href="route('myaccount.messages', ['compose'])"
                                                :data="{ type: 'personal', id: user.id }" class="btn btn-link btn-sm"><i
                                                class="fa fa-envelope-o me-1"></i>Send
                                            Message</Link>
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
