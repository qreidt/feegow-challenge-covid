<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import AppLayout from '@/Layouts/AppLayout.vue';
import VaccinesList from "@/Components/VaccinesList.vue";
import PrimaryButton from "@/Components/Jetstream/PrimaryButton.vue";
import DialogModal from "@/Components/Jetstream/DialogModal.vue";
import SecondaryButton from "@/Components/Jetstream/SecondaryButton.vue";
import DangerButton from "@/Components/Jetstream/DangerButton.vue";
import TextInput from "@/Components/Jetstream/TextInput.vue";
import InputLabel from "@/Components/Jetstream/InputLabel.vue";
import InputError from "@/Components/Jetstream/InputError.vue";
import VaccineLotsList from "@/Components/VaccineLotsList.vue";
import {$dateFormat} from "@/Helpers/date-helper.js";

const { vaccine, vaccine_lots } = defineProps({
    vaccine: Object,
    vaccine_lots: Array,
});

const default_vaccine_lot_data = {
    lot_id: '',
    expiration_date: null,
};

// modals
const show_vaccine_lot_modal = ref(false);
const confirm_vaccine_destroy = ref(false);
const confirm_vaccine_lot_destroy = ref(false);

const vaccine_form = useForm(vaccine);
let vaccine_lot_form = useForm(default_vaccine_lot_data);

function submitVaccineForm() {
    vaccine_form.slug = vaccine_form.slug.toUpperCase();
    vaccine_form.patch(route('vaccines.update'), {
        preserveState: true,
        preserveScroll: true,
    });
}

function confirmVaccineDestroy() {
    confirm_vaccine_destroy.value = true;
}

function vaccineDestroy() {
    vaccine_form.delete(route('vaccines.destroy', vaccine.id));
}

function openVaccineLotFormModal(vaccine_lot) {

    vaccine_lot_form = useForm(Object.assign({}, {
        ...default_vaccine_lot_data, ...vaccine_lot,
        expiration_date: $dateFormat(vaccine_lot.expiration_date, 'yyyy-MM-dd')
    }));
    show_vaccine_lot_modal.value = true;
}

function submitVaccineLotForm() {
    vaccine_lot_form.patch(route('vaccines.lots.update', [vaccine.id, vaccine_lot_form.id]), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: function () {
            show_vaccine_lot_modal.value = false;
            confirm_vaccine_lot_destroy.value = false;
        }
    });
}

function confirmVaccineLotDestroy() {
    confirm_vaccine_lot_destroy.value = true;
}

function vaccineLotDestroy() {
    vaccine_lot_form.delete(route('vaccines.lots.destroy', [vaccine.id, vaccine_lot_form.id]), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            show_vaccine_lot_modal.value = false;
            confirm_vaccine_lot_destroy.value = false;
        }
    });
}
</script>

<template>
    <AppLayout :title="`${vaccine.name} - Vacinas`">
        <template #header>
            {{ vaccine.name }}
        </template>

        <template #actions>
            <DangerButton @click="confirmVaccineDestroy()">
                Remover Vacina
            </DangerButton>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <div class="bg-white dark:bg-slate-800 py-2 overflow-hidden shadow-xl sm:rounded-lg px-4 sm:px-6 sm:py-4 lg:px-8">
                    <div class="overflow-x-auto text-gray-800 dark:text-gray-50 space-y-6">
                        <div class="">
                            <h2 class="text-xl">Dados da Vacina</h2>
                        </div>

                        <form @submit.prevent="submitVaccineForm" class="space-y-6">
                            <div class="grid sm:grid-cols-2 sm:gap-x-4">
                                <div class="">
                                    <InputLabel>Nome Completo</InputLabel>
                                    <div class="flex flex-col mt-2">
                                        <TextInput v-model="vaccine_form.name" class="w-full" />
                                        <InputError class="mt-2" :message="vaccine_form.errors.name" />
                                    </div>
                                </div>

                                <div class="">
                                    <InputLabel>Nome Simplificado</InputLabel>
                                    <div class="flex flex-col mt-2">
                                        <TextInput v-model="vaccine_form.slug" class="w-full uppercase" />
                                        <InputError class="mt-2" :message="vaccine_form.errors.slug" />
                                    </div>
                                </div>
                            </div>

                            <div v-if="vaccine_form.isDirty" class="flex flex-row space-x-4 justify-end">
                                <SecondaryButton @click="vaccine_form.reset()">Descartar</SecondaryButton>
                                <PrimaryButton type="submit">Salvar Alterações</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
                <VaccineLotsList
                    v-bind="{vaccine_lots}"
                    @rowClicked="openVaccineLotFormModal"
                />
            </div>
        </div>

        <DialogModal maxWidth="md" :show="confirm_vaccine_destroy" @close="confirm_vaccine_destroy = false">
            <template #title>
                Confirmar Exclusão de Vacina
            </template>
            <template #content>
                <p>Deseja realmente excluir a vacina {{ vaccine.name }}? Esta ação não poderá ser desfeita.</p>
            </template>
            <template #footer>
                <SecondaryButton @click="confirm_vaccine_destroy = false">Cancelar</SecondaryButton>
                <DangerButton @click="vaccineDestroy()">Sim, Excluir</DangerButton>
            </template>
        </DialogModal>

        <DialogModal maxWidth="md" :show="show_vaccine_lot_modal" @close="show_vaccine_lot_modal = false">
            <template #title>
                Lote de Vacina
            </template>
            <template #content>
                <form @submit.prevent="submitVaccineLotForm()" class="space-y-4">
                    <div class="">
                        <InputLabel>ID do Lote</InputLabel>
                        <div class="flex flex-col mt-2">
                            <TextInput v-model="vaccine_lot_form.lot_id" class="w-full" />
                            <InputError class="mt-2" :message="vaccine_lot_form.errors.lot_id" />
                        </div>
                    </div>

                    <div class="">
                        <InputLabel>Data de Validade</InputLabel>
                        <div class="flex flex-col mt-2">
                            <TextInput type="date" v-model="vaccine_lot_form.expiration_date" class="w-full" />
                            <InputError class="mt-2" :message="vaccine_lot_form.errors.expiration_date" />
                        </div>
                    </div>

                    <div class="flex flex-row justify-between">
                        <DangerButton @click="confirmVaccineLotDestroy()">Remover Lote</DangerButton>
                        <div class="flex flex-row space-x-4 justify-end">
                            <SecondaryButton @click="show_vaccine_lot_modal = false">Cancelar</SecondaryButton>
                            <PrimaryButton v-if="vaccine_lot_form.isDirty" type="submit">Salvar</PrimaryButton>
                        </div>
                    </div>
                </form>
            </template>
        </DialogModal>

        <DialogModal maxWidth="md" :show="confirm_vaccine_lot_destroy" @close="confirm_vaccine_lot_destroy = false">
            <template #title>
                Confirmar Exclusão de Lote de Vacina
            </template>
            <template #content>
                <p>Deseja realmente excluir o lote {{ vaccine_lot_form.lot_id }} data vacina {{ vaccine.name }}?</p>
            </template>
            <template #footer>
                <SecondaryButton @click="confirm_vaccine_lot_destroy = false">Cancelar</SecondaryButton>
                <DangerButton @click="vaccineLotDestroy()">Sim, Excluir</DangerButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>
