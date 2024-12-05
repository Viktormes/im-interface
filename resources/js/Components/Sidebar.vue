<script setup>
import IconAccountBox from "@/Icons/IconAccountBox.vue";
import IconDashboard from "@/Icons/IconDashboard.vue";
import IconStatistics from "@/Icons/IconStatistics.vue";
import IconFormatListNumbered from "@/Icons/IconFormatListNumbered.vue";
import IconTreatment from "@/Icons/IconTreatment.vue";
import IconSettings from "@/Icons/IconSettings.vue";
import {Link, usePage} from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {usePresenter} from "@/Composables/usePresenter.js";
import {computed} from "vue";

const present = usePresenter();
const patient = computed(() => usePage().props.patient);
</script>

<template>
    <nav class="fixed left-0 inset-y-0 bg-white w-60 px-4 py-8 print:hidden">
        <div class="flex flex-col h-full">
            <ApplicationLogo class="mx-auto mt-4 mb-20"/>

            <ul class="leading-tight">
                <li>
                    <NavLink :active="$page.url === '/patients'" :icon="IconAccountBox" href="/patients">Alla
                        Patienter
                    </NavLink>
                </li>
                <template v-if="$page.props.patient">
                    <li class="pl-2 mt-8 pt-8 font-bold text-sm border-t border-healthcare-70">
                        <div class="flex items-start justify-between gap-4">
                            <span>{{ present(patient).name }}</span>
                            <Link as="button"
                                  class="size-6 shrink-0 -m-0.5 rounded-full bg-neutral-90 hover:bg-neutral-80 text-neutral-40 hover:text-neutral-20 select-none"
                                  href="/patients/select"
                                  method="delete"
                                  type="button">
                                &times;
                            </Link>
                        </div>
                    </li>
                    <li class="mt-8">
                        <NavLink :active="$page.url === '/dashboard'" :disabled="!$page.props.patient"
                                 :icon="IconDashboard"
                                 href="/dashboard">
                            Aktuell status
                        </NavLink>
                    </li>
                    <li class="mt-6">
                        <NavLink :active="$page.url.startsWith('/overview')" :disabled="!$page.props.patient"
                                 :icon="IconStatistics" href="/overview">
                            Översikt mätvärden
                        </NavLink>
                    </li>
                    <li class="mt-6">
                        <NavLink :active="$page.url.startsWith('/measurements')" :disabled="!$page.props.patient"
                                 :icon="IconFormatListNumbered" href="/measurements">
                            Mätvärden &amp; skattningar
                        </NavLink>
                    </li>
                    <li class="mt-6">
                        <NavLink :active="$page.url === '/treatment'" :disabled="!$page.props.patient"
                                 :icon="IconTreatment" href="/treatment">
                            Behandling &amp; åtgärder
                        </NavLink>
                    </li>
                </template>
            </ul>

            <div class="flex-1"></div>

            <ul class="space-y-10 leading-tight">
                <li>
                    <NavLink :icon="IconSettings" as="button" href="/logout" method="post">Logga ut</NavLink>
                </li>
            </ul>
        </div>

        <div class="absolute left-full inset-y-0 w-5 bg-gradient-to-r from-healthcare-95 z-[-1]"></div>
    </nav>
</template>
