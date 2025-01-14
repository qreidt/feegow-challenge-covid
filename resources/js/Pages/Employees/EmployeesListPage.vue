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
import Select from "@/Components/Form/Select.vue";
import DangerButton from "@/Components/Jetstream/DangerButton.vue";

defineProps({
    employees: Object,
    vaccines: Array,
});

const show_employee_form_modal = ref(false);
const confirm_employee_archive_modal = ref(false);

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
const search_form = useForm({
    query: '', items_per_page: 16, archived: 'false',
});

function openEmployeeFormModal(employee = null) {
    employee_form = useForm(default_employee_form);

    if (employee) {
        const data = Object.assign({}, {...default_employee_form, ...employee});
        employee_form = useForm(data);
    }

    show_employee_form_modal.value = true;
}

function submitEmployeeForm() {
    if (! employee_form.id) {
        return employee_form.post(route('employees.store'), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                show_employee_form_modal.value = false;
            }
        });
    }

    return employee_form.patch(route('employees.update', employee_form.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            show_employee_form_modal.value = false;
        }
    });
}

function confirmEmployeeArchive(employee) {
    const data = Object.assign({}, {...default_employee_form, ...employee});
    employee_form = useForm(data);

    confirm_employee_archive_modal.value = true;
}

function archiveEmployee() {
    employee_form.delete(route('employees.destroy', employee_form.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirm_employee_archive_modal.value = false;
            employee_form = useForm(default_employee_form);
        }
    })
}

function submitSearch() {
    search_form.get(route('employees.index'), {
        preserveScroll: false,
        preserveState: true,
    });
}

function resetSearch() {
    search_form.reset();
    search_form.get(route('employees.index'), {
        preserveScroll: false,
        preserveState: true,
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <div class="bg-white dark:bg-slate-800 py-2 overflow-hidden shadow-xl sm:rounded-lg px-4 sm:px-6 lg:px-8">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8 text-gray-800 dark:text-gray-50">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <h2 class="text-lg py-4">Opções de Busca</h2>

                            <form @submit.prevent="submitSearch" class="grid md:grid-cols-12 gap-4">
                                <div class="sm:col-span-7">
                                    <InputLabel>Busca Simples</InputLabel>
                                    <div class="flex flex-col mt-2">
                                        <TextInput v-model="search_form.query" class="w-full"
                                                   placeholder="Buscar por Nome ou CPF" />
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <InputLabel>Itens por Página</InputLabel>
                                    <div class="flex flex-col mt-2">
                                        <TextInput type="number" v-model="search_form.items_per_page" class="w-full"
                                                   placeholder="Buscar por Nome ou CPF" />
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <InputLabel>Arquivados</InputLabel>
                                    <div class="flex flex-col mt-2">
                                        <Select @change="submitSearch" v-model="search_form.archived" class="w-full">
                                            <option value="true">Sim</option>
                                            <option value="false">Não</option>
                                        </Select>
                                    </div>
                                </div>

                                <div class="col-span-full flex flex-row space-x-4 justify-end">
                                    <SecondaryButton @click="resetSearch" :disabled="search_form.processing">
                                        Reset
                                    </SecondaryButton>
                                    <PrimaryButton type="submit" :disabled="search_form.processing">
                                        Buscar
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <EmployeesList
                    v-bind="{employees}" @rowClicked="openEmployeeFormModal"
                    @confirmDelete="confirmEmployeeArchive" />
            </div>
        </div>

        <DialogModal maxWidth="xl" :show="show_employee_form_modal" @close="show_employee_form_modal = false"
            :closeable="! employee_form.processing">
            <template #title>
                {{ employee_form.id ? 'Atualizar Ficha de Funcionário' : 'Nova Ficha de Funcionário'}}
            </template>
            <template #content>
                <form @submit.prevent="submitEmployeeForm()" class="space-y-4">
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <InputLabel>Nome</InputLabel>
                            <div class="flex flex-col mt-2">
                                <TextInput v-model="employee_form.name" class="w-full" placeholder="Ex: João Silva" />
                                <InputError class="mt-2" :message="employee_form.errors.name" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>CPF</InputLabel>
                            <div class="flex flex-col mt-2">
                                <TextInput v-model="employee_form.cpf" class="w-full uppercase"
                                           placeholder="Ex: 123.456.789-00" :disabled="employee_form.id" />
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
                            <div class="flex flex-col mt-2">
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
                                    <div class="flex flex-col mt-2">
                                        <TextInput type="date" v-model="employee_form.dose_1.applied_at" class="w-full"/>
                                        <InputError class="mt-2" :message="employee_form.errors['dose_1.applied_at']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_1.vaccine_id" class="">
                                    <InputLabel>Lote Vacina</InputLabel>
                                    <div class="flex flex-col mt-2">
                                        <TextInput
                                            v-model="employee_form.dose_1.lot_id"
                                            class="w-full" placeholder="Ex: XXX-9876" />
                                        <InputError class="mt-2" :message="employee_form.errors['dose_1.lot_id']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_1.vaccine_id" class="">
                                    <InputLabel>Validade do Lote</InputLabel>
                                    <div class="flex flex-col mt-2">
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
                                    <div class="flex flex-col mt-2">
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
                                    <div class="flex flex-col mt-2">
                                        <TextInput type="date" v-model="employee_form.dose_2.applied_at" class="w-full"/>
                                        <InputError class="mt-2" :message="employee_form.errors['dose_2.applied_at']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_2.vaccine_id" class="">
                                    <InputLabel>Lote Vacina</InputLabel>
                                    <div class="flex flex-col mt-2">
                                        <TextInput
                                            v-model="employee_form.dose_2.lot_id"
                                            class="w-full" placeholder="Ex: XXX-9876" />
                                        <InputError class="mt-2" :message="employee_form.errors['dose_2.lot_id']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_2.vaccine_id" class="">
                                    <InputLabel>Validade do Lote</InputLabel>
                                    <div class="flex flex-col mt-2">
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
                                    <div class="flex flex-col mt-2">
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
                                    <div class="flex flex-col mt-2">
                                        <TextInput type="date" v-model="employee_form.dose_3.applied_at" class="w-full"/>
                                        <InputError class="mt-2" :message="employee_form.errors['dose_3.applied_at']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_3.vaccine_id" class="">
                                    <InputLabel>Lote Vacina</InputLabel>
                                    <div class="flex flex-col mt-2">
                                        <TextInput
                                            v-model="employee_form.dose_3.lot_id"
                                            class="w-full" placeholder="Ex: XXX-9876" />
                                        <InputError class="mt-2" :message="employee_form.errors['dose_3.lot_id']" />
                                    </div>
                                </div>

                                <div v-if="employee_form.dose_3.vaccine_id" class="">
                                    <InputLabel>Validade do Lote</InputLabel>
                                    <div class="flex flex-col mt-2">
                                        <TextInput type="date" v-model="employee_form.dose_3.expiration_date" class="w-full"/>
                                        <InputError class="mt-2" :message="employee_form.errors['dose_3.expiration_date']" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="flex flex-row space-x-4 justify-end">
                        <SecondaryButton @click="show_employee_form_modal = false"
                                         :disabled="employee_form.processing">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton type="submit" :disabled="employee_form.processing">Salvar</PrimaryButton>
                    </div>
                </form>
            </template>
        </DialogModal>

        <DialogModal
            maxWidth="md" :show="confirm_employee_archive_modal" @close="confirm_employee_archive_modal = false"
            :closeable="! employee_form.processing"
        >
            <template #title>
                Confirmar Arquivamento de Funcionário
            </template>
            <template #content>
                <p class="">Deseja realmente arquivar a ficha do funcionário {{ employee_form.name }}?</p>
                <p class="mt-2">O funcionário não irá mais aparecer em listagens.</p>
                <p class="mt-2">Esta ação poderá ser desfeita futuramente.</p>
            </template>
            <template #footer>
                <SecondaryButton @click="confirm_employee_archive_modal = false"
                                 :disabled="employee_form.processing">
                    Cancelar
                </SecondaryButton>
                <DangerButton @click="archiveEmployee()" :disabled="employee_form.processing">
                    Sim, Arquivar
                </DangerButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>
