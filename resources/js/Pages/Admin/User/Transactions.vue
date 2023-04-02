<script setup>
import Index from '@/Pages/Admin/User/Index.vue';
import InvoicesList from '@/Components/InvoicesList.vue';

defineProps( { title: String, user: Object } );

</script>
<template>
    <Index :user="user" :title="title" :breadcrumbs="false">
        <div class="card card-body">
            <form action="" class='col-md-3 mb-3'>
                <div class="input-group border shadow-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent border-0"><i class="fa fa-search"></i></span>
                    </div>
                    <input class="form-control border-0" type="search" placeholder="Search...">
                </div>
            </form>
            <div class="table-responsive" style="min-height:50vh">
                <invoices-list :invoices="user.invoices"></invoices-list>
            </div>
        </div>
    </Index>
</template>