<script setup>
import {ref} from "vue";
import AppLayout from '@/Layouts/AppLayout.vue';
import VaccinesList from "@/Components/VaccinesList.vue";
import PrimaryButton from "@/Components/Jetstream/PrimaryButton.vue";
import DialogModal from "@/Components/Jetstream/DialogModal.vue";
import SecondaryButton from "@/Components/Jetstream/SecondaryButton.vue";
import TextInput from "@/Components/Jetstream/TextInput.vue";
import InputLabel from "@/Components/Jetstream/InputLabel.vue";
import InputError from "@/Components/Jetstream/InputError.vue";
import {useForm} from "@inertiajs/vue3";
defineProps({
    vaccines: Array,
});

const show_create_modal = ref(false);
const default_vaccine_form = {
    name: '',
    slug: '',
};

const vaccine_form = useForm(default_vaccine_form);

function toggleCreateModal() {
    show_create_modal.value = ! show_create_modal.value;
    vaccine_form.reset();
}

function submitVaccineForm() {
    vaccine_form.slug = vaccine_form.slug.toUpperCase();
    vaccine_form.post(route('vaccines.store'), {
        onSuccess: toggleCreateModal
    });
}
</script>

<template>
    <AppLayout title="Vacinas">
        <template #header>
            Vacinas ({{vaccines.length}})
        </template>

        <template #actions>
            <PrimaryButton @click="toggleCreateModal()">
                Cadastrar Vacina
            </PrimaryButton>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <VaccinesList v-bind="{vaccines}"/>
            </div>
        </div>

        <DialogModal maxWidth="md" :show="show_create_modal" @close="show_create_modal = false">
            <template #title>
                Cadastrar Vacina
            </template>
            <template #content>
                <form @submit.prevent="submitVaccineForm()" class="space-y-4">
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

                    <div class="flex flex-row space-x-4 justify-end">
                        <SecondaryButton @click="toggleCreateModal">Cancelar</SecondaryButton>
                        <PrimaryButton type="submit">Salvar</PrimaryButton>
                    </div>
                </form>
            </template>
        </DialogModal>
    </AppLayout>
</template>
