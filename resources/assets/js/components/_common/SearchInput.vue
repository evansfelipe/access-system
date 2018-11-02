<style lang="scss" scoped>
    button {
        border: 0;
        outline: none;
        background-color: transparent;
        cursor: pointer;
    }

    input {
        padding: 0 5px;
        border: 0;
        border-left: 1px solid rgb(222,222,222);
        outline: none;
        background-color: transparent;
        width: calc(95% - 25px);
    }
</style>

<template>
    <div class="d-inline-block grey-border p-1">
        <button v-if="updating">
            <i class="fas fa-circle-notch fa-spin fa-fw"></i>
        </button>
        <!-- Toggle Button -->
        <button v-else @click="opened = !opened">
            <i :class="`fas fa-${opened ? 'times' : 'search'} fa-fw`"></i>
        </button>
        <!-- Input -->
        <input v-if="opened" ref="input" type="text" :placeholder="placeholder" v-model="value" @keyup="keyup" @keydown="keydown" @keyup.enter="enter">
    </div>
</template>

<script>
export default {
    props: {
        updating: {
            type: Boolean,
            required: false,
            default: false
        },
        placeholder: {
            type: String,
            required: false,
            default: 'Ingrese el valor a buscar...'
        }
    },
    data() {
        return {
            interval_id: null,
            value: '',
            opened: false,
        };
    },
    methods: {
        /**
         * On any keyup, it restarts the timeout to emit the input value.
         */
        keyup: function() {
            clearTimeout(this.interval_id);
            this.interval_id = setTimeout(this.input, 1000);
        },
        /**
         * On any keydown, it clears the timeout to emit the input value.
         */
        keydown: function() {
            clearTimeout(this.interval_id);
        },
        /**
         * 
         */
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
    watch: {
        /**
         * When the search is opened, focus the input so the user can start typing.
         * When the search is closed and the value of the input is not empty, clears the input
         * and emits the (empty) value.
         */
        opened: function(opened) {
            if(opened) {
                Vue.nextTick(() => this.$refs['input'].focus());
            }
            else if(!opened && this.value !== '') {
                this.value = '';
                this.input();
            }
        },
    }
}
</script>
