<script setup>
import Index from "@/Pages/MyAccount/Messages/Index.vue";
import MessageList from "@/Components/MessageList.vue";
defineProps( { title: String, inbox: Object, stats: Object } );

</script>

<template>
    <Index :title="title" :stats="stats">
        <div class="card px-0" style="min-height:50vh">
            <message-list :inbox="inbox" type="inbox"></message-list>
        </div>
    </Index>
</template>