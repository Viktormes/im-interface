<script setup>
import {computed} from "vue";

const props = defineProps({
    modelValue: Number,
});

const emit = defineEmits([
    'update:modelValue'
]);

const proxy = computed({
    get: () => props.modelValue,
    set(value) {
        emit('update:modelValue', Math.max(0, Math.min(99, +value || 0)));
    }
})
</script>

<template>
    <div class="flex items-center gap-1 justify-center">
        <button
            class="size-6 select-none bg-healthcare-99 hover:bg-healthcare-90 border font-bold border-healthcare rounded-full text-sm"
            type="button"
            @click="$emit('update:modelValue', Math.max(0, modelValue - 1))"
        >
            &minus;
        </button>

        <input v-model="proxy"
               class="w-8 px-1 bg-white border border-neutral-70 rounded-lg text-center text-sm [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
               max="99"
               min="0"
               type="number"/>

        <button
            class="size-6 select-none bg-healthcare-99 hover:bg-healthcare-90 border font-bold border-healthcare rounded-full text-sm"
            type="button"
            @click="$emit('update:modelValue', Math.min(99, modelValue + 1))"
        >
            &plus;
        </button>
    </div>
</template>
