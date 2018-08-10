<style lang="scss" scoped>

</style>

<template>
    <select style="width: 100%; max-height: 50px !important">
        <slot/>
    </select>
</template>

<script>
import Select2 from 'select2';

export default {
    props: {
        options: {
            type: Array,
            required: false,
            default: () => []
        },
        value: {
            required: false,
            default: 0
        },
        placeholder: {
            type: String,
            required: false,
            default: 'Seleccione una opción'
        },
        tags: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    mounted() {
        let vm = this;
        let select = $(this.$el)
            .select2({ data: this.options, placeholder: this.placeholder, tags: this.tags, color: 'red', theme: 'bootstrap' })
            .val(this.value)
            .trigger('change')
            .on('change', function() {
                // As we need this to refer to select element, we can't use an arrow function
                // and we need to use the vm variable to refer the component's this.
                vm.$emit('input', this.value);
            });
        select.data('select2').$selection.css({
            'height': '37px',
        });
    },
    destroyed() {
        $(this.$el).off().select2('destroy');
    },
    watch: {
        // For outside component changes
        // value: function(value) {
        //     $(this.$el).val(value);
        // },
        options: function(options) {
            $(this.$el)
                .empty()
                .select2({ data: options, placeholder: this.placeholder })
                .val(this.value)
                .trigger('change');
        }
    },
}
</script>

