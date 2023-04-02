<script setup>
import Index from "@/Pages/Admin/Courses/Index.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import Editor from '@tinymce/tinymce-vue';
import { ref } from "vue";



const props = defineProps( { title: String, course: Object } );

const form = useForm( props.course );
const featuredImage = ref( props.course.banner ? `/storage/${ props.course.banner }` : form.banner );
let handleAddFile = ( ev ) => {

    form.banner = ev.target.files[ 0 ];

    let reader = new FileReader();
    reader.onload = () => {
        featuredImage.value = reader.result
    };
    reader.readAsDataURL( ev.target.files[ 0 ] )

}
</script>

<template>
    <component :is="$props.course.id ? Index : AuthenticatedLayout" :title="title" :course_id="course ? course.id : null">
        <form enctype="multipart/form-data"
            @submit.prevent="course.id ? form.post(route('course.update', [course.id])) : form.post(route('course.save'))"
            class="row">
            <div class="col-xl-8">
                <div class="card card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-floating mb-3">
                                <input type="text" required class="form-control" placeholder="Course Name"
                                    v-model="form.name" maxlength="30">
                                <label>Name</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" v-model="form.program" required>
                                    <option :value="i"
                                        v-for="(i, index) in ['Digital Entrepreneurship', 'Machine Operation', 'SME Digital Boot-camp']"
                                        :key="index">{{ i }}</option>
                                </select>
                                <label>Program</label>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <editor api-key="xi62uvac03b7vuqqfj6f3lr8x2z6vkd4048al3e3m9hoouny" v-model="form.description"
                                :init="{
                                    height: '400',
                                    menubar: true,
                                    plugins: 'code'
                                }" class="w-100 form-control"></editor>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="">Program Banner</label>
                                    <img v-if="featuredImage" class='rounded mb-3' :src="featuredImage"
                                        style="width:100%;height:150px;object-fit: cover;">
                                    <div class="mb-3">

                                        <input type="file" class="form-control form-control-sm"
                                            @input="handleAddFile($event)">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="">Learning Methods</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="true"
                                            v-model="form.learning_methods.virtual" id="virtual">
                                        <label class="form-check-label" for="virtual">Virtual </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            v-model="form.learning_methods.inclass" value="true" id="in-class">
                                        <label class="form-check-label" for="in-class">In Class</label>
                                    </div>
                                    <label class="mt-3">Course Availability</label>
                                    <div class="form-check form-switch ">
                                        <input class="form-check-input" v-model="form.status" type="checkbox"
                                            id="flexSwitchCheckChecked" value="1">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">
                                            {{ form.status ? 'Active' : 'Inactive' }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 ">
                <div class="card card-body mb-2">
                    <div class="form-floating">
                        <textarea style="height:100px" class="form-control" required placeholder="Summary"
                            v-model="form.summary" maxlength="300"></textarea>
                        <label>Summary</label>
                    </div>
                </div>
                <div class="card card-body mb-2">
                    <div class="form-floating mb-3">
                        <input type="date" required class="form-control" placeholder="Date of Commencement"
                            v-model="form.date_of_commencement">
                        <label>Date of Commencement</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" required placeholder="Duration" v-model="form.duration">
                        <label>Duration</label>
                    </div>
                </div>

                <div class="card card-body mb-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" required placeholder="Cost" v-model="form.cost">
                                <label>Course Price (NGN)</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" placeholder="Cost" v-model="form.discounted_cost">
                                <label>Discounted Cost (NGN)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" :disabled="form.processing" class="btn btn-primary"><span v-if="form.processing"
                        class="spinner-border spinner-border-sm me-2"></span> Save
                    Changes</button>
            </div>
        </form>
    </component>
</template>
