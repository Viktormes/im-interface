<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {Link} from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-[#F7FAFB] flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div
            class="bg-healthcare-90 absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 w-[125vw] h-[80vh] z-10 -rotate-6"></div>

        <div class="grow"></div>

        <div
            class="w-full sm:max-w-md px-12 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg relative z-20"
        >
            <slot/>
        </div>

        <div class="grow">
            <div class="mt-8 relative z-20">
                <Link href="/">
                    <ApplicationLogo class="size-16 fill-current text-gray-500"/>
                </Link>
            </div>
        </div>
    </div>
</template>
