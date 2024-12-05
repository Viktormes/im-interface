<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import LayoutSlot from "@/Components/LayoutSlot.vue";
import HeaderPatient from "@/Components/HeaderPatient.vue";
import IconWarning from "@/Icons/IconWarning.vue";
import IconTrendUp from "@/Icons/IconTrendUp.vue";
import IconTrendNeutral from "@/Icons/IconTrendNeutral.vue";
import IconCheckCircle from "@/Icons/IconCheckCircle.vue";
import IconTrendDown from "@/Icons/IconTrendDown.vue";
import IconExclamation from "@/Icons/IconExclamation.vue";
import IconStatistics from "@/Icons/IconStatistics.vue";
import IconFormatListNumbered from "@/Icons/IconFormatListNumbered.vue";
import IconInformation from "@/Icons/IconInformation.vue";
import DashboardMetric from "@/Components/DashboardMetric.vue";

defineOptions({
    layout: AuthenticatedLayout,
});

defineProps({
    period: Object,
    charts: Array,
});
</script>

<template>
    <Head title="Översikt"/>

    <LayoutSlot v-if="$page.props.patient" slot="header">
        <HeaderPatient :patient="$page.props.patient"/>
    </LayoutSlot>

    <div
        class="max-w-[1600px] mx-auto grid grid-cols-[1.5fr,1.5fr,2fr] xl:grid-cols-[1fr,1fr,1fr] 2xl:grid-cols-[1.5fr,1.5fr,1fr] grid-rows-[auto,1fr] grid-flow-dense gap-x-12 gap-y-16">
        <div class="col-span-3 xl:col-span-2">
            <h1 class="text-xl font-medium font-heading mb-3">Observationer som indikerar på försämring</h1>

            <div class="grid gap-x-12 gap-y-12 grid-cols-3 2xl:grid-cols-4">
                <DashboardMetric change="+2.3" label="BASDAI" trend="up" type="danger" unit="poäng" value="6.9"/>

                <DashboardMetric class="peer" label="Sömn" note="Genomsnitt per dag senaste 7 dagar" trend="down"
                                 type="warning" value="5h 22m">
                    <div
                        class="absolute z-10 top-full left-1/2 -translate-x-1/2 -mt-2 bg-white rounded-lg px-4 py-6 border border-neutral-70 shadow-xl hidden hover:block peer-hover:block">
                        <div class="flex">
                            <div
                                class="flex flex-col justify-between items-end border-r border-neutral-80 pr-2">
                                <span class="text-xs -translate-y-1/2">7</span>
                                <span class="text-xs">6</span>
                                <span class="text-xs translate-y-1/2">5</span>
                            </div>
                            <div class="border-b border-neutral-80 px-2">
                                <svg class="my-2" fill="none" height="63" viewBox="0 0 198 63"
                                     width="198"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3.0769 10.3415C15.7846 14.1561 22.1384 22.6829 34.8461 22.6829C47.5538 22.6829 53.9077 14.1561 66.6154 10.3415C79.3231 6.52683 85.6769 3.60976 98.3846 3.60976C111.092 3.60976 117.446 58.5854 130.154 58.5854C142.862 58.5854 149.215 50.7317 161.923 50.7317C174.631 50.7317 180.985 54.0976 193.692 54.0976"
                                        stroke="#5B97F0"/>
                                    <path
                                        d="M4 14C2.93931 14 1.92204 13.5787 1.17192 12.8288C0.421808 12.0789 0.000265298 11.0617 1.25181e-07 10.001C-0.000265048 8.94031 0.420772 7.92294 1.17051 7.17263C1.92025 6.42233 2.93731 6.00053 3.998 6C5.05869 5.99947 6.07617 6.42025 6.82666 7.16981C7.57715 7.91936 7.9992 8.93631 8 9.997C8.00079 11.0577 7.58027 12.0753 6.8309 12.826C6.08153 13.5766 5.06469 13.9989 4.004 14L4 14Z"
                                        fill="#5B97F0"/>
                                    <path
                                        d="M35 26C33.9393 26 32.922 25.5787 32.1719 24.8288C31.4218 24.0789 31.0003 23.0617 31 22.001C30.9997 20.9403 31.4208 19.9229 32.1705 19.1726C32.9203 18.4223 33.9373 18.0005 34.998 18C36.0587 17.9995 37.0762 18.4203 37.8267 19.1698C38.5772 19.9194 38.9992 20.9363 39 21.997C39.0008 23.0577 38.5803 24.0753 37.8309 24.826C37.0815 25.5766 36.0647 25.9989 35.004 26H35Z"
                                        fill="#5B97F0"/>
                                    <path
                                        d="M65 14C63.9393 14 62.922 13.5787 62.1719 12.8288C61.4218 12.0789 61.0003 11.0617 61 10.001C60.9997 8.94031 61.4208 7.92294 62.1705 7.17263C62.9203 6.42233 63.9373 6.00053 64.998 6C66.0587 5.99947 67.0762 6.42025 67.8267 7.16981C68.5772 7.91936 68.9992 8.93631 69 9.997C69.0008 11.0577 68.5803 12.0753 67.8309 12.826C67.0815 13.5766 66.0647 13.9989 65.004 14L65 14Z"
                                        fill="#5B97F0"/>
                                    <path
                                        d="M98 8C96.9393 8 95.922 7.57871 95.1719 6.82878C94.4218 6.07885 94.0003 5.06169 94 4.001C93.9997 2.94031 94.4208 1.92294 95.1705 1.17263C95.9203 0.42233 96.9373 0.000530847 97.998 5.00722e-07C99.0587 -0.000529846 100.076 0.420252 100.827 1.16981C101.577 1.91936 101.999 2.93631 102 3.997C102.001 5.05769 101.58 6.07527 100.831 6.82595C100.082 7.57663 99.0647 7.99894 98.004 8L98 8Z"
                                        fill="#5B97F0"/>
                                    <path
                                        d="M130 63C128.939 63 127.922 62.5787 127.172 61.8288C126.422 61.0789 126 60.0617 126 59.001C126 57.9403 126.421 56.9229 127.171 56.1726C127.92 55.4223 128.937 55.0005 129.998 55C131.059 54.9995 132.076 55.4202 132.827 56.1698C133.577 56.9194 133.999 57.9363 134 58.997C134.001 60.0577 133.58 61.0753 132.831 61.826C132.082 62.5766 131.065 62.9989 130.004 63H130Z"
                                        fill="#5B97F0"/>
                                    <path
                                        d="M161 55C159.939 55 158.922 54.5787 158.172 53.8288C157.422 53.0789 157 52.0617 157 51.001C157 49.9403 157.421 48.9229 158.171 48.1726C158.92 47.4223 159.937 47.0005 160.998 47C162.059 46.9995 163.076 47.4202 163.827 48.1698C164.577 48.9194 164.999 49.9363 165 50.997C165.001 52.0577 164.58 53.0753 163.831 53.826C163.082 54.5766 162.065 54.9989 161.004 55H161Z"
                                        fill="#5B97F0"/>
                                    <path
                                        d="M194 58C192.939 58 191.922 57.5787 191.172 56.8288C190.422 56.0789 190 55.0617 190 54.001C190 52.9403 190.421 51.9229 191.171 51.1726C191.92 50.4223 192.937 50.0005 193.998 50C195.059 49.9995 196.076 50.4202 196.827 51.1698C197.577 51.9194 197.999 52.9363 198 53.997C198.001 55.0577 197.58 56.0753 196.831 56.826C196.082 57.5766 195.065 57.9989 194.004 58H194Z"
                                        fill="#5B97F0"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </DashboardMetric>
            </div>
        </div>

        <div class="col-start-3 row-span-5">
            <h1 class="text-xl font-medium font-heading mb-3">Händelseförlopp</h1>

            <div class="bg-white rounded-lg shadow-md border border-neutral-80">
                <div class="flex items-center justify-between">
                    <ul class="divide-y divide-neutral-90 w-full text-sm">
                        <li class="py-2.5 px-2.5 flex items-start gap-4">
                            <IconStatistics class="size-5 m-1 text-healthcare-50 shrink-0"/>
                            <div class="space-y-1.5">
                                <div>Nya värden delade med vården</div>
                                <div class="text-xs text-neutral-60">2023-04-04 | 22.32</div>
                            </div>
                        </li>
                        <li class="py-2.5 px-2.5 flex items-start gap-4">
                            <IconInformation class="size-5 m-1 text-healthcare-50 shrink-0"/>
                            <div class="space-y-1.5">
                                <div>Patient uppdaterade <em>Upplever besvär av sjukdom</em></div>
                                <div class="text-xs text-neutral-60">2023-04-04 | 22.32</div>
                            </div>
                        </li>
                        <li class="py-2.5 px-2.5 flex items-start gap-4">
                            <IconInformation class="size-5 m-1 text-healthcare-50 shrink-0"/>
                            <div class="space-y-1.5">
                                <div>Patient uppdaterade <em>Nöjd med behandling</em></div>
                                <div class="text-xs text-neutral-60">2023-04-04 | 22.32</div>
                            </div>
                        </li>
                        <li class="py-2.5 px-2.5 flex items-start gap-4">
                            <IconStatistics class="size-5 m-1 text-healthcare-50 shrink-0"/>
                            <div class="space-y-1.5">
                                <div>Nya värden delade med vården</div>
                                <div class="text-xs text-neutral-60">2023-04-04 | 22.32</div>
                            </div>
                        </li>
                        <li class="py-2.5 px-2.5 flex items-start gap-4">
                            <IconFormatListNumbered class="size-5 m-1 text-healthcare-50 shrink-0"/>
                            <div class="space-y-1.5">
                                <div>Självskattning BASDAI</div>
                                <div class="text-xs text-neutral-60">2023-04-04 | 22.32</div>
                            </div>
                        </li>
                        <li class="py-2.5 px-2.5 flex items-start gap-4">
                            <IconInformation class="size-5 m-1 text-healthcare-50 shrink-0"/>
                            <div class="space-y-1.5">
                                <div>Patient uppdaterade <em>Upplever besvär av sjukdom</em></div>
                                <div class="text-xs text-neutral-60">2023-04-04 | 22.32</div>
                            </div>
                        </li>
                        <li class="py-2.5 px-2.5 flex items-start gap-4">
                            <IconStatistics class="size-5 m-1 text-healthcare-50 shrink-0"/>
                            <div class="space-y-1.5">
                                <div>Nya värden delade med vården</div>
                                <div class="text-xs text-neutral-60">2023-04-04 | 22.32</div>
                            </div>
                        </li>
                        <li class="py-2.5 px-2.5 flex items-start gap-4">
                            <IconStatistics class="size-5 m-1 text-healthcare-50 shrink-0"/>
                            <div class="space-y-1.5">
                                <div>Nya värden delade med vården</div>
                                <div class="text-xs text-neutral-60">2023-04-04 | 22.32</div>
                            </div>
                        </li>
                        <li class="py-2.5 px-2.5 flex items-start gap-4">
                            <IconFormatListNumbered class="size-5 m-1 text-healthcare-50 shrink-0"/>
                            <div class="space-y-1.5">
                                <div>Självskattning BASDAI</div>
                                <div class="text-xs text-neutral-60">2023-04-04 | 22.32</div>
                            </div>
                        </li>
                        <li class="py-2.5 px-2.5 flex items-start gap-4">
                            <IconStatistics class="size-5 m-1 text-healthcare-50 shrink-0"/>
                            <div class="space-y-1.5">
                                <div>Nya värden delade med vården</div>
                                <div class="text-xs text-neutral-60">2023-04-04 | 22.32</div>
                            </div>
                        </li>
                        <li>
                            <button
                                class="text-healthcare font-heading text-base w-full h-10 rounded-lg hover:bg-healthcare-95 select-none"
                                type="button">
                                Visa
                                mer
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-span-2 2xl:col-span-1">
            <h1 class="text-xl font-medium font-heading mb-3">Sjukdomsaktivitet</h1>

            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-neutral-80">
                <table class="w-full text-sm">
                    <tbody>
                    <tr class="*:h-14 *:px-2 first:*:pl-4 last:*:pr-4">
                        <td colspan="2">Upplever besvär av sjukdom</td>
                        <td>Ja</td>
                        <td>
                            <IconWarning class="size-5 text-culture ml-auto"/>
                        </td>
                    </tr>
                    <tr class="*:h-14 *:px-2 first:*:pl-4 last:*:pr-4 *:border-t *:border-neutral-90 *:hover:bg-healthcare-95 cursor-pointer group">
                        <td class="text-healthcare-40 group-hover:underline">
                            BASDAI
                        </td>
                        <td>
                            <IconTrendUp class="size-5 text-culture ml-auto"/>
                        </td>
                        <td>6.9 poäng</td>
                        <td>
                            <IconWarning class="size-5 text-culture ml-auto"/>
                        </td>
                    </tr>
                    <tr class="*:h-14 *:px-2 first:*:pl-4 last:*:pr-4 *:border-t *:border-neutral-90 *:hover:bg-healthcare-95 cursor-pointer group">
                        <td class="text-healthcare-40 group-hover:underline">
                            Antal steg
                        </td>
                        <td>
                            <IconTrendNeutral class="size-5 text-neutral-40 ml-auto"/>
                        </td>
                        <td class="py-2">
                            6143 steg per dag
                            <div class="text-xs text-neutral-60">Genomsnitt senaste veckan</div>
                        </td>
                        <td>
                            <IconCheckCircle class="size-5 text-green ml-auto"/>
                        </td>
                    </tr>
                    <tr class="*:h-14 *:px-2 first:*:pl-4 last:*:pr-4 *:border-t *:border-neutral-90 *:hover:bg-healthcare-95 cursor-pointer group">
                        <td class="text-healthcare-40 group-hover:underline">
                            Sömn
                        </td>
                        <td>
                            <IconTrendDown class="size-5 text-yellow ml-auto"/>
                        </td>
                        <td class="py-2">
                            5 h 22 min
                            <div class="text-xs text-neutral-60">Genomsnitt senaste veckan</div>
                        </td>
                        <td>
                            <IconExclamation class="size-5 text-yellow ml-auto"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-span-2 2xl:col-span-1">
            <h1 class="text-xl font-medium font-heading mb-3">Läkemedelsbehandling</h1>

            <div class="bg-white rounded-lg shadow-md border border-neutral-80">
                <table class="w-full text-sm">
                    <tbody>
                    <tr class="*:h-14 *:px-2 first:*:pl-4 last:*:pr-4">
                        <td>Nöjd med behandling?</td>
                        <td>Nej</td>
                        <td>
                            <IconWarning class="size-5 text-culture ml-auto"/>
                        </td>
                    </tr>
                    <tr class="*:h-14 *:px-2 first:*:pl-4 last:*:pr-4 *:border-t *:border-neutral-90">
                        <td>Följsamhet</td>
                        <td>Ja</td>
                        <td>
                            <IconCheckCircle class="size-5 text-green ml-auto"/>
                        </td>
                    </tr>
                    <tr class="*:h-14 *:px-2 first:*:pl-4 last:*:pr-4 *:border-t *:border-neutral-90">
                        <td>Biverkningar</td>
                        <td>Ja - Mår väldigt dåligt</td>
                        <td>
                            <IconWarning class="size-5 text-culture ml-auto"/>
                        </td>
                    </tr>
                    <tr class="*:h-14 *:px-2 first:*:pl-4 last:*:pr-4 *:border-t *:border-neutral-90">
                        <td>Blodprover</td>
                        <td class="py-2">
                            <div>Alat, Kreatinin, Hb LPK TP</div>
                            <div class="text-xs text-neutral-60">Saknade prover</div>
                        </td>
                        <td>
                            <IconExclamation class="size-5 text-yellow ml-auto"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-span-2 2xl:col-span-1">
            <h1 class="text-xl font-medium font-heading mb-3">Personliga mål</h1>

            <div class="bg-white rounded-lg shadow-md border border-neutral-80">
                <table class="w-full text-sm">
                    <tbody>
                    <tr class="*:h-14 *:px-2 first:*:pl-4 last:*:pr-4">
                        <td class="py-2">Kunna springa 5 km på under 25 minuter</td>
                        <td class="py-2">Ej uppnått</td>
                        <td>
                            <IconWarning class="size-5 text-culture ml-auto"/>
                        </td>
                    </tr>
                    <tr class="*:h-14 *:px-2 first:*:pl-4 last:*:pr-4 *:border-t *:border-neutral-90">
                        <td class="py-2">Daglig stretching</td>
                        <td class="py-2">Ja</td>
                        <td>
                            <IconCheckCircle class="size-5 text-green ml-auto"/>
                        </td>
                    </tr>
                    <tr class="*:h-14 *:px-2 first:*:pl-4 last:*:pr-4 *:border-t *:border-neutral-90">
                        <td class="py-2">Morgonpromenad</td>
                        <td class="py-2">Ja</td>
                        <td>
                            <IconCheckCircle class="size-5 text-green ml-auto"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
