<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({vaccines: Array})
</script>

<template>
    <div class="bg-white dark:bg-slate-800 py-2 overflow-hidden shadow-xl sm:rounded-lg px-4 sm:px-6 lg:px-8">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8 text-gray-800 dark:text-gray-50">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-700">
                    <thead>
                    <tr class="text-gray-900 dark:text-white">
                        <th scope="col" colspan="2" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-0">
                            Nome Completo
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                            Nome Simplificado
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-right">
                            Ações
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-gray-800">
                    <tr v-for="vaccine in vaccines" :key="vaccine.id">
                        <td colspan="2" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-0">
                            {{ vaccine.name }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ vaccine.slug }}
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                            <Link :href="route('vaccines.show', vaccine.id)" class="text-primary-400 hover:text-primary-300">
                                Mais Informações<span class="sr-only">, {{ vaccine.name }}</span>
                            </Link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
