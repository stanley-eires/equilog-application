<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import { formatCurrency, humanizeTime } from '@/helpers';
import moment from 'moment';
import { ref } from "vue";



let props = defineProps( { title: String, courses: Object } );
let id = ref( [] )

let selectAll = ( ev ) => {
    id.value = ev.target.checked ? props.courses.map( e => e.id ) : [];
}

</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="card card-body">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-start mb-2">
                    <Link as="button" :href="route('course.create')" class="btn btn-primary btn-sm text-nowrap"><i
                        class="fa fa-plus-circle me-1  "></i>
                    Add New</Link>
                    <div class="ms-1" v-if="id.length > 0">
                        <Link as="button" method="post" class="btn btn-light btn-sm me-1 mb-1"
                            :href="route('courses.actions')" :data="{ status: 1, id: id }" :preserve-state="false"><i
                            class="fa fa-check me-1 text-success"></i>
                        Enable</Link>
                        <Link as="button" :href="route('courses.actions')" :data="{ status: 0, id: id }" method="post"
                            :preserve-state="false" class="btn btn-light btn-sm me-1 mb-1"><i
                            class="fa fa-times me-1 text-danger"></i>
                        Disable</Link>
                        <Link as="button"
                            onclick="return confirm('Are you sure you want to delete this course(s)? This action is irreversible')"
                            :href="route('course.delete')" :data="{ id: id }" method="delete" :preserve-state="false"
                            class="btn btn-outline-danger btn-sm mb-1"><i class="fa fa-trash me-1"></i>
                        Delete</Link>
                    </div>
                    <div class="dropdown open ms-2">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu">
                            <Link as="button" method="POST" class="dropdown-item" :href="route('seedings.courses')">
                            Seed Courses
                            </Link>
                            <Link as="button" method="POST" class="dropdown-item" :href="route('seedings.courses.users')">
                            Seed Courses Users
                            </Link>
                        </div>
                    </div>
                </div>
                <form action="">
                    <div class="input-group border shadow-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0"><i class="fa fa-search"></i></span>
                        </div>
                        <input class="form-control border-0" type="search" placeholder="Search...">
                    </div>
                </form>
            </div>
            <div class="table-responsive" style="min-height:50vh">
                <table class="table table-centered table-hover">
                    <thead class="text-nowrap ">
                        <tr>
                            <th style="width:2px">
                                <div class="form-check pt-0">
                                    <input @change="selectAll($event)" class="form-check-input" type="checkbox">
                                </div>
                            </th>
                            <th>Course</th>
                            <th> </th>
                            <th style="width:120px">Status <i class="fa fa-question-circle-o"></i></th>
                            <th>Program</th>
                            <th>Total Enrolled</th>
                            <th>Cost</th>
                            <th>Duration</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="courses.length > 0">
                            <tr v-for="i in courses" :key="i.id">
                                <td>
                                    <div class="form-check pt-0">
                                        <input class="form-check-input" type="checkbox" :value="i.id" v-model="id">
                                    </div>
                                </td>
                                <td class=" text-uppercase">
                                    <Link class="d-flex" :href="route('course.single', [i.id, 'overview'])">
                                    <i class="fa fa-list-ul me-2"></i>{{ i.name }}
                                    </Link>
                                </td>
                                <td>
                                    <div class="dropdown open">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown"><i class="fa fa-cog"></i><span
                                                class="d-none d-md-inline ms-1">Actions</span></button>
                                        <div class="dropdown-menu">
                                            <Link class="dropdown-item" :href="route('course.single', [i.id, 'modify'])"><i
                                                class="fa fa-cog me-1"></i> Edit
                                            Course</Link>
                                            <Link :href="route('course.single', [i.id, 'enrollments'])"
                                                class="dropdown-item"><i class="fa fa-search-plus me-1"></i>
                                            View Enrolled</Link>

                                            <Link :href="route('course.delete')" :data="{ id: [i.id] }" method="delete"
                                                :preserve-state="false" class="dropdown-item"><i
                                                class="fa fa-trash me-1"></i>
                                            Delete
                                            Course</Link>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-nowrap ">
                                    <Link method="post" :href="route('courses.actions')" :data="{ status: 0, id: [i.id] }"
                                        v-if="i.status" class="btn p-0 "><i
                                        class="fa fa-check-circle  text-success me-1"></i>
                                    Enable</Link>
                                    <Link method="post" :href="route('courses.actions')" :data="{ status: 1, id: [i.id] }"
                                        v-else class="btn p-0"><i class="fa fa-times-circle   text-danger  me-1"></i>
                                    Disabled</Link>
                                </td>
                                <td>{{ i.program }}</td>
                                <td class="text-nowrap">
                                    <Link :href="route('course.single', [i.id, 'enrollments'])">
                                    {{ i.courses_count }} enrollment{{ i.courses_count == 1 ? '' : 's' }}</Link>
                                </td>
                                <td class="text-nowrap">{{ formatCurrency(i.cost) }}</td>
                                <td class="text-nowrap">{{ i.duration }} {{ i.period }}{{ i.duration > 1 ? 's' : '' }}</td>
                                <td>{{ moment(i.created_at).format('L') }}</td>
                            </tr>
                        </template>
                        <tr v-else class="lead">
                            <td colspan="9">No courses found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
