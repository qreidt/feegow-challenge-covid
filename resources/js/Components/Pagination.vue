<script setup>
import {Link} from "@inertiajs/vue3";
defineProps({pagination: Object});
</script>

<template>
    <div
        v-if="pagination.next_page_url || pagination.prev_page_url"
        class="py-3 flex items-center justify-between"
    >
        <div class="flex-1 flex justify-between sm:hidden">
            <Link
                v-if="pagination.next_page_url"
                :href="pagination.next_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-primary-700 text-sm font-medium rounded-md text-white bg-primary-900 hover:bg-primary-700">
                Anterior
            </Link>
            <Link
                v-if="pagination.prev_page_url"
                :href="pagination.prev_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-primary-700 text-sm font-medium rounded-md text-white bg-primary-900 hover:bg-primary-700">
                Próximo
            </Link>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-white">
                    Exibindo
                    <span class="font-medium">
                    {{ pagination.from }}
                </span>
                    até
                    <span class="font-medium">
                    {{ pagination.to }}
                </span>
                    de
                    <span class="font-medium">
                    {{ pagination.total }}
                </span>
                    resultados
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm space-x-0.5" aria-label="Pagination">
                    <template v-for="(link, key) in pagination.links" :key="key">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            v-html="link.label"
                            class="inline-flex items-center border px-4 py-2 rounded-sm font-semibold text-xs tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            :class="{
                                'bg-gray-800 border-gray-500 text-gray-300 hover:bg-gray-700 dark:bg-white dark:border-gray-300 dark:text-gray-700 dark:hover:bg-gray-50 ': link.active,
                                'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-500 dark:text-gray-300 dark:hover:bg-gray-700': !link.active
                            }"
                        />
                    </template>
                </nav>
            </div>
        </div>
    </div>
</template>
