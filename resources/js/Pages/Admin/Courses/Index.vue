<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps( { title: String, course_id: Number } );
const menus = [
    { name: 'Overview', component: 'Admin/Courses/Overview', href: route( 'course.single', [ props.course_id, 'overview' ] ) },
    { name: 'Modify', component: 'Admin/Courses/Modify', href: route( 'course.single', [ props.course_id, 'modify' ] ) },
    { name: 'Enrollments', component: 'Admin/Courses/Enrollments', href: route( 'course.single', [ props.course_id, 'enrollments' ] ) },
];

</script>

<template>
    <AuthenticatedLayout :title="title">
        <ul class="nav nav-pills nav-tabs-custom text-uppercase mb-2">
            <li class="nav-item" v-for="(menu, index) in menus" :key="index">
                <Link class="nav-link bg-transparent" :class="{ 'active': $page.component === menu.component }"
                    :href="menu.href">{{ menu.name }}</Link>
            </li>
        </ul>
        <div>
            <slot></slot>
        </div>
    </AuthenticatedLayout>
</template>