<script setup>
import {usePresenter} from "@/Composables/usePresenter.js";

defineProps({
    patient: Object,
});

const present = usePresenter();
</script>

<template>
    <div class="flex flex-col items-start md:flex-row md:items-end gap-y-4 justify-between">
        <div class="flex flex-col items-start gap-1">
            <div class="font-bold text-xl font-heading">
                {{ present(patient).name }}
                ({{ present(patient).ssn }})
            </div>
            <div class="grid xl:grid-cols-2 gap-x-4 gap-y-1 text-xs mt-2">
                <div>
                    <span class="text-neutral-50">Diagnos: </span> {{ present(patient).diagnosis }}
                </div>
                <div>
                    <span class="text-neutral-50">Aktuell behandling: </span> -
                </div>
                <div>
                    <span class="text-neutral-50">PAL: </span> {{ present(patient).gp }}
                </div>
                <div>
                    <span class="text-neutral-50">PAFysio: </span> -
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
