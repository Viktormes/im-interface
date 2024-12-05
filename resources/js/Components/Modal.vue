<script setup>
import {watch} from "vue";

defineOptions({
    inheritAttrs: false,
});

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(['close']);

function onKeyDown(e) {
    if (e.key === 'Escape') emit('close');
}

watch(() => props.show, v => {
    if (v) {
        window.addEventListener('keydown', onKeyDown);
    } else {
        window.removeEventListener('keydown', onKeyDown);
    }
});
</script>

<template>
    <Transition enter-from-class="opacity-0" leave-to-class="opacity-0">
        <div v-if="show"
             class="absolute z-50 bg-white rounded-lg p-4 shadow-lg border border-neutral-70 transition-opacity"
             v-bind="$attrs"
        >
            <slot/>
        </div>
    </Transition>

    <Transition enter-from-class="opacity-0" leave-to-class="opacity-0">
        <div v-if="show"
             class="fixed z-40 inset-0 bg-black/20 transition-opacity cursor-pointer"
             @click="$emit('close')"
        ></div>
    </Transition>
</template>
