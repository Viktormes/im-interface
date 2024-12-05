<script setup>
defineProps({
    y: Number,
    width: Number,
});
</script>

<template>
    <path :d="`M0,${y} L${width},${y}`" class="stroke stroke-neutral-80" stroke-dasharray="5"/>
</template>
