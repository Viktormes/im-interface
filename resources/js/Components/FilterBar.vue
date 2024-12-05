<script setup>
import IconCheckSquare from "@/Icons/IconCheckSquare.vue";
import IconSquare from "@/Icons/IconSquare.vue";
import IconLungs from "@/Icons/IconLungs.vue";
import IconInformation from "@/Icons/IconInformation.vue";
import IconAdd from "@/Icons/IconAdd.vue";
import ToggleButton from "@/Components/ToggleButton.vue";
import IconCardiology from "@/Icons/IconCardiology.vue";
import IconSleepy from "@/Icons/IconSleepy.vue";
import IconFitness from "@/Icons/IconFitness.vue";
import {computed} from "vue";
import Btn from "@/Components/Btn.vue";

const props = defineProps({
    modelValue: Object,
    hoveredFilter: String,
});

const emit = defineEmits(['update:modelValue', 'update:hoveredFilter']);

const toggleAll = computed({
    get: () => !Object.keys(proxy.value).some(k => !proxy.value[k]),
    set(v) {
        proxy.value.lungs = v;
        proxy.value.heart = v;
        proxy.value.fitness = v;
        proxy.value.other = v;
        proxy.value.sleep = v;
    }
});

const proxy = computed({
    get: () => props.modelValue,
    set(value) {
    }
});

function onEnter(category) {
    emit('update:hoveredFilter', category);
}

function onLeave() {
    emit('update:hoveredFilter', null);
}
</script>

<template>
    <div class="flex items-center gap-2">
        <ToggleButton v-model="toggleAll">
            <component :is="toggleAll ? IconCheckSquare : IconSquare" class="size-4 my-0.5 shrink-0 -mx-0.5"/>
        </ToggleButton>
        <div
            class="flex *:rounded-none first:*:rounded-l-lg last:*:rounded-r-lg *:border-x first:*:border-l-2 last:*:border-r-2 *:px-2.5">
            <ToggleButton v-model="proxy.heart" @mouseenter="onEnter('heart')"
                          @mouseleave="onLeave">
                <IconCardiology class="size-5 shrink-0"/>
                <span>Hjärta</span>
            </ToggleButton>
            <ToggleButton v-model="proxy.lungs" @mouseenter="onEnter('lungs')" @mouseleave="onLeave">
                <IconLungs class="size-4 shrink-0"/>
                <span>Lungor</span>
            </ToggleButton>
            <ToggleButton v-model="proxy.sleep" @mouseenter="onEnter('sleep')" @mouseleave="onLeave">
                <IconSleepy class="size-4 shrink-0"/>
                <span>Sömn</span>
            </ToggleButton>
            <ToggleButton v-model="proxy.fitness" @mouseenter="onEnter('fitness')" @mouseleave="onLeave">
                <IconFitness class="size-4 shrink-0"/>
                <span>Funktion</span>
            </ToggleButton>
            <ToggleButton v-model="proxy.other" @mouseenter="onEnter('other')" @mouseleave="onLeave">
                <IconInformation class="size-4 shrink-0"/>
                <span>Övriga</span>
            </ToggleButton>
        </div>
        <Btn disabled primary>
            <IconAdd class="size-4 -ml-1 shrink-0"/>
            <span class="whitespace-nowrap">Lägg till egen</span>
        </Btn>
    </div>
</template>
