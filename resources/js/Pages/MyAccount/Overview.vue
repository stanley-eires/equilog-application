<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import ProfilePicture from '@/Components/ProfilePicture.vue';


defineProps( { title: String } );

const gridmenus = [
    { name: 'Personal Info', icon: 'fa-user', description: 'Manage your personal information including next-of-kin and medicals', btn_text: 'Update Info', href: route( 'myaccount.personal-info' ) },
    { name: 'Security Info', icon: 'fa-lock', description: 'Keep your verification methods and security info up to date.', btn_text: 'Update Info', href: route( 'myaccount.security' ) },
    { name: 'Password', icon: 'fa-key', description: 'Make your password stronger, or change it if someone else knows it', btn_text: 'Change Password', href: route( 'myaccount.security' ) },
    { name: 'Payments', icon: 'fa-credit-card', description: 'View and manage all related Invoices & Payments', btn_text: 'View All', href: route( 'myaccount.invoices' ) },
]

</script>

<template>
    <AuthenticatedLayout :title="title">
        <div class="row">
            <div class="col-lg-9 col-sm-12 mx-auto ">
                <div class="row pt-5">
                    <div class="col-lg-4 mb-3">
                        <div class="card card-body text-center h-100">
                            <div style="margin-top:-5rem">
                                <ProfilePicture class="img-thumbnail rounded-circle"
                                    :value="$page.props.auth.user.profile_picture" :user-id="$page.props.auth.user.id">
                                </ProfilePicture>
                            </div>
                            <div class="h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="lead">Welcome, <br><span class="h3">{{ $page.props.auth.user.name }}</span>
                                    </p>
                                    <div class="media text-left small">
                                        <div class="media-body text-truncate">
                                            <i class="fa fa-envelope"></i>
                                            {{ $page.props.auth.user.email }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-none d-md-block">
                                    <Link class='btn btn-sm' :href="route('logout')" method="post" as="button">Signout
                                    Everywhere</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-md-6" v-for="(menu, index) in gridmenus" :key="index">
                                <div class="card card-body text-center waves-effect">
                                    <h2 class="">{{ menu.name }}</h2>
                                    <i class="fa fa-4x my-3" :class="menu.icon"></i>

                                    <p>{{ menu.description }}</p>
                                    <Link :href="menu.href" class="btn btn-link text-uppercase stretched-link">
                                    {{ menu.btn_text }}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>