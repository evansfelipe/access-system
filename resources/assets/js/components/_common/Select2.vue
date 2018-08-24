<style lang="scss" scoped>

</style>

<template>
    <select :name="`${name}${multiple ? '[]' : ''}`" :multiple="multiple" class="form-control d-none">
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
        },
        name: {
            type: String,
            required: false,
            default: ''
        },
        multiple: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    computed: {
        select2Options: function() {
            return {  
                data: this.options,
                placeholder: this.placeholder,
                tags: this.tags,
                color: 'red',
                theme: 'bootstrap',
                "language": {
                    "noResults": function(){
                        return "No hay resultados";
                    }
                },
            };
        }
    },
    mounted() {
        let vm = this;
        let select = $(this.$el)
            .select2(this.select2Options)
            .val(this.value)
            .trigger('change');
        select.data('select2').$selection.css({
            'min-height': '37px',
        });
        select.on('change', function() {
            // As we need this to refer to select element, we can't use an arrow function
            // and we need to use the vm variable to refer the component's this.
            if(this.multiple) {
                vm.$emit('input', $(vm.$el).select2('data').map(el => el.id));
            }
            else {
                vm.$emit('input', this.value);
            }
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
            // Maybe there is a way to change the options without restarting the select?
            $(this.$el)
                .select2(this.select2Options)
                // .val(this.value) It's needed?
                .data('select2').$selection.css({
                    'min-height': '37px',
                });
        }
    },
}
</script>

