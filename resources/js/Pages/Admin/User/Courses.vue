<script setup>
import Index from '@/Pages/Admin/User/Index.vue';
import EnrollmentList from "@/Components/EnrollmentList.vue";

defineProps( { title: String, user: Object } );
</script>
<template>
    <Index :user="user" :title="title" :breadcrumbs="true">
        <div class="card card-body">
            <enrollment-list :enrollments="user.courses" :except="['candidate']"></enrollment-list>
        </div>
    </Index>
</template>