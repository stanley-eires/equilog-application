<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps( {
    status: String,
} );

const form = useForm( {
    email: '',
} );

const submit = () => {
    form.post( route( 'password.email' ) );
};
</script>

<template>
    <GuestLayout>

        <Head title="Forgot Password" />
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
                        <p>Forgot your password? No problem. Just let us know your email address and we will email
                            you a password reset
                            link that will allow you to choose a new one.</p>
                    </div>
                    <div class="p-2 mt-4">
                        <div v-if="$page.props.flash.message.content" class="alert alert-success mb-3" role="alert">
                            {{ $page.props.flash.message.content }}
                        </div>
                        <form @submit.prevent="submit">
                            <div class="form-floating mb-3">
                                <input autofocus type="email" class="form-control"
                                    :class="{ 'is-invalid': form.errors.email }" required placeholder="Email"
                                    v-model="form.email">
                                <label>Email</label>
                                <div class="invalid-feedback" v-if="form.errors.email">{{ form.errors.email }}</div>
                            </div>

                            <div class="mt-3 text-end">
                                <button class="btn btn-primary w-sm waves-effect waves-light" type="submit"
                                    :disabled="form.processing"><span v-if="form.processing"
                                        class="spinner-border spinner-border-sm me-2"></span>Reset</button>
                            </div>
                            <div class="mt-4 text-center">
                                <p class="mb-0">Remember It ?
                                    <Link :href="route('login')" class="text-primary">
                                    Login</Link>
                                </p>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </GuestLayout>
</template>