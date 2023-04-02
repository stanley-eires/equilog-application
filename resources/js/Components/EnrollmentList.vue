<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import moment from 'moment';
import Pagination from '@/Components/Pagination.vue';
import ProfilePicture from '@/Components/ProfilePicture.vue';

defineProps( { enrollments: Object, except: { default: () => [] } } );

</script>
<template>
    <div class="table-responsive" style="min-height:50vh">
        <table class="table table-centered table-hover">
            <thead class="text-nowrap">
                <tr>
                    <th style="width:3px">
                        <div class="form-check pt-0">
                            <input class="form-check-input" type="checkbox">
                        </div>
                    </th>
                    <th v-if="!except.includes('candidate')">Candidate</th>
                    <th v-if="!except.includes('course')">Course</th>
                    <th v-if="!except.includes('course')">Program</th>
                    <th>Invoice Ref. </th>
                    <th>Progress</th>
                    <th>Date Enrolled</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                <template v-if="enrollments.data.length > 0">
                    <tr v-for="(enrol, index) in enrollments.data" :key="index">
                        <td>
                            <div class="form-check pt-0">
                                <input class="form-check-input" type="checkbox">
                            </div>
                        </td>
                        <td class="text-uppercase" v-if="!except.includes('candidate')">
                            <div class="d-flex position-relative align-items-center">
                                <div class="flex-shrink-0">
                                    <ProfilePicture :value="enrol.profile_picture" size="25">
                                    </ProfilePicture>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <Link :href="route('user.single', [enrol.user_id, 'profile'])" class="stretched-link">
                                    {{ enrol.name }}</Link>
                                </div>
                            </div>
                        </td>

                        <td v-if="!except.includes('course')" class="text-uppercase">
                            <a target='_blank' v-if="enrol.course_slug"
                                :href="route('public.course.single', [enrol.course_slug])"><i
                                    class="fa fa-list-ul me-2"></i>{{ enrol.course_name }}
                            </a>
                            <Link v-else :href="route('course.single', [enrol.course_id, 'overview'])"><i
                                class="fa fa-list-ul me-2"></i>{{ enrol.course_name }}
                            </Link>
                        </td>
                        <td v-if="!except.includes('course')">{{ enrol.course_program }}</td>
                        <td>
                            <Link :href="route('invoice', enrol.invoice_id)">#{{ enrol.invoice_ref }}</Link>
                        </td>
                        <td>
                            <span v-if="enrol.progress">
                                <span class="text-nowrap"><i class="fa fa-check-circle text-success"></i>
                                    Completed</span><br>
                                <small>{{ enrol.date_completed }}</small>
                            </span>
                            <span v-else>--</span>
                        </td>
                        <td>{{ moment(enrol.created_at).format('L') }}</td>
                        <td>
                            <div class="dropdown">
                                <Link as="button" :disabled="enrol.progress"
                                    class="btn btn-sm btn-outline-secondary  dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fa fa-cog me-1"></i> Actions</Link>
                                <div class="dropdown-menu ">
                                    <Link as="button" preserve-scroll class="dropdown-item"
                                        :href="route('users.course.progress')"
                                        :data="{ progress: 1, date_completed: moment().format('lll'), id: [enrol.id] }"
                                        method="post"><i class="fa fa-check me-1"></i> Mark Completed</Link>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr v-else class="lead">
                    <td colspan="6">No record of user enrollment for this course</td>
                </tr>
            </tbody>
        </table>
    </div>
    <pagination class="mt-5" :links="enrollments.links" />
</template>