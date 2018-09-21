<style lang="scss" scoped>
    div.wrapper {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }
    div.checkbox {
        background-color: rgba(63, 114, 155, 0.7);
        width: 2.5em;
        padding: 0.25em;
        border-radius: 0.75em;
        transition: all 0.25s;
        &.active {
            background-color: #3F729B;
        }
        & > .check {
            position: relative;
            background-color: white;
            height: 1em;
            width: 1em;
            border-radius: 0.5em;
            transition: all 0.25s;
        }
    }
</style>

<template>
    <div class="mb-1 wrapper" @click="toggle">
        <div :class="'checkbox' + (this.switch? ' active':'')">
            <div class="check" :style="this.switch? 'left:1em': 'left:0'"></div>
        </div>
        <span class="ml-2">
            {{ label }}
        </span>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: Boolean,
            required: false,
            default: false
        },
        label: {
            type: String,
            required: false,
            default: ''
        }
    },
    data() {
        return {
            switch: this.value,
        }
    },
    methods: {
        toggle: function() {
            this.switch = !this.switch;
            this.$emit('update', this.switch);
        }
    }
}
</script>

