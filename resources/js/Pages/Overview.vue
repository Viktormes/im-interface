<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import LayoutSlot from "@/Components/LayoutSlot.vue";
import HeaderPatient from "@/Components/HeaderPatient.vue";
import {computed, reactive, ref} from "vue";
import dayjs from "dayjs";
import SearchBox from "@/Components/SearchBox.vue";
import PeriodPicker from "@/Components/PeriodPicker.vue";
import FilterBar from "@/Components/FilterBar.vue";
import Chart from "@/Components/Chart.vue";
import PatientReportedOutcomes from "@/Components/PatientReportedOutcomes.vue";

defineOptions({
    layout: AuthenticatedLayout,
});

const props = defineProps({
    period: Object,
    measurements: Array,
    proms: Array,
});

const start = computed(() => dayjs(props.period.start).startOf('day'));
const end = computed(() => dayjs(props.period.end).startOf('day'));
const accuracy = computed(() => props.period.accuracy);
const search = ref('');
const filters = reactive(Object.fromEntries(new URL(window.location.href).searchParams.get('filters')?.split(',').map(f => {
    return f.substring(0, 1) === '!' ? [f.substring(1), false] : [f, true];
}) || []));
const hoveredCategory = ref(null);

const filteredRows = computed(() => {
    const f = props.measurements.filter(m => filters[m.category] !== false);
    return (search.value ? f.filter(m => m.label.toLowerCase().indexOf(search.value.toLowerCase()) >= 0) : f)
        .toSorted((a, b) => a.label.localeCompare(b.label));
});

const filteredProms = computed(() => {
    return search.value ? props.proms.filter(m => m.label.toLowerCase().indexOf(search.value.toLowerCase()) >= 0) : props.proms;
});

function onApplyPeriod(e) {
    const filter = Object.keys(filters).map(f => (filters[f] ? '' : '!') + f).join(',');
    router.get(`?from=${e.from.format('YYYY-MM-DD')}&to=${e.to.format('YYYY-MM-DD')}&accuracy=${e.accuracy}&filters=${filter}`);
}
</script>

<template>
    <Head title="Översikt"/>

    <LayoutSlot v-if="$page.props.patient" slot="header">
        <HeaderPatient :patient="$page.props.patient"/>
    </LayoutSlot>

    <div class="flex items-start justify-between">
        <h1 class="text-xl font-medium font-heading">Översikt Mätvärden</h1>
        <SearchBox v-model="search"/>
    </div>

    <div class="flex flex-wrap max-2xl:flex-col items-center max-2xl:items-start gap-4 justify-between mt-5">
        <PeriodPicker :accuracy="accuracy" :end="end" :start="start" @apply="onApplyPeriod"/>
        <FilterBar v-model="filters" v-model:hovered-filter="hoveredCategory"/>
    </div>

    <div class="mt-8 space-y-5">
        <Chart v-for="m in filteredRows" :key="m.identifier" :chart="m" :class="{
            '!bg-healthcare-95': hoveredCategory === m.category,
        }" :period="period"/>

        <PatientReportedOutcomes :items="proms" :period="period"/>
    </div>
</template>
