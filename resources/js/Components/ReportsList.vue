<script setup>
import {$dateFormat, $dateTimeFormat} from "../Helpers/date-helper.js";
import Pagination from "@/Components/Pagination.vue";

defineProps({reports: Object});
defineEmits(['rowClicked']);

function mapReportType(report_type) {
    return {
        'CSV': '.csv',
    }[report_type] ?? '-';
}
</script>

<template>
    <div class="bg-white dark:bg-slate-800 py-2 overflow-hidden shadow-xl sm:rounded-lg px-4 sm:px-6 lg:px-8">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8 text-gray-800 dark:text-gray-50">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-700">
                    <thead>
                    <tr class="text-gray-900 dark:text-white">
                        <th scope="col" colspan="2" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-0">
                            Nome
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                            Requisição
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                            Disponível
                        </th>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-0">
                            Tipo
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-gray-800">
                    <tr
                        @click="$emit('rowClicked', report)"
                        v-for="report in reports.data" :key="report.id"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                    >
                        <td colspan="2" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-0">
                            {{ report.name }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $dateTimeFormat(report.created_at, 'dd/MM/yy HH:mm') }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $dateTimeFormat(report.ready_at, 'dd/MM/yy HH:mm') }}
                        </td>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-0">
                            {{ mapReportType(report.type) }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Pagination :pagination="reports" />
    </div>
</template>
