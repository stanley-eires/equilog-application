<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import moment from 'moment';
import { formatCurrency } from '@/helpers';
import Pagination from '@/Components/Pagination.vue';
import ProfilePicture from '@/Components/ProfilePicture.vue';

defineProps( { invoices: Object } );
const is_admin = computed( () => {
    return usePage().url.startsWith( '/admin' )
} )

</script>
<template>
    <div class="table-responsive" style="min-height:30vh">
        <table class="table table-centered">
            <thead class="text-nowrap">
                <tr>
                    <th style="width:2px">
                        <div class="form-check pt-0">
                            <input class="form-check-input" type="checkbox">
                        </div>
                    </th>
                    <th v-if="is_admin" style="min-width: 200px;">Candidate</th>
                    <th>Invoice #</th>
                    <th></th>
                    <th>Date Generated</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Approval Status</th>
                </tr>
            </thead>
            <tbody>
                <template v-if="invoices.data.length > 0">
                    <tr v-for="(i, index) in invoices.data" :key="index">
                        <td>
                            <div class="form-check pt-0">
                                <input :disabled="i.approval_status == 'Approved'" class="form-check-input" type="checkbox">
                            </div>
                        </td>
                        <td v-if="is_admin" class="text-uppercase">
                            <Link class="d-flex" :href="route('user.single', [i.user_id, 'profile'])">
                            <div class="flex-shrink-0">
                                <ProfilePicture :value="i.profile_picture" size="25">
                                </ProfilePicture>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                {{ i.name }}
                            </div>
                            </Link>
                        </td>
                        <td>
                            <a target="_blank" :href="route('invoice', i.invoice_id)">
                                #{{ i.invoice_ref }}</a>
                        </td>
                        <td>
                            <div class="dropdown open">
                                <button :disabled="i.status == 2" class=" btn btn-sm btn-outline-dark dropdown-toggle"
                                    type="button" data-bs-toggle="dropdown"><i class="fa fa-cog me-1"></i>
                                    Actions</button>
                                <div class="dropdown-menu">
                                    <template v-if="is_admin && i.payment_status == 1">
                                        <Link preserve-scroll
                                            onclick="return confirm('Are you sure you want to approve this invoice payment?\n\nPlease ensure to check properly before proceeding further as this action might be irreversible.')"
                                            as='button' :href="route('admin.invoices.approve')"
                                            :data="{ id: i.invoice_id, user_id: i.user_id, items: i.items.map(e => e.id) }"
                                            method="post" class="dropdown-item "><i class="fa fa-check me-1"></i>
                                        Approve
                                        Payment</Link>
                                        <Link preserve-scroll
                                            onclick="return confirm('Do not be in a haste to go ahead especially in a scenario when the payment is legit but the funds has not be credited to your account\n\nAre you sure you want to decline this invoice payment?')"
                                            as='button' :disabled="i.status == 1" class="dropdown-item"
                                            :href="route('admin.invoices.decline')"
                                            :data="{ status: 1, date_approved: moment().format('lll'), id: [i.invoice_id], user_id: i.user_id }"
                                            method="put"><i class="fa fa-times me-1"></i> Decline
                                        Payment</Link>
                                    </template>
                                    <Link preserve-scroll
                                        onclick="return confirm('Are you sure you want to delete this invoice permanently?\n\nThis action is irreversible')"
                                        :href="route('admin.invoices.delete')" :data="{ id: [i.invoice_id] }"
                                        :disabled="i.payment_status == 1" method="delete" as='button'
                                        class="dropdown-item "><i class="fa fa-trash me-1"></i>
                                    Delete
                                    Invoice</Link>
                                </div>
                            </div>
                        </td>
                        <td>{{ moment(i.created_at).format('L') }}</td>
                        <td class="text-nowrap">{{ formatCurrency(i.amount) }}</td>
                        <td>
                            <span v-if="i.payment_status == 1">
                                <i class="fa fa-check-circle text-success"></i> Paid<br>
                                <small>{{ moment(i.date_paid).format('L') }}</small>
                            </span>
                            <span v-else>
                                <i class="fa fa-times-circle text-danger"></i> Unpaid
                            </span>
                        </td>
                        <td>
                            <span v-if="i.status == 2">
                                <i class="fa fa-check-circle text-success"></i> Approved<br>
                                <small>{{ i.date_approved }}</small>
                            </span>
                            <span v-else-if="i.status == 1">
                                <i class="fa fa-times-circle text-danger"></i> Disapproved<br>
                                <small>{{ i.date_approved }}</small>
                            </span>
                            <span v-else>
                                --
                            </span>
                        </td>
                    </tr>
                </template>
                <tr v-else class="lead">
                    <td colspan="8">No record of invoice found</td>
                </tr>
            </tbody>
        </table>
    </div>
    <pagination class="mt-5" :links="invoices.links" />
</template>



<style></style>