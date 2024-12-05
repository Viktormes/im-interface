<script setup>
import Btn from "@/Components/Btn.vue";

const props = defineProps({
    modelValue: Boolean,
});

defineEmits(['update:modelValue']);
</script>

<template>
    <Btn :primary="modelValue !== false" @click="$emit('update:modelValue', modelValue === false)">
        <slot/>
    </Btn>
</template>
