<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import moment from 'moment';
import ProfilePicture from '@/Components/ProfilePicture.vue';


const props = defineProps( { title: String, user: Object, breadcrumbs: Boolean } );

const menus = [
    { name: 'Profile', component: 'Admin/User/Profile', href: route( 'user.single', [ props.user.id, 'profile' ] ) },
    { name: 'Courses', component: 'Admin/User/Courses', href: route( 'user.single', [ props.user.id, 'courses' ] ) },
    { name: 'Transactions', component: 'Admin/User/Transactions', href: route( 'user.single', [ props.user.id, 'transactions' ] ) },
];

</script>

<template>
    <AuthenticatedLayout :title="title" :breadcrumbs="breadcrumbs">
        <section class="mb-5 card card-body">
            <div class="row gx-0 align-items-center">
                <div class="col-md-2 text-center text-sm-start">
                    <ProfilePicture :value="user.profile_picture" size="130">
                    </ProfilePicture>
                </div>
                <div class="col-md-7">
                    <h2 class='fw-bold text-uppercase text-center text-sm-start'>{{ user.name }}</h2>
                    <ul class='list-unstyled row row-cols-md-3 row-cols-2 gy-2'>
                        <li class="text-truncate col-12"><i class='fa fa-envelope me-2'></i> <a class="text-reset"
                                :href="`mailto:${user.email}`">{{ user.email }}</a></li>
                        <li class="col-7"><i class='fa fa-phone me-2'></i><a class="text-reset"
                                :href="`tel:${user.phone}`">{{ user.phone }}</a></li>
                        <li class="col-4"><i class="fa fa-transgender me-2"></i> {{ user.gender }}</li>
                        <li><i class="fa fa-calendar me-2"></i> {{ moment(user.date_of_birth).format('ll') }}</li>
                        <li><i class="fa fa-life-ring me-2"></i> {{ user.marital_status }}</li>
                        <li class="col-12"><i class="fa fa-map me-2"></i> {{ user.address }}</li>
                    </ul>
                </div>
                <div class="col-md-3 text-center">
                    <a :href="`mailto:${user.email}`" class="btn btn-primary w-75 "><i class="fa fa-envelope me-2"></i> Send
                        Message</a>
                </div>
            </div>
        </section>
        <ul class="nav nav-pills nav-tabs-custom text-uppercase mb-2">
            <li class="nav-item" v-for="(menu, index) in menus" :key="index">
                <Link class="nav-link bg-transparent" :class="{ 'active': $page.component === menu.component }"
                    :href="menu.href">{{ menu.name }}</Link>
            </li>
        </ul>
        <div>
            <slot></slot>
        </div>

    </AuthenticatedLayout>
</template>