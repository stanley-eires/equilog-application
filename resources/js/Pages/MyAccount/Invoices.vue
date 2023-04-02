<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InvoicesList from '@/Components/InvoicesList.vue';
defineProps( { title: String, invoices: Object } );
</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <form action="" class='col-md-3 mb-3'>
                        <div class="input-group border shadow-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-0"><i class="fa fa-search"></i></span>
                            </div>
                            <input class="form-control border-0" type="search" placeholder="Search...">
                        </div>
                    </form>
                    <invoices-list :invoices="invoices"></invoices-list>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>