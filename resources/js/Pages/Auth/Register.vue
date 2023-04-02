<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm( {
    name: '',
    email: '',
    password: '',
    phone: '',
    terms: false,
} );

const submit = () => {
    form.post( route( 'register' ), {
        onFinish: () => form.reset( 'password' ),
    } );
};
</script>

<template>
    <GuestLayout>

        <Head title="Register" />
        <div class="row align-items-center justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card card-body">
                    <div class="mb-3">
                        <Link :href="route('public.home')"><img src="/assets/img/equilog_logo.png" alt="Equilog"
                            style="width:150px">
                        </Link>
                    </div>
                    <h3 class="fw-bold text-dark">Register Account</h3>

                    <div class="p-2 mt-4">
                        <form @submit.prevent="submit">
                            <div class="form-floating mb-3">
                                <input autofocus type="text" class="form-control"
                                    :class="{ 'is-invalid': form.errors.name }" required placeholder="Name"
                                    v-model="form.name">
                                <label>Name</label>
                                <div class="invalid-feedback" v-if="form.errors.name">{{ form.errors.name }}</div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" :class="{ 'is-invalid': form.errors.email }"
                                    required placeholder="Email" v-model="form.email">
                                <label>Email</label>
                                <div class="invalid-feedback" v-if="form.errors.email">{{ form.errors.email }}</div>
                            </div>
                            <div class="form-floating mb-3">
                                <input autocomplete="current-password" type="password" class="form-control"
                                    :class="{ 'is-invalid': form.errors.password }" required placeholder="Email"
                                    v-model="form.password">
                                <label>Password</label>
                                <div class="invalid-feedback" v-if="form.errors.password">{{ form.errors.password }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input autofocus type="text" class="form-control"
                                    :class="{ 'is-invalid': form.errors.phone }" required placeholder="Name"
                                    v-model="form.phone">
                                <label>Phone</label>
                                <div class="invalid-feedback" v-if="form.errors.phone">{{ form.errors.phone }}</div>
                            </div>

                            <div class="mt-3 text-end">
                                <button class="btn btn-primary w-sm waves-effect waves-light"
                                    :class="{ 'disabled': form.processing }" :disabled="form.processing" type="submit"><span
                                        v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                                    Register</button>
                            </div>
                            <div class="mt-4 text-center">
                                <p class="text-muted mb-0">Already have an account ?
                                    <Link :href="route('login')" class="text-primary">
                                    Login
                                    </Link>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>