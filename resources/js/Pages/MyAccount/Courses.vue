<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import EnrollmentList from "@/Components/EnrollmentList.vue";

defineProps( { title: String, courses: Object } );
let id = ref( [] )
</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="card card-body">
            <enrollment-list :enrollments="courses" :except="['candidate']"></enrollment-list>
        </div>
    </AuthenticatedLayout>
</template>