<template>
    <span :title="text.length > length ? text : ''">{{ text.length > length ? text.substring(0, length - 1) + '...': text }}</span>
</template>

<script>
export default {
    props: {
        text: {
            required: true,
            type: String
        },
        length: {
            required: false,
            type: Number,
            default: 25
        }
    }
}
</script>
