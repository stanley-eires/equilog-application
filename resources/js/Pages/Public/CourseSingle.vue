<script setup>
import CourseCard from '@/Components/CourseCard.vue';
import GeneralLayout from '@/Layouts/GeneralLayout.vue';
import { Link } from '@inertiajs/vue3';
import { formatCurrency } from '@/helpers';
import moment from 'moment';
import { computed } from 'vue';

let props = defineProps( {
    title: String,
    course: Object,
    my_application: Object,
} );



let method = computed( () => {
    if ( props.course.learning_methods.virtual && props.course.learning_methods.inclass ) {
        return "Virtual and In-Class";
    }
    else if ( props.course.learning_methods.virtual ) {
        return "Virtually Online";
    }
    else if ( props.course.learning_methods.inclass ) {
        return "In-Class Mode";
    }
} )

</script>

<template>
    <GeneralLayout :title="title">
        <nav class="breadcrumb">
            <Link class="breadcrumb-item" :href="route('public.home')">Home</Link>
            <span class="breadcrumb-item">{{ course.program }}</span>
            <span class="breadcrumb-item active" aria-current="page">{{ course.name }}</span>
        </nav>
        <div v-if="!$page.props.auth.user" class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fa fa-exclamation-triangle fa-2x me-2"></i>
            <p class="mb-0">You would need to <strong>
                    <Link class="alert-link" :href="route('login')"> Login</Link>
                </strong> or <strong>
                    <Link class="alert-link" :href="route('register')"> Create an account</Link>
                </strong> before you can apply to enrol
                for any course</p>
        </div>

        <div class="row g-md-5 my-5">
            <div class="col-md-6 order-2 order-md-1">
                <h1 class="display-4 lh-1">{{ course.name }}</h1>
                <p class="lead lh-base">{{ course.summary }}</p>
                <div v-if="my_application" class="alert alert-primary" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle me-2" aria-hidden="true"></i>
                        <p class="mb-0">You've enrolled for this course on
                            <strong> {{ moment(my_application.created_at).format('ll') }} </strong>
                        </p>
                    </div>
                    <Link class="alert-link d-block mt-2 text-decoration-underline" :href="route('myaccount.courses')">View
                    my courses</Link>
                </div>
                <div v-else-if="$page.props.auth.user && (!$page.props.auth.user.nin || !$page.props.auth.user.profile_picture || !$page.props.auth.user.work_experience.title || !$page.props.auth.user.medicals.genotype)"
                    class="alert alert-primary" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-exclamation-triangle me-3 fa-2x" aria-hidden="true"></i>
                        <p class="mb-0"><strong>You cannot enrol for this course yet</strong><br>
                            You need to update your picture, your personal details, your educational background and your
                            medical records
                        </p>
                    </div>
                    <Link class="alert-link d-block mt-2 text-decoration-underline"
                        :href="route('myaccount.personal-info')">
                    Edit Account Information</Link>
                </div>
                <Link v-else as="button" method="POST" :data="{ course_id: course.id }"
                    class="btn btn-primary btn-lg px-5 mt-3 waves-effect" :href="route('myaccount.invoice.create')">
                <strong>Enrol for
                    Course</strong><br><small>Starts
                    {{ moment(course.date_of_commencement).format('MMM Do') }}</small></Link>


            </div>
            <div class="col-md-4 offset-md-2 order-1  order-md-2 mb-3">
                <img class='rounded course-card'
                    :src="course.banner ? `/storage/${course.banner}` : `/assets/img/headers-2.jpg`" :alt="course.name">
            </div>
        </div>
        <div class="border border-secondary rounded card-body my-5">
            <div class="row lead justify-content-md-center">
                <div class=" col-md-2 col-6">
                    <div class="">
                        <strong class="h5">Cost</strong><br>
                        <template v-if="course.discounted_cost">
                            <span>{{ formatCurrency(course.discounted_cost) }}</span>
                            <span class="ms-2"><s class="d-none d-md-inline">{{ formatCurrency(course.cost) }}</s></span>
                        </template>
                        <span v-else>{{ formatCurrency(course.cost) }}</span>
                    </div>
                </div>
                <div class="col-md-1 d-none d-md-flex justify-content-center">
                    <div class="vr h-100 d-none d-md-block"></div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="">
                        <strong class="h5">Duration</strong><br>
                        <span>{{ course.duration }}</span>

                    </div>
                </div>
                <div class="col-md-1 d-none d-md-flex justify-content-center">
                    <div class="vr h-100 d-none d-md-block"></div>
                </div>
                <div class=" col-md-2 col-6">
                    <strong class="h5">Commencement</strong><br>
                    <span>{{ moment(course.date_of_commencement).format('LL') }}</span>
                </div>
                <div class="col-md-1 d-none d-md-flex justify-content-center">
                    <div class="vr h-100 d-none d-md-block"></div>
                </div>
                <div class=" col-md-2 col-6">
                    <strong class="h5">Learning Method</strong><br>
                    <span>{{ method }}</span>
                </div>

            </div>
        </div>
        <div class="lead column-2" v-html="course.description">
        </div>

    </GeneralLayout>
</template>
<style scoped>
.column-2 {
    column-count: 2;
    column-gap: 100px;
}

.course-card {
    width: 100%;
    height: 15rem;
    object-fit: cover;
}

@media (max-width: 500px) {

    .column-2 {
        column-count: 1;
    }
}
</style>



