<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps( {
    email: String,
    token: String,
} );

const form = useForm( {
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
} );

const submit = () => {
    form.post( route( 'password.store' ), {
        onFinish: () => form.reset( 'password', 'password_confirmation' ),
    } );
};
</script>

<template>
    <GuestLayout>

        <Head title="Reset Password" />
        <div class="row justify-content-center">

            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card card-body">
                    <div class="mt-2">
                        <div class="mb-3">
                            <Link :href="route('public.home')"><img src="/assets/img/equilog_logo.png" alt="Equilog"
                                style="width:150px">
                            </Link>
                        </div>
                        <h3 class="fw-bold text-dark">Reset Password</h3>
                    </div>
                    <form @submit.prevent="submit">
                        <div class="form-floating mb-3">
                            <input autofocus type="email" class="form-control" :class="{ 'is-invalid': form.errors.email }"
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
                            <input autocomplete="current-password" type="password" class="form-control"
                                :class="{ 'is-invalid': form.errors.password_confirmation }" required placeholder="Email"
                                v-model="form.password_confirmation">
                            <label>Confirm Password</label>
                            <div class="invalid-feedback" v-if="form.errors.password_confirmation">
                                {{ form.errors.password_confirmation }}
                            </div>
                        </div>
                        <div class="mt-3 text-end">
                            <button class="btn btn-primary w-sm waves-effect waves-light" type="submit"
                                :disabled="form.processing"><span v-if="form.processing"
                                    class="spinner-border spinner-border-sm me-2"></span>Reset Password</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </GuestLayout>
</template>