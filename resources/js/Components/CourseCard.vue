<script setup>
import { Link } from "@inertiajs/vue3";
import { formatCurrency } from '@/helpers';
import moment from 'moment';
import { truncate } from 'lodash'
defineProps( {
    course: Object
} );

</script>
<template>
    <Link class="card course-card text-reset shadow-lg" :href="route('public.course.single', [course.slug])">
    <img class="card-img-top img-fluid course-card-img"
        :src="course.banner ? `/storage/${course.banner}` : `/assets/img/headers-2.jpg`" :alt="course.name">
    <div class="card-body">
        <h6 class=" mt-0 text-uppercase">{{ course.name }}</h6>
        <p class="card-text">{{ truncate(course.summary, { length: 100 }) }}</p>
        <div class="d-flex justify-content-between align-items-center">
            <span class="badge text-muted">{{ formatCurrency(course.discounted_cost ?? course.cost) }}</span>
            <span class="vr"></span>
            <span class="badge text-muted">{{ course.duration }}</span>
            <span class="vr"></span>
            <span class="badge text-muted">{{ moment(course.date_of_commencement).format('LL') }}</span>

        </div>
    </div>
    </Link>
</template>
<style scoped>
.course-card-img {
    width: 100%;
    height: 10rem;
    object-fit: cover;
}

.course-card {
    transition: 0.5s;
}

.course-card:hover {
    transform: scale(1.01);
    box-shadow: none !important;
}
</style>



