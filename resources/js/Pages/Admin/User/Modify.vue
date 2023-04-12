<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import ProfilePicture from '@/Components/ProfilePicture.vue';

const props = defineProps( { title: String, user: [ Object ], breadcrumbs: Boolean } );
const form = useForm( props.user );

</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="card card-body">
            <div class="row">
                <div class="col-md-3">
                    <div v-if="user.id">
                        <label>Applicant's Recent Photo *</label>
                        <div class="text-center">
                            <ProfilePicture :value="user.profile_picture" :user-id="user.id">
                            </ProfilePicture>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <form
                        @submit.prevent="user.id ? form.put(route('user.update', [user.id])) : form.post(route('users.save'))">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.name }"
                                        required placeholder="Name" v-model="form.name">
                                    <label>Fullname</label>
                                    <div class="invalid-feedback" v-if="form.errors.name">{{ form.errors.name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" :class="{ 'is-invalid': form.errors.email }"
                                        required placeholder="Email" v-model="form.email">
                                    <label>Email</label>
                                    <div class="invalid-feedback" v-if="form.errors.email">{{ form.errors.email }}</div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control" required placeholder="Phone"
                                        v-model="form.phone">
                                    <label>Phone</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" required v-model="form.gender">
                                        <option :value="i" v-for="(i, index) in ['Male', 'Female']" :key="index">{{ i }}
                                        </option>
                                    </select>
                                    <label>Gender</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-select" required v-model="form.marital_status">
                                        <option :value="i"
                                            v-for="(i, index) in ['Single', 'Married', 'Widowed', 'Divorced']" :key="index">
                                            {{ i }}
                                        </option>
                                    </select>
                                    <label>Marital Status</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="date" required class="form-control" placeholder="Date Of Birth"
                                        v-model="form.date_of_birth">
                                    <label>Date Of Birth</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control" placeholder="Address" v-model="form.address">
                                    <label>Address</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <textarea style="height:100px" class="form-control" placeholder="Summary"
                                        v-model="form.summary"></textarea>
                                    <label>Profile Summary</label>
                                </div>
                            </div>
                        </div>

                        <div v-if="user.id">
                            <h6 class='bg-light p-2 my-3 text-uppercase'>Roles</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="admin" value="admin"
                                    v-model="form.roles">
                                <label class="form-check-label" for="admin">
                                    Site Administrator
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="coordinator" value="coordinatore"
                                    v-model="form.roles">
                                <label class="form-check-label" for="coordinator">
                                    Course Coordinator
                                </label>
                            </div>
                            <div class="form-check">
                                <input disabled class="form-check-input" type="checkbox" id="subscriber" value="subscriber"
                                    v-model="form.roles">
                                <label class="form-check-label" for="subscriber">
                                    Subscriber
                                </label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <Link class="btn " :href="route('admin.users')"><i class="fa fa-arrow-left me-2"></i>Back to all
                            users</Link>
                            <button type="submit" :disabled="form.processing" class="btn btn-primary"><span
                                    v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span> Save
                                Changes</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

