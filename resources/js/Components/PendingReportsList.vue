<script setup>
import {$dateFormat, $dateTimeFormat} from "../Helpers/date-helper.js";
import Pagination from "@/Components/Pagination.vue";

defineProps({reports: Array});
defineEmits(['rowClicked']);

function mapReportType(report_type) {
    return {
        'CSV': '.csv',
    }[report_type] ?? '-';
}
</script>

<template>
    <div class="bg-white dark:bg-slate-800 py-2 overflow-hidden shadow-xl sm:rounded-lg px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl py-4 text-gray-800 dark:text-white font-semibold">Relatórios Pendentes</h2>
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8 text-gray-800 dark:text-gray-50">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-700">
                    <thead>
                    <tr class="text-gray-900 dark:text-white">
                        <th scope="col" class="py-3.5 text-left text-sm font-semibold">
                            Data/Hora Requisição
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-gray-800">
                    <tr
                        v-for="report in reports" :key="report.id"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                    >
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $dateTimeFormat(report.created_at, 'dd/MM/yy HH:mm') }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
