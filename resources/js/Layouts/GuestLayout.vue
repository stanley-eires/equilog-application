<script setup>
import ToastNotification from '@/Components/ToastNotification.vue';
</script>

<template>
    <ToastNotification v-if="$page.props.flash.message" :title="$page.props.flash.message.title"
        :content="$page.props.flash.message.content" :status="$page.props.flash.message.status"></ToastNotification>
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <slot />
        </div>
    </div>
</template>
