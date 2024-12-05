<script setup>

function fill(p, thickness = 0.1, padding = 0) {
    const sa = Math.PI + Math.PI / 4;
    const ea = sa - (Math.PI * 1.5) * p;
    const or = 1 - padding * 2;
    const ir = or - thickness;
    const ht = thickness / 2;

    let result = 'M' + Math.cos(sa) * or + ',' + -Math.sin(sa) * or;

    if (p > 0.5) {
        result += ' A ' + or + ' ' + or + ' 0 0 1 0 ' + -Math.sin(Math.PI / 2) * or;
    }
    result += ' A ' + or + ' ' + or + ' 0 0 1 ' + Math.cos(ea) * or + ' ' + -Math.sin(ea) * or;

    result += ' A ' + ht + ' ' + ht + ' 0 0 1 ' + Math.cos(ea) * ir + ',' + -Math.sin(ea) * ir;

    if (p > 0.5) {
        result += ' A ' + ir + ' ' + ir + ' 0 0 0 0 ' + -Math.sin(Math.PI / 2) * ir;
    }
    result += ' A ' + ir + ' ' + ir + ' 0 0 0 ' + Math.cos(sa) * ir + ' ' + -Math.sin(sa) * ir;

    result += ' A ' + ht + ' ' + ht + ' 0 0 1 ' + Math.cos(sa) * or + ',' + -Math.sin(sa) * or;

    return result;
}
</script>

<template>
    <svg viewBox="-1 -1 2 2">
        <path :d="fill(1, 0.15, 0.01)" class="fill-neutral-50 stroke-[0.03] stroke-neutral-50"/>
        <path :d="fill(0.75, 0.15, 0.01)" class="fill-neutral-80"/>
        <path :d="fill(0.5, 0.15, 0.01)" class="fill-green-50"/>
    </svg>
</template>
