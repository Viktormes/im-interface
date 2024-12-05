<script setup>
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    icon: Object,
    disabled: Boolean,
    active: Boolean,
});
</script>

<template>
    <Link :class="{
            'font-bold': active,
            'opacity-50 pointer-events-none': disabled,
          }"
          :disabled="disabled"
          :href="href"
          class="flex items-center text-healthcare-10 w-full space-x-3 px-2 h-14 rounded-lg hover:bg-healthcare-95 font-heading select-none"
    >
        <span :class="{'!bg-healthcare text-white': active}"
              class="p-1.5 rounded-md border-2 border-healthcare bg-healthcare-95 text-healthcare">
            <component :is="icon" class="size-6"/>
        </span>

        <span>
            <slot/>
        </span>
    </Link>
</template>
