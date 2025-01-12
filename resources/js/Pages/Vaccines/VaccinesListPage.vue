<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import VaccinesList from "@/Components/VaccinesList.vue";
import PrimaryButton from "@/Components/Jetstream/PrimaryButton.vue";
defineProps({
    vaccines: Array,
});
</script>

<template>
    <AppLayout title="Vacinas">
        <template #header>
            Vacinas
        </template>

        <template #actions>
            <PrimaryButton>
                Cadastrar Vacina
            </PrimaryButton>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <VaccinesList v-bind="{vaccines}"/>
            </div>
        </div>
    </AppLayout>
</template>
