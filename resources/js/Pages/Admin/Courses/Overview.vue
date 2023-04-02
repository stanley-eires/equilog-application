<script setup>
import Index from "@/Pages/Admin/Courses/Index.vue";
import { Link } from "@inertiajs/vue3";
import { formatCurrency, humanizeTime } from '@/helpers';
import moment from 'moment';



defineProps( { title: String, course: Object } );

</script>

<template>
    <Index :title="title" :course_id="course.id">
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class='text-uppercase fw-bold'>Total Enrollment</span>
                            <h2 class=" mb-0">{{ course.total_enrollment }}</h2>
                        </div>
                        <i class="fa fa-users fa-3x"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class='text-uppercase fw-bold'>Total Completed</span>
                            <h2 class=" mb-0">{{ course.total_completed }} </h2>
                        </div>
                        <i class="fa fa-check-square fa-3x"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class='text-uppercase fw-bold'>REVENUE Generated</span>
                            <h2 class="mb-0 ">{{ formatCurrency(course.revenue) }}</h2>
                        </div>
                        <i class="fa fa-money-bill fa-3x"></i>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card card-body">
                    <h3 class="fw-bold ">{{ course.name }}</h3>
                    <hr>
                    <div v-html="course.description">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <table class='table table-centered'>
                        <tbody>
                            <tr>
                                <th>Program</th>
                                <td class='text-end'> {{ course.program }}</td>
                            </tr>
                            <tr>
                                <th>Duration</th>
                                <td class='text-end'>{{ course.duration }}
                                    {{ course.period }}{{ course.duration > 1 ? 's' : '' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Cost</th>
                                <td class='text-end'>{{ formatCurrency(course.cost) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td class='text-end'>
                                    <p v-if="course.status">
                                        <i class="fa fa-check-circle text-success me-1"></i>Enabled
                                    </p>
                                    <p v-else>
                                        <i class="fa fa-times-circle text-danger  me-1"></i>Disabled
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>Created</th>
                                <td class='text-end'><small>Admin
                                        User</small><br>{{ moment(course.created_at).format('L') }}</td>
                            </tr>
                            <tr>
                                <th>Modified</th>
                                <td class='text-end'><small>Admin
                                        User</small><br>{{ moment(course.created_at).format('L') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <a target="_blank" class="btn btn-primary waves-effect w-100"
                    :href="route('public.course.single', [course.slug])">View
                    Course <i class="fa fa-external-link ms-2" aria-hidden="true"></i></a>
            </div>
        </div>
    </Index>
</template>
