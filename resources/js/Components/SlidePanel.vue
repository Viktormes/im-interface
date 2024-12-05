<script lang="ts" setup>
defineOptions({
    inheritAttrs: false,
});

defineProps<{
    open?: boolean
}>();
</script>

<template>
    <transition enter-from-class="!grid-rows-[0fr] !mt-0 !opacity-0" leave-to-class="!grid-rows-[0fr] !mt-0 !opacity-0">
        <div v-if="open" class="grid grid-rows-[1fr] transition-all duration-500 overflow-hidden" v-bind="$attrs">
            <div class="min-h-0">
                <slot/>
            </div>
        </div>
    </transition>
</template>
