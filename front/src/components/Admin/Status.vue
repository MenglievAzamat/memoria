<template>
    <div
        class="rounded flex justify-center items-center p-2 h-[2.9375rem] text-black"
        :class="{
            'bg-[#8E8E8E]' : status.name === 'not-filled',
            'bg-[#E3B661]' : status.name === 'filled',
            'bg-[#FFE8BD]' : status.name === 'review',
        }"
    >
        {{ status.display_name }}
    </div>
</template>

<script>
export default {
    name: "Status",

    props: {
        status: {
            type: Object,
            required: true,
        }
    }
}
</script>