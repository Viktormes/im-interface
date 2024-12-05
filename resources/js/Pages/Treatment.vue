<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, usePage} from "@inertiajs/vue3";
import LayoutSlot from "@/Components/LayoutSlot.vue";
import HeaderPatient from "@/Components/HeaderPatient.vue";
import {usePresenter} from "@/Composables/usePresenter.js";
import {computed, reactive, ref} from "vue";
import IconAdd from "@/Icons/IconAdd.vue";
import IconPencil from "@/Icons/IconPencil.vue";
import Checkbox from "@/Components/Checkbox.vue";
import SlidePanel from "@/Components/SlidePanel.vue";
import Btn from "@/Components/Btn.vue";
import IconChevronDown from "@/Icons/IconChevronDown.vue";
import Modal from "@/Components/Modal.vue";
import IconWarning from "@/Icons/IconWarning.vue";

defineOptions({
    layout: AuthenticatedLayout,
});

const present = usePresenter();
const patient = computed(() => usePage().props.patient);
const practitioner = computed(() => usePage().props.auth.user);
const medicines = ref([
    {
        name: 'Ipren, 400 mg',
        type: 'Tablett',
        dose: '1x3',
        schedule: 'Dag',
        whenNecessary: null,
        prescribed: '2024-04-05'
    },
    {
        name: 'Alvedon, 500 mg',
        type: 'Tablett',
        dose: '1x2',
        schedule: 'Dag',
        whenNecessary: 1,
        prescribed: '2024-04-05'
    },
    {
        name: 'Naproxen, 250 mg',
        type: 'Tablett',
        dose: '1x2',
        schedule: 'Dag',
        whenNecessary: null,
        prescribed: '2024-04-07'
    },
]);

const messageStart = 'Hej ' + present(patient.value).firstName + '!\n\n';
const messageEnd = '\n\nMed vänliga hälsningar,\n' + present(practitioner.value).name + ' - Leg. Läkare';

const message = ref(messageStart + messageEnd);
const returnData = ref(false);
const bookAppointment = ref(false);
const digitalAppointment = ref(false);
const recommendedCare = ref(false);
const changesToMedicines = ref(false);
const prescriptionRenewal = ref(false);
const addedWorkouts = reactive({
    0: false,
    1: false,
    2: false,
    3: false,
});

const showMedicineModal = ref(false);

function openMedicineModal() {
    showMedicineModal.value = true;
}

function closeMedicineModal() {
    showMedicineModal.value = false;
}
</script>

<template>
    <Head title="Översikt"/>

    <LayoutSlot v-if="$page.props.patient" slot="header">
        <HeaderPatient :patient="$page.props.patient"/>
    </LayoutSlot>

    <h1 class="text-xl font-medium font-heading mb-5">Behandling &amp; Åtgärder</h1>

    <div class="flex gap-10 items-stretch">
        <div class="w-2/5 max-w-[500px]">
            <h3 class="text-base font-bold mb-3">
                Meddelande till {{ present(patient).firstName }}
            </h3>

            <textarea v-model="message" class="w-full border border-neutral-20 rounded-lg" rows="15"></textarea>

            <h3 class="text-base font-bold mb-3 mt-8">
                Standardbedömningar
            </h3>

            <ul class="space-y-3 text-sm">
                <li class="flex items-start justify-between gap-4 p-4 bg-white border border-healthcare-70 rounded-lg">
                    <span>Allt ser bra ut, återkom om läget förändras</span>
                    <Btn @click="message = messageStart + 'Allt ser bra ut, återkom om läget förändras.' + messageEnd">
                        <IconAdd class="size-5 shrink-0 -mx-1"/>
                        <span>Använd</span>
                    </Btn>
                </li>
                <li class="flex items-start justify-between gap-4 p-4 bg-white border border-healthcare-70 rounded-lg">
                    <span>Jag skulle vilja boka in en uppföljningstid för ytterligare tester och utvärdering</span>
                    <Btn
                        @click="message = messageStart + 'Jag skulle vilja boka in en uppföljningstid för ytterligare tester och utvärdering.' + messageEnd">
                        <IconAdd class="size-5 shrink-0 -mx-1"/>
                        <span>Använd</span>
                    </Btn>
                </li>
            </ul>
            <div class="flex flex-wrap gap-3 mt-3">
                <Btn>
                    <IconAdd class="size-5 shrink-0 -mx-1"/>
                    <span>Skapa ny standardmall</span>
                </Btn>
                <Btn>
                    <IconPencil class="size-4 shrink-0"/>
                    <span>Redigera mallar</span>
                </Btn>
            </div>
        </div>
        <div class="flex flex-col flex-1 border-l-2 border-neutral-80 pl-10">
            <h3 class="text-base font-bold mb-3">
                Standardbedömningar
            </h3>
            <div class="space-y-2">
                <div class="flex flex-wrap gap-x-8 gap-y-5">
                    <Checkbox v-model:checked="returnData" class="shrink-0 h-10">
                        Återför data
                    </Checkbox>
                </div>
                <div class="flex flex-wrap gap-x-8 gap-y-5">
                    <Checkbox v-model:checked="bookAppointment" class="shrink-0 h-10">
                        Boka besök
                    </Checkbox>
                    <Checkbox v-if="bookAppointment" v-model:checked="digitalAppointment" class="shrink-0 h-10">
                        Digitalt besök
                    </Checkbox>
                    <div v-if="bookAppointment" class="relative">
                        <input class="rounded-lg bg-white border border-neutral-50 h-10"
                               placeholder="Välj datum och tid"
                               type="datetime-local">
                    </div>
                </div>
            </div>

            <h3 class="text-base font-bold mb-3 mt-8">
                Ytterligare tillägg
            </h3>
            <div class="space-y-2">
                <div class="flex flex-wrap gap-x-8 gap-y-5">
                    <Checkbox v-model:checked="recommendedCare" class="shrink-0 h-10">
                        Rekommenderad egenvård
                    </Checkbox>
                </div>
                <SlidePanel :open="recommendedCare">
                    <div class="flex flex-wrap gap-x-8 gap-y-5 pb-8 pl-8">
                        <h4 class="">Rörlighetsträningsprogram</h4>
                        <ul class="w-full flex flex-wrap items-start gap-5">
                            <li v-for="i in 2" :key="i"
                                :class="{'!bg-healthcare text-white': addedWorkouts[i - 1]}"
                                class="p-4 bg-white border border-neutral-50 rounded-lg flex items-stretch gap-4 max-w-[430px]">
                                <div class="shrink-0 flex flex-col">
                                    <img alt="" class="size-20" src="/images/workout/agility-workout-1.svg"/>
                                    <div class="flex-1"></div>
                                    <div class="text-xs opacity-65 mt-2">15 minuter</div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="font-bold mb-2">
                                        Rörlighetsträningsprogram {{ i }}
                                    </div>
                                    <div class="text-sm">
                                        Katt-ko stretch, Liggande rotation, Bröstryggsrotation & Höftböjarstretch.
                                    </div>
                                    <div class="flex-1"></div>
                                    <div class="mt-4">
                                        <Btn :class="{'border-white': addedWorkouts[i-1]}" :primary="addedWorkouts[i-1]"
                                             class="shrink-0 ml-auto"
                                             @click="addedWorkouts[i-1] = !addedWorkouts[i-1]">
                                            <template v-if="addedWorkouts[i-1]">
                                                <span class="text-lg leading-4 -mt-0.5">&times;</span>
                                                <span>Ta bort</span>
                                            </template>
                                            <template v-else>
                                                <IconAdd class="size-5 -mx-1 shrink-0"/>
                                                <span>Lägg till</span>
                                            </template>
                                        </Btn>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <h4>Stryketräningsprogram</h4>
                        <ul class="w-full flex flex-wrap items-start gap-5">
                            <li v-for="i in 2" :key="i"
                                :class="{'!bg-healthcare text-white': addedWorkouts[i + 1]}"
                                class="p-4 bg-white border border-neutral-50 rounded-lg flex items-stretch gap-4 max-w-[430px]">
                                <div class="shrink-0 flex flex-col">
                                    <img alt="" class="size-20" src="/images/workout/agility-workout-1.svg"/>
                                    <div class="flex-1"></div>
                                    <div class="text-xs opacity-65 mt-2">15 minuter</div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="font-bold mb-2">
                                        Styrketräningsprogram {{ i }}
                                    </div>
                                    <div class="text-sm">
                                        Katt-ko stretch, Liggande rotation, Bröstryggsrotation & Höftböjarstretch.
                                    </div>
                                    <div class="flex-1"></div>
                                    <div class="mt-4">
                                        <Btn :class="{'border-white': addedWorkouts[i+1]}" :primary="addedWorkouts[i+1]"
                                             class="shrink-0 ml-auto"
                                             @click="addedWorkouts[i+1] = !addedWorkouts[i+1]">
                                            <template v-if="addedWorkouts[i+1]">
                                                <span class="text-lg leading-4 -mt-0.5">&times;</span>
                                                <span>Ta bort</span>
                                            </template>
                                            <template v-else>
                                                <IconAdd class="size-5 -mx-1 shrink-0"/>
                                                <span>Lägg till</span>
                                            </template>
                                        </Btn>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </SlidePanel>
                <div class="flex flex-wrap gap-x-8 gap-y-5">
                    <Checkbox v-model:checked="changesToMedicines" class="shrink-0 h-10">
                        Förändring av läkemedel
                    </Checkbox>
                </div>
                <SlidePanel :open="changesToMedicines">
                    <div class="pb-8 pl-8">
                        <div class="border border-neutral-90 rounded-md overflow-x-auto mt-4">
                            <table class="text-xs text-left w-full">
                                <thead>
                                <tr class="whitespace-nowrap text-xs border-b border-neutral-90 *:bg-healthcare-95 *:p-3">
                                    <th>Läkemedel</th>
                                    <th>Typ</th>
                                    <th>Dos</th>
                                    <th>Schema</th>
                                    <th>Vid behov</th>
                                    <th colspan="2">Ordineringsdatum</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-95">
                                <tr v-for="(medicin, index) in medicines" :key="index"
                                    class="*:p-3 *:text-left first:*:pl-4 *:transition-all *:duration-100 *:bg-neutral-99 *:odd:bg-white">
                                    <td>
                                        <div class="flex items-center justify-between">
                                            <span>{{ medicin.name }}</span>
                                            <IconWarning v-if="index !== 1" class="text-culture"/>
                                        </div>
                                    </td>
                                    <td>
                                        {{ medicin.type }}
                                    </td>
                                    <td>
                                        <div class="relative inline-block">
                                            <select
                                                class="text-xs p-1 pr-5 m-0 border-0 bg-none bg-transparent rounded hover:bg-healthcare/10 w-full">
                                                <option value="">{{ medicin.dose }}</option>
                                            </select>
                                            <IconChevronDown
                                                class="size-5 absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none"/>
                                        </div>
                                    </td>
                                    <td>
                                        {{ medicin.schedule }}
                                    </td>
                                    <td>
                                        <input v-model="medicin.whenNecessary"
                                               class="text-xs p-1 rounded border-neutral-70 w-7 text-center [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                               placeholder="-"
                                               type="number">
                                    </td>
                                    <td>
                                        {{ medicin.prescribed }}
                                    </td>
                                    <td class="text-right w-8">
                                        <button
                                            class="text-culture hover:bg-culture-90 rounded-full text-xl size-8 -my-1 block ml-auto"
                                            type="button">
                                            <span class="block pb-1">&times;</span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 bg-culture-90 text-culture font-bold" colspan="7">
                                        <div class="flex items-center gap-2">
                                            <IconWarning class="size-4"/>
                                            <span>Varning! Naproxen och Ipren bör ej kombineras!</span>
                                        </div>
                                        <div class="mt-3 font-normal text-black">
                                            Läs mer på:
                                            <a class="text-healthcare underline"
                                               href="https://janusmed.se/interaktioner"
                                               target="_blank">https://janusmed.se/interaktioner</a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex gap-3 mt-3">
                            <Btn @click="openMedicineModal">
                                <IconAdd class="size-5 -mx-1 shrink-0"/>
                                <span>Lägg till läkemedel</span>
                            </Btn>
                        </div>
                    </div>
                </SlidePanel>
                <div class="flex flex-wrap gap-x-8 gap-y-5">
                    <Checkbox v-model:checked="prescriptionRenewal" class="shrink-0 h-10">
                        Receptförnyelse
                    </Checkbox>
                </div>
            </div>
            <div class="flex-1"></div>
            <div class="mt-4 flex justify-end">
                <Btn large primary>
                    <span>Granska och skicka meddelande</span>
                </Btn>
            </div>
        </div>

        <Teleport to="body">
            <Modal :show="showMedicineModal"
                   class="w-[60rem] max-w-full max-h-full left-1/2 top-1/3 -translate-x-1/2 -translate-y-1/2"
                   @close="closeMedicineModal"
            >
                <button class="absolute top-2 right-2 block size-8 hover:bg-healthcare/10 rounded" type="button"
                        @click="closeMedicineModal">
                    <span class="text-xl block pb-1">&times;</span>
                </button>

                <div class="flex items-start gap-8 text-sm">
                    <div class="space-y-2">
                        <div>Läkemedelsnamn</div>
                        <input class="bg-white border-neutral-70 rounded-md text-sm w-80"
                               placeholder="Sök på läkemedel..."
                               type="text"/>
                    </div>
                    <div class="space-y-2">
                        <div>Dosering</div>
                        <select class="bg-white border-neutral-70 rounded-md text-sm">
                            <option value=""></option>
                            <option value="foo">1x1</option>
                            <option value="foo">1x2</option>
                            <option value="foo">1x3</option>
                            <option value="foo">1+0+0+1</option>
                            <option value="foo">1+1+1+1</option>
                            <option value="foo">etc...</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <div>Schema</div>
                        <select class="bg-white border-neutral-70 rounded-md text-sm">
                            <option value=""></option>
                            <option value="foo">Dag</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <div>Vid behov</div>
                        <select class="bg-white border-neutral-70 rounded-md text-sm">
                            <option value=""></option>
                            <option value="foo">Dag</option>
                        </select>
                    </div>
                </div>

                <div class="border-2 border-healthcare rounded-xl p-3 mt-4 relative text-sm">
                    <div class="text-base font-bold text-healthcare">Ibuprofen</div>
                    <div class="text-neutral-50">
                        Filmdragerad tablett 400 mg<br>
                        (vit, oval, filmdragerad, 8×15 mm)<br>
                        <br>
                        Icke-steroida antiinflammatoriska/antireumatiska medel, NSAID
                    </div>
                    <div class="flex items-start gap-16 mt-6">
                        <div>
                            Aktiv substans:<br>
                            <ul class="list-disc pl-5">
                                <li>
                                    <a class="underline" href="#">Ibuprofen</a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            ATC-kod:<br>
                            <a class="underline" href="#">M01AE0</a>
                        </div>
                        <div>
                            Utbytbarhet:<br>
                            <a class="underline" href="#">Utbytbara läkemedel</a>
                        </div>
                    </div>

                    <Btn class="absolute bottom-3 right-3">Lägg till</Btn>
                </div>
            </Modal>
        </Teleport>
    </div>
</template>
