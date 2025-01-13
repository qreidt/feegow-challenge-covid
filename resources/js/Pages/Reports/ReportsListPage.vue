<script setup>
import {ref} from "vue";
import AppLayout from '@/Layouts/AppLayout.vue';
import EmployeesList from "@/Components/EmployeesList.vue";
import PrimaryButton from "@/Components/Jetstream/PrimaryButton.vue";
import DialogModal from "@/Components/Jetstream/DialogModal.vue";
import SecondaryButton from "@/Components/Jetstream/SecondaryButton.vue";
import TextInput from "@/Components/Jetstream/TextInput.vue";
import InputLabel from "@/Components/Jetstream/InputLabel.vue";
import InputError from "@/Components/Jetstream/InputError.vue";
import {router, useForm} from "@inertiajs/vue3";
import Select from "@/Components/Form/Select.vue";
import DangerButton from "@/Components/Jetstream/DangerButton.vue";
import ReportsList from "@/Components/ReportsList.vue";

defineProps({
    reports: Object,
    vaccines: Array,
});

function createNewReport() {
    return router.post(route('reports.store'),{}, {
        preserveScroll: true,
        preserveState: true,
    });
}

function openReport(report) {
    window.open(report.file_url);
}

</script>

<template>
    <AppLayout title="Vacinas">
        <template #header>
            Relatório ({{reports.total}})
        </template>

        <template #actions>
            <PrimaryButton @click="createNewReport()">
                Gerar Relatório
            </PrimaryButton>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <ReportsList v-bind="{reports}" @rowClicked="openReport" />
            </div>
        </div>
    </AppLayout>
</template>
