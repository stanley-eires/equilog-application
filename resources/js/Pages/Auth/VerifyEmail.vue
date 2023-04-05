<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps( {
    status: String,
} );

const form = useForm( {} );

const submit = () => {
    form.post( route( 'verification.send' ) );
};

const verificationLinkSent = computed( () => props.status === 'verification-link-sent' );
</script>

<template>
    <GuestLayout>

        <Head title="Email Verification" />
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card card-body">

                    <div class="mt-2">
                        <div class="mb-5">
                            <Link :href="route('public.home')"><img src="/assets/img/equilog_logo.png" alt="Equilog"
                                style="width:150px">
                            </Link>
                        </div>
                        <h3>Email Verification</h3>

                        <div class="mb-4 text-sm">
                            Thanks for signing up! Before getting started, could you verify your email address by clicking
                            on
                            the link
                            we just emailed to you? If you didn't receive the email, we will gladly send you another.
                        </div>

                        <div class="alert alert-success" v-if="verificationLinkSent">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    A new verification link has been sent to the email address you provided during
                                    registration.
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="submit">
                            <div class="mt-5 d-flex align-items-center justify-content-between">
                                <button class="btn btn-primary w-sm waves-effect waves-light"
                                    :disabled="form.processing"><span v-if="form.processing"
                                        class="spinner-border spinner-border-sm me-2"></span>Resend
                                    Verification
                                    Email</button>

                                <Link :href="route('logout')" method="post" as="button"
                                    class="text-decoration-underline btn btn-link text-muted">
                                Log Out</Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
