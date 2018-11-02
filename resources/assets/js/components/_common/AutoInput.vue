<template>
    <input type="text" :placeholder="placeholder" v-model="value" @keyup="keyup" @keydown="keydown" @keyup.enter="enter">
</template>

<script>
export default {
    props: {
        initialValue: {
            type: String,
            required: false,
            default: ''
        },
        placeholder: {
            type: String,
            required: false,
            default: ''
        },
        delay: {
            type: Number,
            required: false,
            default: 1000
        }
    },
    data() {
        return {
            interval_id: null,
            value: this.initialValue,
        };
    },
    methods: {
        /**
         * On any keyup, it restarts the timeout to emit the input value.
         */
        keyup: function() {
            clearTimeout(this.interval_id);
            this.interval_id = setTimeout(this.input, this.delay);
        },
        /**
         * On any keydown, it clears the timeout to emit the input value.
         */
        keydown: function() {
            clearTimeout(this.interval_id);
        },
        enter: function() {
            clearTimeout(this.interval_id);
            this.input();
        },
        /**
         * Emits an event to the parent with the input's value.
         */
        input: function() {
            this.$emit('input', this.value);
        }
    },
}
</script>
