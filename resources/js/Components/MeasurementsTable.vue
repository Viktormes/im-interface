<script setup>
import {ref} from "vue";

const props = defineProps({
    headers: Array,
    rows: Array,
    highlightRows: Array,
});

const hoveredRow = ref(null);
const hoveredCol = ref(null);

function onLockedCellEnter(index) {
    hoveredRow.value = index;
}

function onLockedCellLeave() {
    hoveredRow.value = null;
}

function onUnlockedCellEnter(rowIndex, colIndex) {
    hoveredRow.value = rowIndex;
    hoveredCol.value = colIndex;
}

function onUnlockedCellLeave() {
    hoveredRow.value = null;
    hoveredCol.value = null;
}
</script>

<template>
    <div class="flex items-start mt-4 border border-neutral-80 rounded-md overflow-hidden">
        <div class="shrink-0 border-r border-neutral-80">
            <table class="text-xs text-left w-full [&_td]:whitespace-nowrap">
                <thead>
                <tr class="whitespace-nowrap text-2xs border-b border-neutral-80 *:bg-healthcare-95 *:p-3 *:text-center *:transition-all *:duration-100">
                    <template v-for="(header, colIndex) in headers" :key="colIndex">
                        <th v-if="header.locked">
                            {{ header.text || '\u00a0' }}
                        </th>
                    </template>
                </tr>
                </thead>
                <tbody class="divide-y divide-neutral-95">
                <tr v-for="(row, rowIndex) in rows" :key="rowIndex"
                    :class="{'*:!bg-healthcare-90': (hoveredRow === rowIndex || highlightRows?.includes(rowIndex))}"
                    class="*:p-3 *:text-center first:*:text-left first:*:pl-4 *:font-bold *:transition-all *:duration-100 bg-neutral-99 odd:bg-white">
                    <template v-for="(header, index) in headers" :key="index">
                        <td v-if="header.locked" @mouseenter="onLockedCellEnter(rowIndex)"
                            @mouseleave="onLockedCellLeave">
                            {{ row[index] }}
                        </td>
                    </template>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="overflow-x-auto flex-1">
            <table class="text-xs text-left w-full [&_td]:whitespace-nowrap">
                <thead>
                <tr class="whitespace-nowrap text-2xs border-b border-neutral-80 *:bg-healthcare-95 *:p-3 *:text-center *:transition-all *:duration-100">
                    <template v-for="(header, colIndex) in headers" :key="colIndex">
                        <th v-if="!header.locked"
                            :class="{'!bg-healthcare-90': hoveredCol !== null && hoveredCol === colIndex}">
                            {{ header.text || '\u00a0' }}
                        </th>
                    </template>
                </tr>
                </thead>
                <tbody class="divide-y divide-neutral-95">
                <tr v-for="(row, rowIndex) in rows" :key="rowIndex"
                    class="*:py-3 *:px-4 *:text-center *:transition-all *:duration-100 *:whitespace-nowrap bg-neutral-99 odd:bg-white">
                    <template v-for="(header, colIndex) in headers" :key="colIndex">
                        <td v-if="!header.locked"
                            :class="{
                                '!bg-healthcare-90': (
                                    (hoveredRow === rowIndex && (hoveredCol === null || colIndex < hoveredCol)) ||
                                    (hoveredCol !== null && hoveredCol === colIndex && rowIndex < hoveredRow) ||
                                    highlightRows?.includes(rowIndex)
                                ),
                                '!bg-healthcare-80': hoveredRow === rowIndex && hoveredCol === colIndex,
                            }"
                            @mouseenter="onUnlockedCellEnter(rowIndex, colIndex)"
                            @mouseleave="onUnlockedCellLeave">
                            {{ row[colIndex] }}
                        </td>
                    </template>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
