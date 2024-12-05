<script setup>
import IconSearch from "@/Icons/IconSearch.vue";
import {computed} from "vue";

const props = defineProps({
    modelValue: String,
});

const emit = defineEmits([
    'update:modelValue',
]);

const proxy = computed({
    get: () => props.modelValue,
    set: value => emit('update:modelValue', value),
})
</script>

<template>
    <div class="relative">
        <input v-model="proxy"
               class="bg-white rounded-lg border border-neutral-80 w-52 h-10 px-10 flex items-center gap-4 text-sm"
               placeholder="Sök" type="text">
        <IconSearch class="absolute size-5 left-2.5 top-2.5 pointer-events-none text-neutral-50"/>
        <button v-if="proxy?.length"
                class="absolute right-1 top-1 size-8 rounded-md text-lg hover:bg-neutral-90" type="button"
                @click="proxy = ''">
            &times;
        </button>
    </div>
</template>
