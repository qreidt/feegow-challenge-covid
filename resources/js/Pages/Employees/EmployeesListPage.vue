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
import {useForm} from "@inertiajs/vue3";
import Checkbox from "@/Components/Jetstream/Checkbox.vue";
import Select from "@/Components/Form/Select.vue";
import {$dateFormat} from "@/Helpers/date-helper.js";
defineProps({
    employees: Object,
    vaccines: Array,
});

const show_employee_form_modal = ref(false);
const default_employee_form = {
    id: null,
    name: '',
    cpf: '',
    birth_date: '',
    has_comorbidity: false,

    dose_1: {vaccine_id: '', vaccine_lot_id: null, lot_id: '', expiration_date: null, applied_at: null},
    dose_2: {vaccine_id: '', vaccine_lot_id: null, lot_id: '', expiration_date: null, applied_at: null},
    dose_3: {vaccine_id: '', vaccine_lot_id: null, lot_id: '', expiration_date: null, applied_at: null},
};

let employee_form = useForm(default_employee_form);

function openEmployeeFormModal(employee = null) {
    employee_form = useForm(default_employee_form);

    if (employee) {
        const data = Object.assign({}, {...default_employee_form, ...employee});
        employee_form = useForm(data);
    }

    show_employee_form_modal.value = true;
}

function submitEmployeeForm() {
    employee_form.slug = employee_form.slug.toUpperCase();
    employee_form.post(route('employees.store'), {
        onSuccess: toggleCreateModal
    });
}
</script>

<template>
    <AppLayout title="Vacinas">
        <template #header>
            Funcionários ({{employees.total}})
        </template>

        <template #actions>
            <PrimaryButton @click="openEmployeeFormModal()">
                Cadastrar Funcionário
            </PrimaryButton>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <EmployeesList v-bind="{employees}" @rowClicked="openEmployeeFormModal" />
            </div>
        </div>

        <DialogModal maxWidth="xl" :show="show_employee_form_modal" @close="show_employee_form_modal = false">
            <template #title>
                {{ employee_form.id ? 'Atualizar Ficha de Funcionário' : 'Nova Ficha de Funcionário'}}
            </template>
            <template #content>
                <form @submit.prevent="submitEmployeeForm()" class="space-y-4">
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <InputLabel>Nome</InputLabel>
                            <div class="flex flex-col mt-2">
                                <TextInput v-model="employee_form.name" class="w-full" />
                                <InputError class="mt-2" :message="employee_form.errors.name" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>CPF</InputLabel>
                            <div class="flex flex-col mt-2">
                                <TextInput v-model="employee_form.cpf" class="w-full uppercase" />
                                <InputError class="mt-2" :message="employee_form.errors.cpf" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Data de Nascimento</InputLabel>
                            <div class="flex flex-col mt-2">
                                <TextInput type="date" v-model="employee_form.birth_date" class="w-full" />
                                <InputError class="mt-2" :message="employee_form.errors.birth_date" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Possui Comorbidade</InputLabel>
                            <div class="flex flex-row mt-2">
                                <Select v-model="employee_form.has_comorbidity" class="w-full">
                                    <option :value="true">Sim</option>
                                    <option :value="false">Não</option>
                                </Select>
                            </div>
                        </div>

                        <div class="col-span-full flex flex-col mt-2 space-y-2">
                            <p class="text-sm border-l-2 border-gray-700 pl-2">1ª dose</p>

                            <div class="ml-2 grid md:grid-cols-2 gap-x-3 gap-y-4">
                                <div class="">
                                    <InputLabel>Marca Vacina</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <Select v-model="employee_form.dose_1.vaccine_id" class="w-full">
                                            <option value="">Pendente</option>
                                            <option v-for="vaccine in vaccines" :value="vaccine.id">
                                                {{ vaccine.slug }}
                                            </option>
                                        </Select>
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_1.vaccine_id" class="">
                                    <InputLabel>Data Aplicação</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <TextInput type="date" v-model="employee_form.dose_1.applied_at" class="w-full"/>
                                        <InputError class="mt-2" :message="employee_form.errors['dose_1.applied_at']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_1.vaccine_id" class="">
                                    <InputLabel>Lote Vacina</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <TextInput
                                            v-model="employee_form.dose_1.lot_id"
                                            class="w-full" placeholder="Ex: XXX-9876" />
                                        <InputError class="mt-2" :message="employee_form.errors['dose_1.lot_id']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_1.vaccine_id" class="">
                                    <InputLabel>Validade do Lote</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <TextInput type="date" v-model="employee_form.dose_1.expiration_date" class="w-full"/>
                                        <InputError class="mt-2" :message="employee_form.errors['dose_1.expiration_date']" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-full flex flex-col mt-2 space-y-2">
                            <p class="text-sm border-l-2 border-gray-700 pl-2">2ª dose</p>

                            <div class="ml-2 grid md:grid-cols-2 gap-x-3 gap-y-4">
                                <div class="">
                                    <InputLabel>Marca Vacina</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <Select v-model="employee_form.dose_2.vaccine_id" class="w-full">
                                            <option value="">Pendente</option>
                                            <option v-for="vaccine in vaccines" :value="vaccine.id">
                                                {{ vaccine.slug }}
                                            </option>
                                        </Select>
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_2.vaccine_id" class="">
                                    <InputLabel>Data Aplicação</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <TextInput type="date" v-model="employee_form.dose_2.applied_at" class="w-full"/>
                                        <InputError class="mt-2" :message="employee_form.errors['dose_2.applied_at']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_2.vaccine_id" class="">
                                    <InputLabel>Lote Vacina</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <TextInput
                                            v-model="employee_form.dose_2.lot_id"
                                            class="w-full" placeholder="Ex: XXX-9876" />
                                        <InputError class="mt-2" :message="employee_form.errors['dose_2.lot_id']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_2.vaccine_id" class="">
                                    <InputLabel>Validade do Lote</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <TextInput type="date" v-model="employee_form.dose_2.expiration_date" class="w-full"/>
                                        <InputError class="mt-2" :message="employee_form.errors['dose_2.expiration_date']" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-full flex flex-col mt-2 space-y-2">
                            <p class="text-sm border-l-2 border-gray-700 pl-2">3ª dose</p>

                            <div class="ml-2 grid md:grid-cols-2 gap-x-3 gap-y-4">
                                <div class="">
                                    <InputLabel>Marca Vacina</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <Select v-model="employee_form.dose_3.vaccine_id" class="w-full">
                                            <option value="">Pendente</option>
                                            <option v-for="vaccine in vaccines" :value="vaccine.id">
                                                {{ vaccine.slug }}
                                            </option>
                                        </Select>
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_3.vaccine_id" class="">
                                    <InputLabel>Data Aplicação</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <TextInput type="date" v-model="employee_form.dose_3.applied_at" class="w-full"/>
                                        <InputError class="mt-2" :message="employee_form.errors['dose_3.applied_at']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_3.vaccine_id" class="">
                                    <InputLabel>Lote Vacina</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <TextInput
                                            v-model="employee_form.dose_3.lot_id"
                                            class="w-full" placeholder="Ex: XXX-9876" />
                                        <InputError class="mt-2" :message="employee_form.errors['dose_3.lot_id']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_3.vaccine_id" class="">
                                    <InputLabel>Validade do Lote</InputLabel>
                                    <div class="flex flex-row mt-2">
                                        <TextInput type="date" v-model="employee_form.dose_3.expiration_date" class="w-full"/>
                                        <InputError class="mt-2" :message="employee_form.errors['dose_3.expiration_date']" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="flex flex-row space-x-4 justify-end">
                        <SecondaryButton @click="show_employee_form_modal = false">Cancelar</SecondaryButton>
                        <PrimaryButton type="submit">Salvar</PrimaryButton>
                    </div>
                </form>
            </template>
        </DialogModal>
    </AppLayout>
</template>
