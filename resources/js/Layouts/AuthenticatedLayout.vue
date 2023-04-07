<script setup>
import { computed } from 'vue';
import { Link, Head, usePage } from '@inertiajs/vue3';
import NavbarProfile from '@/Components/NavbarProfile.vue';
import ToastNotification from '@/Components/ToastNotification.vue';


defineProps( { title: String, breadcrumbs: { type: Boolean, default: true } } );
const menu_type = computed( () => {
    return usePage().url.startsWith( '/admin' ) ? menus.admin : menus.myaccount;
} )


const menus = {
    myaccount: [
        { name: 'My Account' },
        { name: 'Home', icon: 'fa-home', href: "myaccount.overview", active: [ 'MyAccount/Overview' ].includes( usePage().component ) },
        { name: 'Personal Info', icon: 'fa-id-card', href: "myaccount.personal-info", active: [ 'MyAccount/PersonalInfo' ].includes( usePage().component ) },
        { name: 'Security', icon: 'fa-lock', href: "myaccount.security", active: [ 'MyAccount/Security' ].includes( usePage().component ) },
        { name: 'Courses', icon: 'fa-book', href: "myaccount.courses", active: [ 'MyAccount/Courses' ].includes( usePage().component ) },
        { name: 'Payment & Invoices', icon: 'fa-credit-card', href: "myaccount.invoices", active: [ 'MyAccount/Invoices', 'MyAccount/Invoice' ].includes( usePage().component ) },
    ],
    admin: [
        { name: 'Administrator' },
        { name: 'Dashboard', icon: 'fa-home', href: "admin.index", active: [ 'Admin/Dashboard' ].includes( usePage().component ) },
        { name: 'Users', icon: 'fa-user', href: "admin.users", active: [ 'Admin/Users', 'Admin/User/Profile', 'Admin/User/Courses', 'Admin/User/Transactions', 'Admin/User/Modify' ].includes( usePage().component ) },
        // { name: 'Cohorts', icon: 'fa-users', href: "myaccount.personal-info", component: '' },
        { name: 'Courses', icon: 'fa-book', href: "admin.courses", active: [ 'Admin/Courses/Courses', 'Admin/Courses/Overview', 'Admin/Courses/Enrollments', 'Admin/Courses/Modify' ].includes( usePage().component ) },
        { name: 'Payment', icon: 'fa-credit-card', href: "admin.invoices", active: [ 'MyAccount/Invoices', 'MyAccount/Invoice' ].includes( usePage().component ) },
        { name: 'Site Settings', icon: 'fa fa-wrench', href: "admin.site-settings", active: [ 'Admin/Settings' ].includes( usePage().component ) },
    ]
}
const toggleSidebar = () => {
    $( 'body' ).toggleClass( 'sidebar-enable' );
    if ( $( window ).width() >= 992 ) {
        $( 'body' ).toggleClass( 'vertical-collpsed' );
    } else {
        $( 'body' ).removeClass( 'vertical-collpsed' );
    }
}

</script>
<template>
    <Head :title="title" />
    <div id="layout-wrapper">
        <ToastNotification v-if="$page.props.flash.message" :title="$page.props.flash.message.title"
            :content="$page.props.flash.message.content" :status="$page.props.flash.message.status"></ToastNotification>
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex align-items-center">
                    <button @click="toggleSidebar()" type="button"
                        class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <a href="/" class="d-md-none">
                        <img src="/assets/img/equilog_logo.png" alt="" style="width:100px">
                    </a>
                    <form class="app-search d-none d-lg-block">
                        <div class="position-relative">
                            <input type="text" class="form-control">
                            <span class="fa fa-search"></span>
                        </div>
                    </form>
                </div>

                <div class="d-flex">
                    <NavbarProfile></NavbarProfile>
                </div>
            </div>
        </header>
        <div class="vertical-menu">
            <div class="navbar-brand-box px-2 d-none d-md-flex justify-content-between align-items-center">
                <a href="/" class="ms-4">
                    <img src="/assets/img/equilog_logo.png" alt="" style="width:100px">
                </a>
                <button @click="toggleSidebar()" type="button" class="btn btn-sm font-size-16 header-item waves-effect ">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
            </div>

            <div data-simplebar class="sidebar-menu-scroll">
                <div id="sidebar-menu">
                    <ul class=" list-unstyled" id="side-menu">
                        <template v-for="(menu, index) in menu_type" :key="index">
                            <li v-if="menu.href" :class="{ 'mm-active': menu.active }">
                                <Link :href="route(menu.href)" :class="{ 'active': menu.active }" class="waves-effect">
                                <i class="fa" :class="menu.icon"></i><span>{{ menu.name }}</span>
                                </Link>
                            </li>
                            <li v-else class="menu-title">{{ menu.name }}</li>

                        </template>
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="page-content">
                <div class="alert border-warning alert-warning" role="alert"
                    v-if="!$page.props.auth.user.email_verified_at">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fa fa-exclamation-circle fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <strong class="mt-0">Pending Email Verification</strong>
                            <p class="mb-0">You are yet to verify your email address. Some functionalities might not be
                                available to
                                you until you verify. Please check the email sent to you or
                                <Link class="alert-link text-decoration-underline" :href="route('verification.notice')">
                                click here</Link>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row d-print-none" v-if="breadcrumbs">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">{{ title }}</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item">
                                            <Link :href="route('myaccount.overview')">My Account</Link>
                                        </li>
                                        <li class="breadcrumb-item active">{{ title }}</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>