<script setup>
import { humanizeTime } from '@/helpers';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';


defineProps( { title: String, activities: Object } );
const form = useForm( {
    current_password: '',
    password: '',
    password_confirmation: '',
} );
const updatePassword = () => {
    form.put( route( 'password.update' ), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    } );
};
</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-body">
                    <h4 class="mb-3">Your Devices</h4>
                    <p class="mb-3">You're signed in on these devices or have been in the last 28 days. There might be
                        multiple
                        activity sessions
                        from the same device</p>
                    <div class="table-responsive">
                        <table class="table table-centered">
                            <thead>
                                <tr>
                                    <th style="width:3px">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox">
                                        </div>
                                    </th>
                                    <th>Operating System</th>
                                    <th>Browser</th>
                                    <th>IP Address</th>
                                    <th>Signed In</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(i, index) in activities" :key="index">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox">
                                        </div>
                                    </td>
                                    <td class="text-nowrap"><i class="fa me-1 "
                                            :class="i.value.desktop ? 'fa-desktop' : 'fa-mobile'"></i>
                                        {{ i.value.platform }}
                                    </td>
                                    <td class="text-nowrap"><i class="fa me-1 "
                                            :class="`fa-${i.value.browser?.toLowerCase()}`"></i>
                                        {{ i.value.browser }}
                                    </td>
                                    <td class="text-nowrap">{{ i.value.ip }}</td>
                                    <td class="text-nowrap"> {{ humanizeTime(i.created_at) }}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <form @submit.prevent="updatePassword" class="card card-body">
                    <h4 class="mb-3">Password</h4>
                    <p class="mb-3">Choose a strong password and don't reuse it for other accounts.</p>


                    <div class="form-floating mb-3">
                        <input autocomplete="current-password" type="password" class="form-control"
                            :class="{ 'is-invalid': form.errors.current_password }" placeholder="Current Password"
                            v-model="form.current_password">
                        <label>Password</label>
                        <div class="invalid-feedback" v-if="form.errors.current_password">{{ form.errors.current_password }}
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input autocomplete="current-password" type="password" class="form-control"
                            :class="{ 'is-invalid': form.errors.password }" placeholder="New Password"
                            v-model="form.password">
                        <label>New Password</label>
                        <div class="invalid-feedback" v-if="form.errors.password">{{ form.errors.password }}
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input autocomplete="current-password" type="password" class="form-control"
                            :class="{ 'is-invalid': form.errors.password_confirmation }" placeholder="Confirm Password"
                            v-model="form.password_confirmation">
                        <label>Confirm Password</label>
                        <div class="invalid-feedback" v-if="form.errors.password_confirmation">
                            {{ form.errors.password_confirmation }}
                        </div>
                    </div>
                    <div class=" my-3">
                        <button class="btn btn-primary w-sm waves-effect waves-light" type="submit"
                            :disabled="form.processing"><span v-if="form.processing"
                                class="spinner-border spinner-border-sm me-2"></span>Change Password</button>
                    </div>
                </form>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
