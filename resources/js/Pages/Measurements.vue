<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import LayoutSlot from "@/Components/LayoutSlot.vue";
import HeaderPatient from "@/Components/HeaderPatient.vue";
import {computed, reactive, ref} from "vue";
import dayjs from "dayjs";
import {formatString} from "@/util.js";
import SearchBox from "@/Components/SearchBox.vue";
import MeasurementsTable from "@/Components/MeasurementsTable.vue";
import PeriodPicker from "@/Components/PeriodPicker.vue";
import FilterBar from "@/Components/FilterBar.vue";

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

const numCols = computed(() => end.value.startOf('day').diff(start.value, props.period.accuracy) + 1);
const filteredRows = computed(() => {
    const f = props.measurements.filter(m => filters[m.category] !== false);
    return (search.value ? f.filter(m => m.label.toLowerCase().indexOf(search.value.toLowerCase()) >= 0) : f)
        .toSorted((a, b) => a.label.localeCompare(b.label));
});
const highlightRows = computed(() => filteredRows.value
    .map((f, i) => (f.category === hoveredCategory.value ? i : null))
    .filter(f => f !== null));

const filteredProms = computed(() => {
    return search.value ? props.proms.filter(m => m.label.toLowerCase().indexOf(search.value.toLowerCase()) >= 0) : props.proms;
});

const search = ref('');
const hoveredCategory = ref(null);
const filters = reactive(Object.fromEntries(new URL(window.location.href).searchParams.get('filters')?.split(',').map(f => {
    return f.substring(0, 1) === '!' ? [f.substring(1), false] : [f, true];
}) || []));

const tableHeaders = computed(() => {
    const result = [
        {locked: true},
    ];

    for (let i = 0; i < numCols.value; i++) {
        let text = '';

        switch (props.period.accuracy) {
            case 'day':
                text = start.value.add(i, 'days').format('D/M');
                break;
            case 'week':
                text = 'v' + start.value.add(i, 'weeks').isoWeek();
                break;
            case 'month':
                text = start.value.add(i, 'months').format('MMM');
                break;
        }

        result.push({
            text,
        });
    }

    return result;
});

const tableRows = computed(() => {
    const result = filteredRows.value.map(v => {
        const values = v.data.map(dp => {
            if (dp === undefined || dp === null) return '-';

            return formatString(v.format, ...(Array.isArray(dp) ? dp : [dp]));
        });

        return [
            v.label,
            ...values,
        ];
    });

    const groupedProms = {};
    filteredProms.value.forEach(p => {
        groupedProms[p.label] = groupedProms[p.label] || [];
        groupedProms[p.label].push(p);
    });

    for (let k of Object.keys(groupedProms)) {
        const res = [k];

        for (let i = 0; i < numCols.value; i++) {
            res.push('-');
        }

        groupedProms[k].forEach(itm => {
            const index = dayjs(itm.dateTime).diff(start.value, accuracy.value);
            res[index] = formatString(itm.format, itm.value);
        });

        result.push(res);
    }

    return result;
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
        <h1 class="text-xl font-medium font-heading">Mätvärden &amp; skattningar</h1>
        <SearchBox v-model="search"/>
    </div>

    <div class="flex max-2xl:flex-col items-center max-2xl:items-start gap-4 justify-between mt-5">
        <PeriodPicker :accuracy="accuracy" :end="end" :start="start" @apply="onApplyPeriod"/>
        <FilterBar v-model="filters" v-model:hovered-filter="hoveredCategory"/>
    </div>

    <MeasurementsTable :headers="tableHeaders" :highlight-rows="highlightRows" :rows="tableRows"/>
</template>
