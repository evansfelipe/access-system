<template>
    <el-select  :style="`width: ${width}`" 
                :value="value"     :disabled="disabled"
                :name="name"            :placeholder="placeholder"
                :multiple="multiple"    :allow-create="tags"
                filterable              default-first-option
                :clearable="clearable"  :size="size"
                :loading="loading"      loading-text="Cargando..."
                auto-complete="nope"    :filter-method="filterMethod"
                @change="value => this.$emit('input', value)"

                :remote-method="remoteMethod" :remote="remoteMethod !== null"
    >
        <el-option
            v-for="item in filtered_options"
            :key="item.id"
            :value="item.id"
            :label="item.text"
        >
        </el-option>
    </el-select>
</template>

<script>
export default {
    props: {
        disabled: {
            type:       Boolean,
            required:   false,
            default:    false
        },
        options: {
            type:       Array,
            required:   false,
            default:    () => []
        },
        value: {
            required:   false,
            default:    ''
        },
        placeholder: {
            type:       String,
            required:   false,
            default:    'Seleccione una opción'
        },
        tags: {
            type:       Boolean,
            required:   false,
            default:    false
        },
        name: {
            type:       String,
            required:   false,
            default:    ''
        },
        multiple: {
            type:       Boolean,
            required:   false,
            default:    false
        },
        size: {
            type:       String,
            required:   false,
            default:    ''
        },
        loading: {
            type:       Boolean,
            required:   false,
            default:    false
        },
        clearable: {
            type:       Boolean,
            required:   false,
            default:    true
        },
        width: {
            type:       String,
            required:   false,
            default:    '100%'
        },
        remoteMethod: {
            type: Function,
            required: false,
            default: null
        }
    },
    data() {
        return {
            filtered_options: this.options
        };
    },
    watch: {
        options: function() {
            this.filtered_options = this.options;
        }
    },
    methods: {
        filterMethod: function(value) {
            this.filtered_options = this.options.filter(option => option.text.matches(value));
        }
    }
}
</script>

