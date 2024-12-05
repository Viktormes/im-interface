<script setup>
import IconChevronRightOutline from "@/Icons/IconChevronRightOutline.vue";
import IconWarning from "@/Icons/IconWarning.vue";
import IconTrendDown from "@/Icons/IconTrendDown.vue";
import IconExclamation from "@/Icons/IconExclamation.vue";
import IconTrendUp from "@/Icons/IconTrendUp.vue";

const props = defineProps({
    label: String,
    value: [String, Number, null],
    unit: [String, undefined, null],
    note: [String, undefined, null],
    type: String,
    trend: String,
    change: String,
});
</script>

<template>
    <div class="relative">
        <div
            :class="{
                'border-culture-40 hover:bg-culture-95': type === 'danger',
                'border-yellow-40 hover:bg-yellow-99': type === 'warning',
            }"
            class="h-full bg-white rounded-lg border flex flex-col shadow-md hover:shadow-xl cursor-pointer transition-all peer"
        >
            <div class="flex items-center justify-between pl-3 pr-2 h-12">
                <div class="flex items-center gap-3">
                    <IconWarning v-if="type === 'danger'" class="size-5 text-culture"/>
                    <IconExclamation v-else-if="type === 'warning'" class="size-5 text-yellow-60"/>
                    <span>{{ label }}</span>
                </div>
                <IconChevronRightOutline class="size-6"/>
            </div>
            <div class="flex flex-col justify-center flex-1">
                <div class="flex items-center justify-between px-3">
                    <div class="flex items-end gap-1">
                        <template v-if="unit">
                            <span class="text-2xl font-bold">{{ value }}</span>
                            <span class="text-xs mb-1">{{ unit }}</span>
                        </template>
                        <span v-else class="text-2xl">{{ value }}</span>
                    </div>
                    <div
                        :class="{
                            'bg-culture-90 text-culture': type === 'danger',
                            'bg-yellow-95 text-yellow-50': type === 'warning',
                        }"
                        class="rounded flex items-center gap-1 text-xs px-1.5 py-1"
                    >
                        <span v-if="change">{{ change }}</span>
                        <IconTrendDown v-if="trend === 'down'" class="size-6"/>
                        <IconTrendUp v-else-if="trend === 'up'" class="size-6"/>
                    </div>
                </div>
                <div v-if="note" class="text-xs opacity-65 px-4 py-3 text-balance">
                    {{ note }}
                </div>
            </div>
        </div>

        <slot/>
    </div>
</template>
