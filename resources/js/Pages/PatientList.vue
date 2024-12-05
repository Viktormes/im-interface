<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link} from '@inertiajs/vue3';
import LayoutSlot from "@/Components/LayoutSlot.vue";
import IconWarning from "@/Icons/IconWarning.vue";
import dayjs from "dayjs";
import IconTrendUp from "@/Icons/IconTrendUp.vue";
import IconTrendNeutral from "@/Icons/IconTrendNeutral.vue";
import IconExclamation from "@/Icons/IconExclamation.vue";
import IconTrendDown from "@/Icons/IconTrendDown.vue";
import {usePresenter} from "@/Composables/usePresenter.js";
import HeaderPractitioner from "@/Components/HeaderPractitioner.vue";

defineOptions({
    layout: AuthenticatedLayout,
});

defineProps({
    patients: Array,
});

const present = usePresenter();
</script>

<template>
    <Head title="Alla Patienter"/>

    <LayoutSlot slot="header">
        <HeaderPractitioner :practitioner="$page.props.auth.user"/>
    </LayoutSlot>

    <h1 class="text-xl font-medium font-heading">Patienter - indikation på försämring</h1>

    <div class="border border-neutral-80 rounded-md overflow-x-auto mt-4">
        <table class="text-xs text-left w-full">
            <thead>
            <tr class="whitespace-nowrap text-2xs border-b border-neutral-80 [&>*]:bg-healthcare-95 [&>*]:p-3">
                <th>Status</th>
                <th>Namn</th>
                <th>Personnummer</th>
                <th>Läkemedelsbehandling</th>
                <th>Patientansvarig läkare</th>
                <th>Senast inskickade värden</th>
                <th>BASDAI</th>
                <th>Symtomskattning</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-neutral-95">
            <tr v-for="patient in patients" :key="patient.id"
                class="bg-neutral-99 odd:bg-white">
                <td class="p-3 w-1">
                    <IconWarning class="mx-auto text-culture-50"/>
                </td>
                <td class="p-3 font-bold">
                    <Link :href="'/patients/select/' + patient.id" class="hover:underline">
                        {{ present(patient).name }}
                    </Link>
                </td>
                <td class="p-3">{{ present(patient).ssn }}</td>
                <td class="p-3">Adalimumab</td>
                <td class="p-3">{{ present(patient).gp }}</td>
                <td class="p-3">{{ present(patient).lastObservation }}</td>
                <td class="p-3">
                    <div class="flex items-center gap-2">
                        <span>6.9</span>
                        <IconTrendUp class="size-6 text-culture-50"/>
                    </div>
                </td>
                <td class="p-3">8.3</td>
            </tr>
            <tr class="bg-neutral-99 odd:bg-white">
                <td class="p-3 w-1">
                    <IconExclamation class="mx-auto text-yellow-70"/>
                </td>
                <td class="p-3 font-bold text-neutral-60">
                    Antonio Garcia
                </td>
                <td class="p-3">19960321-6892</td>
                <td class="p-3">Adalimumab, NSAID</td>
                <td class="p-3">Benny Olson</td>
                <td class="p-3">{{ dayjs().format('YYYY-MM-DD HH:mm') }}</td>
                <td class="p-3">
                    <div class="flex items-center gap-2">
                        <span>4.1</span>
                        <IconTrendNeutral class="size-6 text-neutral-50"/>
                    </div>
                </td>
                <td class="p-3">7</td>
            </tr>
            </tbody>
        </table>
    </div>

    <h1 class="text-xl font-medium font-heading mt-10">Patienter - övriga</h1>

    <div class="border border-neutral-80 rounded-md overflow-x-auto mt-4">
        <table class="text-xs text-left w-full">
            <thead>
            <tr class="whitespace-nowrap text-2xs border-b border-neutral-80 [&>*]:bg-healthcare-95 [&>*]:p-3">
                <th>Status</th>
                <th>Namn</th>
                <th>Personnummer</th>
                <th>Läkemedelsbehandling</th>
                <th>Patientansvarig läkare</th>
                <th>Senast inskickade värden</th>
                <th>BASDAI</th>
                <th>Symtomskattning</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-neutral-95">
            <tr class="bg-neutral-99 odd:bg-white">
                <td class="p-3 w-1"></td>
                <td class="p-3 font-bold text-neutral-60">
                    Albin Svensson
                </td>
                <td class="p-3">19911011-1917</td>
                <td class="p-3">Secukinumab</td>
                <td class="p-3">Benny Olson</td>
                <td class="p-3">{{ dayjs().format('YYYY-MM-DD HH:mm') }}</td>
                <td class="p-3">
                    <div class="flex items-center gap-2">
                        <span>1.6</span>
                        <IconTrendNeutral class="size-6 text-neutral-50"/>
                    </div>
                </td>
                <td class="p-3">2.1</td>
            </tr>
            <tr class="bg-neutral-99 odd:bg-white">
                <td class="p-3 w-1"></td>
                <td class="p-3 font-bold text-neutral-60">
                    Edgar Smith
                </td>
                <td class="p-3">19840318-1905</td>
                <td class="p-3">Adalimumab, NSAID</td>
                <td class="p-3">Benny Olson</td>
                <td class="p-3">{{ dayjs().format('YYYY-MM-DD HH:mm') }}</td>
                <td class="p-3">
                    <div class="flex items-center gap-2">
                        <span>2.5</span>
                        <IconTrendDown class="size-6 text-green-50"/>
                    </div>
                </td>
                <td class="p-3">3.9</td>
            </tr>
            </tbody>
        </table>
    </div>


</template>
