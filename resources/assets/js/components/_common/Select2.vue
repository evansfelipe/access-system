<style lang="scss" scoped>
</style>


<template>
    <el-select  style="width: 100%" 
                v-model="input_value"   :disabled="disabled || loading"
                :name="name"            :placeholder="!loading ? placeholder : 'Cargando valores...'"
                :multiple="multiple"    :allow-create="tags"
                filterable              default-first-option
                clearable               :size="size"
                :loading="loading"      loading-text="Cargando..." auto-complete="nope"
    >
        <el-option
            v-for="(item, key) in options"
            :key="key"
            :value="item.id"
            :label="item.text"
        >
        </el-option>
    </el-select>
</template>

<script>
import Select2 from 'select2';

export default {
    props: {
        disabled: {
            type: Boolean,
            required: false,
            default: false
        },
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
        },
        size: {
            type: String,
            required: false,
            default: ''
        },
        loading: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    data() {
        return {
            input_value: this.value
        };
    },
    watch: {
        value: function() {
            this.input_value = this.value;
        },
        input_value: function() {
            this.$emit('input', this.input_value);            
        }
    }
}
</script>

