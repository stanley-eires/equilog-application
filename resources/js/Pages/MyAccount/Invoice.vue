<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import moment from 'moment';
import { formatCurrency } from '@/helpers';
import { nanoid } from 'nanoid';
import paystack from "vue3-paystack";


let dismiss = ref( true )
const props = defineProps( { title: String, invoice: Object } );
const totalCost = computed( () => {
    let total = 0;
    props.invoice.items.forEach( element => {
        total += Number( element.cost );
    } );
    return total;
} );
const isOwner = computed( () => {
    return usePage().props.auth.user.id == props.invoice.user_id
} )
const fullname = computed( () => { usePage().props.auth.user.name.split( ' ' ) } );

let paystackdata = {
    publicKey: 'pk_test_a509cf0b75f217f6e0adf29ab8cf622d86a70544',
    firstname: usePage().props.auth.user.name.split( ' ' )[ 0 ],
    lastname: usePage().props.auth.user.name.split( ' ' ).slice( 1 ).join( ' ' ),
    reference: nanoid( 15 ),
}

const processPayment = ( response ) => {
    useForm( {
        reference: response.reference,
        invoice: props.invoice.id
    } ).post( route( 'myaccount.invoice.payment-via-paystack' ) )
    console.log( response )
};



const form = useForm( {
    amount: totalCost,
    transaction_gateway: 'Bank Transfer',
    status: 1,
    invoice_id: props.invoice.id,
    file: null
} )


const handleUpload = () => {
    if ( confirm( 'By proceeding, you confirm this evidence of payment is legitimate and accept disciplinary actions on false/misleading evidence upload ' ) ) {
        form.post( route( 'myaccount.invoice.upload-evidence' ), {
            preserveScroll: true, onSuccess: () => {
                form.reset();
                dismiss = true;
            }
        } )
    }
}


</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="row">
            <div class="col-md-8">
                <div class="card p-5 overflow-hidden" style="min-height:80vh;">
                    <div v-if="invoice.status && invoice.payment_status" class='ribbon paid'>FULLY PAID</div>
                    <div v-else-if="!invoice.status && invoice.payment_status" class='ribbon partially'>PARTIALLY PAID
                    </div>
                    <div v-else class='ribbon unpaid'>UNPAID</div>
                    <div class="d-flex justify-content-end">
                        <div>
                            <div class="text-end">
                                <button onclick="print()" class="btn btn-sm  d-print-none"><i
                                        class="fa fa-print me-2"></i>Print</button>
                            </div>
                            <div class="text-end">
                                <img src="/assets/img/equilog_logo.png" alt="Equilog" style="width:150px">
                                <ul class='list-unstyled  '>
                                    <li class='fw-bold text-muted text-uppercase'>Equilog Training & Consultancy</li>
                                    <li class=''>Access Bank: <strong>0051030986</strong></li>
                                    <li class=''>Covenant Microfinance: <strong>0520058031</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 ">
                        <div class="col">
                            <ul class='list-unstyled'>
                                <li class='fw-bold mb-1 text-muted text-uppercase'>Invoice
                                    #{{ invoice.invoice_ref }}
                                </li>
                                <li class=''>Invoice Date: <strong>{{ moment(invoice.created_at).format('L') }}</strong>
                                </li>
                                <li class=''>Due Date: <strong>Never</strong></li>
                            </ul>
                            <ul class='list-unstyled'>
                                <li class='fw-bold mb-1'>Invoiced To</li>
                                <li class="lead">{{ invoice.name }}</li>
                            </ul>
                        </div>
                        <div class="col my-2 text-center text-lg-end">
                            <img v-if="invoice.status"
                                :src="`/assets/img/${invoice.status == 2 ? 'approved' : 'rejected'}.jpeg`" alt=""
                                style="width:150px;object-fit: contain;">
                        </div>
                    </div>
                    <div class="mb-5">
                        <div class="table-responsive">
                            <table class="table table-bordered border-secondary table-centered">
                                <thead>
                                    <tr>
                                        <th :class="{ 'd-none': invoice.status || invoice.payment_status || !isOwner }">
                                        </th>
                                        <th class='w-100'>Description</th>
                                        <th>Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(i, index) in invoice.items" :key='index'>
                                        <td class="text-center text-danger"
                                            :class="{ 'd-none': invoice.status || invoice.payment_status || !isOwner }">
                                            <Link preserve-scroll :href="route('invoice.remove-item')"
                                                :data="{ id: invoice.id, item: i.id }" method="delete" class="text-danger">
                                            <i class="fa fa-minus-circle"></i></Link>

                                        </td>
                                        <td>{{ i.description }}</td>
                                        <td class='text-nowrap'>{{ formatCurrency(i.cost) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td :colspan="invoice.status || invoice.payment_status || !isOwner ? 1 : 2"
                                            class='text-end'>Total
                                        </td>
                                        <td class='text-nowrap fw-bold'>{{ formatCurrency(totalCost) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="d-flex justify-content-end" v-if="!invoice.payment_status && isOwner">
                                <paystack buttonClass="btn btn-lg rounded-0 btn-primary"
                                    :buttonText="`Pay ${formatCurrency(totalCost)} with Card`"
                                    :publicKey="paystackdata.publicKey" :firstname="paystackdata.firstname"
                                    :lastname="paystackdata.lastname" :email="$page.props.auth.user.email"
                                    :amount="totalCost * 100" :reference="paystackdata.reference"
                                    :onSuccess="processPayment">Pay
                                    with card
                                </paystack>
                            </div>
                        </div>
                        <Link v-if="!invoice.payment_status && isOwner" :href="route('public.home')"><i
                            class="fa fa-plus-square-o me-1" aria-hidden="true"></i> Add more
                        programs <i class="fa fa-right ms-2 fa-long-arrow-right  "></i>
                        </Link>
                    </div>

                    <h5>Transactions</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered border-secondary  table-centered mb-5">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">Transaction Date</th>
                                    <th class="text-nowrap">Payment Method</th>
                                    <th>Transaction ID</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="invoice.transactions.length > 0">
                                    <tr v-for="(i, index) in invoice.transactions" :key='index'>
                                        <td>{{ moment(i.created_at).format('LL') }}</td>
                                        <td>{{ i.transaction_gateway }}<br>
                                            <a target="_blank"
                                                v-if="i.transaction_gateway == 'Bank Transfer' && i.meta.proof_of_payment"
                                                :href="`/storage/${i.meta.proof_of_payment}`" class='small'><em>View Proof
                                                    of
                                                    Payment</em></a>
                                        </td>
                                        <td class="small" style="width:100%">
                                            <span class="text-truncate">{{ i.id }}</span>
                                        </td>
                                        <td class="text-center ">
                                            <i v-if="i.status" class="fa fa-check-circle text-success"></i>
                                            <i v-else class="fa fa-times-circle text-danger "></i>
                                        </td>
                                        <td>{{ formatCurrency(i.amount) }}</td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td class='text-center' colspan='4'>No Related Transactions Found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="col-md-4 d-print-none" v-if="invoice.status == 0">
                <div class="card my-3">
                    <div class="card-header bg-transparent">
                        <h4 class="card-title mb-0">Manual Payment</h4>
                    </div>
                    <div class="card-body">
                        <p>You can make payment via bank deposit or mobile transer directly to any of the bank account
                            displayed in the invoice <strong>using the generated invoice code (#{{ invoice.invoice_ref }})
                            </strong> as
                            narration. This makes it easier for the administrator to track the payment</p>
                        <p>You are to then <a href='#' @click.prevent="dismiss = false">click
                                here</a> to upload evidence of successful
                            payment. Proof of payment might consist of the bank teller used in making deposit or the receipt
                            generated by your bank application and not your SMS debit notification. </p>
                        <p>Your invoice status chould change on successful verification or contact support if don't get any
                            response within the next 6 hours.</p>
                    </div>
                </div>
                <template v-if="isOwner">

                    <button v-if="dismiss" @click.prevent="dismiss = false"
                        class="btn align-items-center d-flex btn-outline-dark w-100 rounded-0"><i
                            class="fa fa-upload me-2"></i>
                        Upload
                        Payment Evidence
                    </button>
                    <form v-else class="modal-content" @submit.prevent="handleUpload">
                        <div class="modal-header">
                            <h6 class="modal-title">Payment Evidence Upload</h6>
                            <button type="button" class="btn-close" @click.prevent="dismiss = true"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" :value="form.amount">
                            <input type="hidden" :value="form.transaction_gateway">
                            <input type="hidden" value="1">
                            <input type="hidden" :value="form.invoice_id">
                            <div>
                                <input id="my-input" class="custom-file-input" type="file"
                                    @input="form.file = $event.target.files[0]" accept="image/*" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0">

                            <button type="button" class="btn btn-light btn-sm"
                                @click.prevent="dismiss = true">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm" :disabled="form.processing"><span
                                    v-if="form.processing"
                                    class="spinner-border spinner-border-sm me-2"></span>Upload</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.ribbon {
    position: absolute;
    padding: 1rem 0;
    inset: 0 auto auto 0;
    transform-origin: 100% 0;
    transform: translate(-45%) rotate(-45deg);
    clip-path: inset(0-100%);
    font-weight: bold;
    color: white;
}

.paid {
    background: var(--bs-green);
    box-shadow: 0 0 0 999px var(--bs-green);
}

.unpaid {
    background: var(--bs-red);
    box-shadow: 0 0 0 999px var(--bs-red);
}

.partially {
    background: var(--bs-yellow);
    box-shadow: 0 0 0 999px var(--bs-yellow);
}
</style>
