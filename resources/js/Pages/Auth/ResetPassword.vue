<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
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

    <!-- <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                    autocomplete="username" />

                                                                <InputError class="mt-2" :message="form.errors.email" />
                                                            </div>

                                                            <div class="mt-4">
                                                                <InputLabel for="password" value="Password" />

                                                                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                                                                    autocomplete="new-password" />

                                                                <InputError class="mt-2" :message="form.errors.password" />
                                                            </div>

                                                            <div class="mt-4">
                                                                <InputLabel for="password_confirmation" value="Confirm Password" />

                                                                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                                                                    v-model="form.password_confirmation" required autocomplete="new-password" />

                                                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                                                            </div>

                                                            <div class="flex items-center justify-end mt-4">
                                                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                                    Reset Password
                                                                </PrimaryButton>
                                                            </div>
                                                        </form> -->
    </GuestLayout>
</template>
