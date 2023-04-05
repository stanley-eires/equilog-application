<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm( {
    password: '',
} );

const submit = () => {
    form.post( route( 'password.confirm' ), {
        onFinish: () => form.reset(),
    } );
};
</script>

<template>
    <GuestLayout>

        <Head title="Confirm Password" />
        <Link :href="route('public.home')"><img src="/assets/img/equilog_logo.png" alt="Equilog" style="width:150px">
        </Link>
        <div class="mb-4">
            This is a secure area of the application. Please confirm your password before continuing.
        </div>

        <form @submit.prevent="submit">
            <div class="form-floating mb-3">
                <input autocomplete="current-password" type="password" class="form-control"
                    :class="{ 'is-invalid': form.errors.password }" required placeholder="Email" v-model="form.password">
                <label>Password</label>
                <div class="invalid-feedback" v-if="form.errors.password">{{ form.errors.password }}
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-primary w-sm waves-effect waves-light" type="submit"
                    :disabled="form.processing"><span v-if="form.processing"
                        class="spinner-border spinner-border-sm me-2"></span>Reset</button>
            </div>
        </form>
    </GuestLayout>
</template>
