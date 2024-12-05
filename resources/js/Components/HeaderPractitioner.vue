<script setup>
import {usePresenter} from "@/Composables/usePresenter.js";

defineProps({
    practitioner: Object,
});

const present = usePresenter();
</script>

<template>
    <div class="flex flex-col items-start md:flex-row md:items-end gap-y-4 justify-between">
        <div class="flex flex-col items-start gap-1">
            <div class="font-bold text-xl font-heading">
                Alla Patienter
            </div>
            <div class="space-y-1 mt-2">
                <div class="text-sm">
                    <span class="font-bold">Läk. {{ present(practitioner).name }}</span>, HSA-ID:
                    {{ present(practitioner).hsaId }}
                </div>
                <div class="text-sm opacity-65">
                    Reumatologimottagning Sahlgrenska
                </div>
            </div>
        </div>
    </div>
</template>
