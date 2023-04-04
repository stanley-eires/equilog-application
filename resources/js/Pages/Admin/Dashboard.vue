<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { formatCurrency, humanizeTime } from '@/helpers';
import moment from 'moment';
import ProfilePicture from '@/Components/ProfilePicture.vue';
import CounterUp from '@/Components/CounterUp.vue';
import InvoicesList from '@/Components/InvoicesList.vue';

const props = defineProps( { title: String, stats: Object, chartdata: Object } );
let state = ref( {
    options: {
        chart: {
            id: 'Sales-Analytics'
        },

        plotOptions: {
            bar: {
                barHeight: '100%',
                distributed: true,
                horizontal: true,
                dataLabels: {
                    position: 'bottom'
                },
            }
        },
        dataLabels: {
            enabled: true,
            textAnchor: 'start',
            style: {
                colors: [ '#fff' ]
            },
            formatter: function ( val, opt ) {
                return opt.w.globals.labels[ opt.dataPointIndex ] + ":  " + formatCurrency( val )
            },
            offsetX: 0,
            dropShadow: {
                enabled: true
            }
        },
        stroke: {
            width: 5,
            colors: [ '#fff' ]
        },
        xaxis: {
            categories: props.chartdata.map( e => `${ e.name }  (${ e.total_enrollment })` )
        },
        yaxis: {
            labels: {
                show: false,
            }
        },
    },
    series: [ {
        name: 'Revenue',
        data: props.chartdata.map( e => e.revenue )
    } ]
} )

</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="row">
            <div class="col-xl-8">
                <div class="card card-body">
                    <h4 class="card-title mb-4">Sales Analytics</h4>

                    <div class="mt-1">
                        <ul class="list-inline main-chart mb-0">
                            <li class="list-inline-item chart-border-left me-0 border-0">
                                <h3 class="text-primary"><counter-up
                                        :number="formatCurrency(chartdata.map(e => e.revenue).reduce((x, y) => x + y, 0))"></counter-up><span
                                        class="text-muted d-inline-block font-size-15 ms-3">Income</span></h3>
                            </li>
                            <li class="list-inline-item chart-border-left me-0">
                                <h4><counter-up
                                        :number="(chartdata.map(e => e.total_enrollment).reduce((x, y) => x + y, 0))"></counter-up><span
                                        class="text-muted d-inline-block font-size-15 ms-3">Sales</span>
                                </h4>
                            </li>
                            <li class="list-inline-item chart-border-left me-0">
                                <h4><counter-up
                                        :number="((stats.unique_user_enrolled / stats.users) * 100).toFixed(2)"></counter-up>%<span
                                        class="text-muted d-inline-block font-size-15 ms-3">Conversation Ratio</span>
                                </h4>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-3">
                        <apexchart type="bar" :height="350" :options="state.options" :series="state.series"></apexchart>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card card-body alert-light">
                    <div class="text-center">
                        <h1 class="text-primary display-5 fw-bolder "><counter-up :number="stats.users"></counter-up></h1>
                        <p class="text-muted mb-0">All Platform Users</p>
                    </div>
                </div>
                <div class="card card-body mb-3">
                    <h4 class="card-title mb-4">Top Users By Courses</h4>
                    <div data-simplebar style="max-height: 336px;">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless table-centered table-hover table-nowrap">
                                <tbody>
                                    <tr v-for="user in stats.top_users_by_courses" :key="user.user_id">
                                        <td>
                                            <div class="d-flex position-relative align-items-center">
                                                <div class="flex-shrink-0">
                                                    <ProfilePicture :value="user.profile_picture" size="25">
                                                    </ProfilePicture>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <Link class="text-reset"
                                                        :href="route('user.single', [user.user_id, 'courses'])">
                                                    {{ user.name }}</Link>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold text-end">{{ user.total_courses }} <span
                                                class="d-none d-lg-inline">course{{ user.total_courses == 1 ?
                                                    '' : 's' }}</span><br><span
                                                class="text-success">{{ formatCurrency(user.costs) }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xl-9">
                <div class="card">
                    <div class="small">
                        <h4 class="card-title card-body">Invoice Awaiting Admin Actions</h4>
                        <invoices-list :invoices="stats.awaiting_invoices"></invoices-list>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card card-body">
                    <h4 class="card-title mb-4">Recent Activity</h4>

                    <ol class="activity-feed mb-0 ps-1 small" data-simplebar style="max-height: 500px;overflow: auto;">
                        <li v-for="(feed, index) in stats.activities" class="feed-item" :key="index">
                            <div class="feed-item-list">
                                <small class="text-muted mb-1 font-size-13"
                                    :title="moment(feed.created_at).format('L hh:MM A')">{{ humanizeTime(feed.created_at) }}</small>
                                <p class="mt-0 mb-0" v-if="feed.actions == 'login'">
                                    <Link :href="route('user.single', [feed.user_id, 'profile'])">
                                    {{ feed.user_id == $page.props.auth.user.id ? "You" : feed.name }} </Link> signed in
                                    with
                                    IP
                                    {{ feed.value.ip }}
                                    using
                                    {{ feed.value.browser }} browser on a
                                    {{ feed.value.desktop ? feed.value.platform + ' PC' : 'Mobile Device' }}
                                </p>
                                <p class="mt-0 mb-0" v-else-if="feed.actions == 'user_actions'">
                                    <Link :href="route('user.single', [feed.user_id, 'profile'])">
                                    {{ feed.user_id == $page.props.auth.user.id ? "You " : feed.name }} </Link>
                                    <span class="mx-1"> {{ feed.value.type }} </span>
                                    <Link :href="route('user.single', [feed.value.id, 'profile'])">
                                    {{ feed.value.id == $page.props.auth.user.id ? " your profile " : (feed.value.id == feed.user_id ? ' his/her profile' : ' this user\'s profile') }}
                                    </Link>

                                </p>
                                <p class="mt-0 mb-0" v-else-if="feed.actions == 'course_actions'">
                                    <Link :href="route('user.single', [feed.user_id, 'profile'])">
                                    {{ feed.user_id == $page.props.auth.user.id ? "You " : feed.name }} </Link>
                                    <span>{{ feed.value.type }} </span>
                                    <Link :href="route('course.single', [feed.value.id, 'overview'])"> this course
                                    </Link>
                                </p>
                                <p class="mt-0 mb-0" v-else-if="feed.actions == 'invoice_actions'">
                                    <Link :href="route('user.single', [feed.user_id, 'profile'])">
                                    {{ feed.user_id == $page.props.auth.user.id ? "You " : feed.name }} </Link>
                                    <span>{{ feed.value.type }} </span>
                                    <Link :href="route('invoice', [feed.value.id])"> this invoice
                                    </Link>
                                </p>
                                <p class="mt-0 mb-0" v-else>Some unknown action was performed</p>
                            </div>
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
