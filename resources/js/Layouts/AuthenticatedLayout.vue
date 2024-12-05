<script setup>
import Sidebar from "@/Components/Sidebar.vue";
</script>

<template>
    <div class="min-h-screen bg-healthcare-99 pl-60 print:pl-0 text-neutral-1">
        <Sidebar/>

        <!-- Page Heading -->
        <header
            class="empty:hidden bg-healthcare-99 border-b border-healthcare-90 px-10 py-6 print:p-0 print:pb-4 print:mb-4 relative"
            data-slot="header"/>

        <!-- Page Content -->
        <main class="px-10 py-6 print:p-0 relative">
            <slot/>
        </main>
    </div>
</template>
