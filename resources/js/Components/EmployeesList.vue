<script setup>
import {$dateFormat} from "../Helpers/date-helper.js";

defineProps({employees: Object});
defineEmits(['rowClicked', 'confirmDelete']);
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
                            CPF
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                            Data Nas.
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                            Poss. Comorb.
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                            Qtd. Doses
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-sm font-semibold text-right">
                            Ações
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-gray-800">
                    <tr
                        @click="$emit('rowClicked', employee)"
                        v-for="employee in employees.data" :key="employee.id"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                    >
                        <td colspan="2" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-0">
                            {{ employee.name }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ employee.cpf }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $dateFormat(employee.birth_date) }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ employee.has_comorbidity ? 'Sim' : 'Não' }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ employee.doses_count }} doses
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                            <div class="text-primary-400 hover:text-primary-300">
                                Editar<span class="sr-only">, {{ employee.name }}</span>
                            </div>
                            <div
                                @click.stop="$emit('confirmDelete', employee)"
                                class="text-red-400 hover:text-red-300"
                            >
                                Arquivar<span class="sr-only">, {{ employee.name }}</span>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
