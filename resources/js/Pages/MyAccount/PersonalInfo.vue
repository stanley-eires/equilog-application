<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProfilePicture from '@/Components/ProfilePicture.vue';

const props = defineProps( { title: String, user: Object } );
const form = useForm( props.user );
const currentTab = ref( 0 );
</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="row">
            <div class="col-lg-2">
                <nav class="nav nav-pills flex-lg-column flex-row mb-3">
                    <button @click.prevent="currentTab = 0" class="nav-link text-start"
                        :class="{ active: currentTab === 0 }">Biography
                    </button>
                    <button @click.prevent="currentTab = 1" class="nav-link text-start"
                        :class="{ active: currentTab === 1 }">Education
                        &
                        Work History</button>
                    <button @click.prevent="currentTab = 2" class="nav-link text-start"
                        :class="{ active: currentTab === 2 }">Medical
                        Fitness</button>
                </nav>
            </div>
            <div class="col-lg-10">
                <div v-if="currentTab == 0">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-body h-100">
                                <h4>Biography</h4>
                                <label>Applicant's Recent Photo *</label>
                                <div class="text-center">
                                    <ProfilePicture :value="user.profile_picture" :user-id="user.id">
                                    </ProfilePicture>
                                </div>
                            </div>
                        </div>
                        <form @submit.prevent="form.put(route('user.update', [user.id], { preserveScroll: true, }))"
                            class="col-md-9">
                            <div class="card card-body">
                                <div class="row">
                                    <h6 class='bg-light p-2 my-3 text-uppercase  col-12'>Personal Details</h6>
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control"
                                                :class="{ 'is-invalid': form.errors.name }" required placeholder="Name"
                                                v-model="form.name">
                                            <label>Fullname *</label>
                                            <div class="invalid-feedback" v-if="form.errors.name">{{ form.errors.name }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" required v-model="form.gender">
                                                <option :value="i" v-for="(i, index) in ['Male', 'Female']" :key="index">
                                                    {{ i }}
                                                </option>
                                            </select>
                                            <label>Gender *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" required v-model="form.marital_status">
                                                <option :value="i"
                                                    v-for="(i, index) in ['Single', 'Married', 'Widowed', 'Divorced']"
                                                    :key="index">
                                                    {{ i }}
                                                </option>
                                            </select>
                                            <label>Marital Status *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating mb-3">
                                            <input type="date" required class="form-control" placeholder="Date Of Birth"
                                                v-model="form.date_of_birth">
                                            <label>Date Of Birth *</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card card-body">
                                <h6 class='bg-light p-2 my-3 text-uppercase  col-12'>Profile Summary</h6>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea maxlength="300" style="height:100px" class="form-control"
                                            placeholder="Summary" v-model="form.summary"></textarea>
                                        <label>Profile Summary</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-body">
                                <h6 class='bg-light p-2 my-3 text-uppercase '>Contact Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="" class="form-control" :class="{ 'is-invalid': form.errors.email }"
                                                required placeholder="NIN" v-model="form.nin">
                                            <label>National Identification Number (NIN) *</label>
                                            <div class="invalid-feedback" v-if="form.errors.nin">{{ form.errors.nin }}</div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control"
                                                :class="{ 'is-invalid': form.errors.email }" disabled required
                                                placeholder="Email" v-model="form.email">
                                            <label>Email *</label>
                                            <div class="invalid-feedback" v-if="form.errors.email">{{ form.errors.email }}
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="tel" class="form-control" required placeholder="Phone"
                                                v-model="form.phone">
                                            <label>Phone *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" placeholder="Address" required
                                                v-model="form.address">
                                            <label>Address *</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-body">
                                <h6 class='bg-light p-2 my-3 text-uppercase '>Next Of Kin</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" required placeholder="Name"
                                                v-model="form.next_of_kin.name">
                                            <label>Fullname *</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" required v-model="form.next_of_kin.relationship">
                                                <option :value="i"
                                                    v-for="(i, index) in ['Sibling', 'Parent', 'Child', 'Friend', 'Others']"
                                                    :key="index">
                                                    {{ i }}
                                                </option>
                                            </select>
                                            <label>Relationship *</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" required placeholder="Email"
                                                v-model="form.next_of_kin.email">
                                            <label>Email *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" required placeholder="Phone"
                                                v-model="form.next_of_kin.phone">
                                            <label>Phone *</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" my-3">
                                <button type="submit" :disabled="form.processing" class="btn btn-primary"><span
                                        v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span> Save
                                    Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                <form v-else-if="currentTab == 1"
                    @submit.prevent="form.put(route('user.update', [user.id], { preserveScroll: true }))">
                    <div class="card card-body mb-3">
                        <h4>Education & Work History</h4>
                        <h6 class='bg-light p-2 my-3 text-uppercase  col-12'>Educational Background</h6>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" required v-model="form.education.degree">
                                        <option :value="i"
                                            v-for="(i, index) in ['School Certificate', 'National Diploma', 'Bachelor Degree', 'Master Degree', 'Doctoral Degree']"
                                            :key="index">
                                            {{ i }}
                                        </option>
                                    </select>
                                    <label>Highest Level of Education * </label>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" required v-model="form.education.institution">
                                    <label>School / University / Institution *</label>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" required v-model="form.education.location">
                                    <label>Location *</label>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Start Date</label>
                                <input type='month' class='form-control'>
                            </div>
                            <div class="form-group col-md-4 d-flex justify-content-between align-items-end">
                                <div class='w-100 mr-2'>
                                    <label>End Date</label>
                                    <input type='month' class='form-control '>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-body">
                        <h6 class='bg-light p-2 my-3 text-uppercase col-12'>Most Recent Work Experience</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" required placeholder="Name"
                                        v-model="form.work_experience.title">
                                    <label>Job Title</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" required placeholder="Name"
                                        v-model="form.work_experience.employer">
                                    <label>Employer *</label>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" required placeholder="Name"
                                        v-model="form.work_experience.location">
                                    <label>Location *</label>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Start Date</label>
                                <input type='month' class='form-control'>
                            </div>
                            <div class="form-group col-md-4 d-flex justify-content-between align-items-end">
                                <div class='w-100 mr-2'>
                                    <label>End Date</label>
                                    <input type='month' class='form-control '>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" my-3">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary"><span
                                v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span> Save
                            Changes</button>
                    </div>
                </form>
                <form v-else-if="currentTab == 2" class="card card-body"
                    @submit.prevent="form.put(route('user.update', [user.id], { preserveScroll: true }))">
                    <h4>Medical Fitness</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" required v-model="form.medicals.genotype">
                                    <option :value="i" v-for="(i, index) in ['AA', 'AO', 'BB', 'BO', 'AB', 'OO']"
                                        :key="index">
                                        {{ i }}
                                    </option>
                                </select>
                                <label>Genotype *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" required v-model="form.medicals.bloodgroup">
                                    <option :value="i" v-for="(i, index) in ['A', 'B', 'AB', 'O']" :key="index">
                                        {{ i }}
                                    </option>
                                </select>
                                <label>Blood Group * </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" required v-model="form.medicals.rhd">
                                    <option :value="i" v-for="(i, index) in ['Positive', 'Negative']" :key="index">
                                        {{ i }}
                                    </option>
                                </select>
                                <label>RhD Factor *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="number" step=".1" class="form-control" required
                                    v-model.number="form.medicals.height">
                                <label>Height (FT) *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="number" step=".1" class="form-control" required
                                    v-model.number="form.medicals.weight">
                                <label>Weight (KG) *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" required v-model="form.medicals.vision">
                                    <option :value="i" v-for="(i, index) in ['Clear', 'Blurry', 'Blind']" :key="index">
                                        {{ i }}
                                    </option>
                                </select>
                                <label>Eye Sight *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" required v-model="form.medicals.hearing">
                                    <option :value="i" v-for="(i, index) in ['Clear', 'Partial', 'Deaf']" :key="index">
                                        {{ i }}
                                    </option>
                                </select>
                                <label>Hearing * </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" required v-model="form.medicals.hiv">
                                    <option :value="i" v-for="(i, index) in ['Positive', 'Negative']" :key="index">
                                        {{ i }}
                                    </option>
                                </select>
                                <label>HIV I&II *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" required v-model="form.medicals.covid">
                                    <option :value="i" v-for="(i, index) in ['Positive', 'Negative']" :key="index">
                                        {{ i }}
                                    </option>
                                </select>
                                <label>COVID 19 * </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" required v-model="form.medicals.meningitis">
                                    <option :value="i" v-for="(i, index) in ['Positive', 'Negative']" :key="index">
                                        {{ i }}
                                    </option>
                                </select>
                                <label>Cerebrospinal Meningitis * </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <select class="form-select" required v-model="form.medicals.tuberculosis">
                                    <option :value="i" v-for="(i, index) in ['Positive', 'Negative']" :key="index">
                                        {{ i }}
                                    </option>
                                </select>
                                <label>Serum Tuberculosis *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" v-model="form.medicals.allergies"></textarea>
                                <label>Allergies </label>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="form-floating mb-3">
                                <input type="file" class="form-control">
                                <label class="pb-5 d-inline-block">Certicate of Medical Report</label>
                            </div>
                        </div>

                    </div>
                    <div class=" my-3">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary"><span
                                v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span> Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
