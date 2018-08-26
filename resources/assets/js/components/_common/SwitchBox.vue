<style lang="scss" scoped>
    div.checkbox {
        background-color: rgba(63, 114, 155, 0.7);
        position: absolute;
        height: 1.5em;
        width: 2.5em;
        border-radius: 0.75em;
        transition: all 0.25s;
        cursor: pointer;
        &.active {
            background-color: #3F729B;
        }
        & > .check {
            margin: 0.25em;
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
    <div :class="'checkbox' + (this.switch? ' active':'')" @click="toggle()">
        <div class="check" :style="this.switch? 'left:1em': 'left:0'"></div>
    </div>
</template>

<script>
export default {
    props: {
        initial: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    data() {
        return {
            switch: this.initial,
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

