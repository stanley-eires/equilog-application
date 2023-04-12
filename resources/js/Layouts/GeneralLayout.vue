<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from "vue";
import NavbarProfile from '@/Components/NavbarProfile.vue';
import ToastNotification from '@/Components/ToastNotification.vue';

defineProps( { title: String } );
onMounted( () => {
    let body = document.querySelector( 'body' );
    body.setAttribute( "data-layout", "horizontal" )
    body.setAttribute( "style", "background:white" )
} )
onUnmounted( () => {
    let body = document.querySelector( 'body' );
    body.removeAttribute( "data-layout" )
    body.removeAttribute( "style" )
} )

</script>
<template>
    <ToastNotification v-if="$page.props.flash.message" :title="$page.props.flash.message.title"
        :content="$page.props.flash.message.content" :status="$page.props.flash.message.status"></ToastNotification>


    <Head :title="title" />
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <Link :href="route('public.home')" class="logo logo-dark">
                    <img src="/assets/img/equilog_logo.png" alt="" height="22">
                    </Link>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown d-inline-block d-lg-none ms-2">
                    <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="uil-search"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                        aria-labelledby="page-header-search-dropdown">
                    </div>
                </div>

                <NavbarProfile></NavbarProfile>

            </div>
        </div>
    </header>
    <div id="layout-wrapper">
        <div class="main-content">
            <slot name="banner"></slot>

            <div class="page-content mt-0">
                <div class="container-fluid">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
