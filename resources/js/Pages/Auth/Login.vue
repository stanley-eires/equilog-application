<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { decodeCredential } from 'vue3-google-login'

defineProps( {
    canResetPassword: Boolean,
    status: String
} );

const form = useForm( {
    email: '',
    password: '',
    remember: false,
} );

const submit = () => {
    form.post( route( 'login' ), {
        onFinish: () => form.reset( 'password' ),
    } );
};
const handleLogin = ( response ) => {
    const userData = decodeCredential( response.credential )
    useForm( {
        email: userData.email,
        name: userData.name
    } ).post( route( 'social-login' ) )
}
</script>

<template>
    <GuestLayout>

        <Head title="Log in" />
        <div class="row align-items-center justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card card-body">
                    <div class=" mt-2">
                        <div class="mb-3">
                            <Link :href="route('public.home')"><img src="/assets/img/equilog_logo.png" alt="Equilog"
                                style="width:150px">
                            </Link>
                        </div>
                        <h3 class="fw-bold text-dark">Sign in</h3>
                    </div>
                    <div class="p-2 mt-4">
                        <div v-if="status" class="mb-4 text-sm text-success">
                            {{ status }}
                        </div>
                        <form @submit.prevent="submit">
                            <div class="form-floating mb-3">
                                <input autofocus type="email" class="form-control"
                                    :class="{ 'is-invalid': form.errors.email }" required placeholder="Email"
                                    v-model="form.email">
                                <label>Email</label>
                                <div class="invalid-feedback" v-if="form.errors.email">{{ form.errors.email }}</div>
                            </div>
                            <div class="text-end">
                                <Link v-if="canResetPassword" :href="route('password.request')" class="text-muted">
                                Forgot password?</Link>
                            </div>
                            <div class="form-floating mb-3">
                                <input autocomplete="current-password" type="password" class="form-control"
                                    :class="{ 'is-invalid': form.errors.password }" required placeholder="Email"
                                    v-model="form.password">
                                <label>Password</label>
                                <div class="invalid-feedback" v-if="form.errors.password">{{ form.errors.password }}
                                </div>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="auth-remember-check" name="remember"
                                    v-model="form.remember">
                                <label class="form-check-label" for="auth-remember-check">Remember me</label>
                            </div>

                            <div class="mt-3 text-end">
                                <button class="btn btn-primary w-sm waves-effect waves-light" type="submit"
                                    :disabled="form.processing"><span v-if="form.processing"
                                        class="spinner-border spinner-border-sm me-2"></span>Sign In</button>
                            </div>
                            <div class="my-4 text-center" v-if="$page.props.auth_config?.allow_manual_registration">
                                <p class="mb-0">Don't have an account ?
                                    <Link :href="route('register')" class="fw-medium text-primary">Register</Link>
                                </p>
                            </div>
                            <GoogleLogin v-if="$page.props.auth_config?.allow_google_sso" prompt :callback="handleLogin"
                                :client-id="$page.props.auth_config?.google_client_id"
                                :buttonConfig="{ size: 'xlarge', text: 'continue_with', width: '400px' }" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>