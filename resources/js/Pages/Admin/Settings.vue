<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PaymentWidget from '@/Components/Settings/PaymentWidget.vue';
import AuthWidget from '@/Components/Settings/AuthWidget.vue';
import { computed } from 'vue';
import { humanizeTime } from '@/helpers';
import { Link } from '@inertiajs/vue3';

const props = defineProps( { title: String, settings: Object } );

const payment_props = computed( () => props.settings.find( item => item.key == 'payment' ) );
const auth_props = computed( () => props.settings.find( item => item.key == 'auth' ) );
const maintenance_functions = [
    { type: 'clear_cache', name: 'Clear Data Caches', destructive: false, },
    { type: 'clear_activities_log', name: 'Clear Activities Log', destructive: false },
    { type: 'create_symlink', name: 'Create Symlink', destructive: false },
    { type: 'reset_platform', name: 'Reset Platform', destructive: true } ]

</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-body mb-3">
                    <div class="small text-end text-muted fst-italic">Last Update:
                        {{ humanizeTime(payment_props?.updated_at) }}
                    </div>
                    <payment-widget :settings="payment_props?.value"></payment-widget>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-body mb-3">
                    <div class="small text-end text-muted fst-italic">Last Update:
                        {{ humanizeTime(auth_props?.updated_at) }}
                    </div>
                    <auth-widget :settings="auth_props?.value"></auth-widget>
                </div>
                <div class="card card-body mb-3">
                    <h5 class="card-title mb-3">Maintenance Functions</h5>
                    <ul class="list-unstyled lh-lg row row-cols-md-2">
                        <li v-for="func in maintenance_functions" :key="func">
                            <Link v-if="func.destructive"
                                onclick="return prompt('This is a destructive action and should only be done by someone who knows the consequences.\n\nEnter: I UNDERSTAND in the input field below to continue') == 'I UNDERSTAND'"
                                class="btn btn-link text-danger" as="button" method="post" :data="{ type: func.type }"
                                :href="route('admin.maintenance-functions')"><i
                                class="fa fa-exclamation-triangle  me-1"></i>
                            {{ func.name }}</Link>
                            <Link v-else class="btn btn-link" as="button" method="post" :data="{ type: func.type }"
                                :href="route('admin.maintenance-functions')"><i class="fa fa-chevron-right  me-1"></i>
                            {{ func.name }}</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
