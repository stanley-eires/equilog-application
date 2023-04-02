<script setup>
import Index from "@/Pages/Admin/Courses/Index.vue";
import EnrollmentList from "@/Components/EnrollmentList.vue";

defineProps( { title: String, course: Object } );

</script>

<template>
    <Index :title="title" :course_id="course.id">
        <div class="card card-body mb-4">
            <enrollment-list :enrollments="course.enrollments" :except="['course']"></enrollment-list>
        </div>
    </Index>
</template>