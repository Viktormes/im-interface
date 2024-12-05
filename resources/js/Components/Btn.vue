<script setup>
const props = defineProps({
    primary: Boolean,
    disabled: Boolean,
    large: Boolean,
});
</script>

<template>
    <button
        :class="[
            primary ? 'bg-healthcare hover:bg-healthcare-50 text-white' : 'hover:bg-healthcare/10 text-healthcare',
            disabled ? 'pointer-events-none opacity-50 border-neutral-20' : '',
            disabled ? (primary ? 'bg-neutral-20' : 'text-neutral-20 bg-neutral-80') : '',
            large ? 'py-1.5 leading-6 px-4 !text-sm' : 'py-1 leading-5 px-2 text-xs',
        ]"
        :disabled="disabled"
        class="rounded-lg border-2 border-healthcare font-bold flex items-center justify-center gap-2 select-none focus-visible:outline-offset-4"
        type="button"
    >
        <slot/>
    </button>
</template>
