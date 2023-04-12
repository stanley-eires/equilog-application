<template>
    <template v-if="$page.props.auth.user">
        <div class="dropdown d-inline-block ms-1" v-if="$page.props.auth.user.roles.includes('admin')">
            <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fa-th fa"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <div class="row">
                    <div class="col-6">
                        <Link :href="route('admin.index')" class="dropdown-icon-item">
                        <i class="fa fa-black-tie fa-5x"></i>
                        <span class="h6 text-uppercase">Administrator</span>
                        </Link>
                    </div>
                    <div class="col-6">
                        <Link :href="route('myaccount.overview')" class="dropdown-icon-item">
                        <i class="fa fa-user-circle-o fa-5x"></i>
                        <span class="h6 text-uppercase">My Account</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect align-items-center d-flex"
                id="page-header-user-dropdown" data-bs-toggle="dropdown">
                <ProfilePicture size="40" class="header-profile-user" :value="$page.props.auth.user.profile_picture"
                    :user-id="$page.props.auth.user.id">
                </ProfilePicture>
                <span class="d-none d-xl-inline-block ms-1 fw-medium font-size-15">{{ $page.props.auth.user.name }}</span>
                <i class="uil-angle-down d-none d-xl-inline-block font-size-15"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <Link :href="route('myaccount.overview')" class="dropdown-item"><i
                    class="fa fa-user-circle-o font-size-18 align-middle text-muted me-1"></i> <span class="align-middle">My
                    Account</span></Link>
                <Link :href="route('logout')" method="post" as="button" class="dropdown-item"><i
                    class="fa fa-sign-out font-size-18 align-middle text-muted me-1"></i> <span
                    class="align-middle">Logout</span></Link>
            </div>
        </div>
    </template>
    <div v-else>
        <Link :href="route('login')" class="btn btn-primary">Login</Link>
        <Link v-if="$page.props.auth_config?.allow_manual_registration" :href="route('register')" class="btn btn-link ">
        Join for Free</Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import ProfilePicture from '@/Components/ProfilePicture.vue';
</script>

