<script setup>
import { Link } from "@inertiajs/vue3"

defineProps( {
    links: Array,
} )
</script>
<template>
    <nav aria-label="Page navigation" v-if="links.length > 3">
        <ul class="pagination  pagination-sm  ">
            <template v-for="(link, index) in links" :key="index">
                <li class="page-item disabled" v-if="link.url === null">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true" v-html="link.label"></span>
                    </a>
                </li>
                <li class="page-item" :class="{ 'active': link.active }" aria-current="page" v-else>
                    <Link class="page-link" :href="link.url"><span v-html="link.label"></span>
                    </Link>
                </li>
            </template>
        </ul>
    </nav>
</template>

