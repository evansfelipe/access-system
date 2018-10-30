<template>
    <el-date-picker
        type="date"
        v-model="internal_value"
        :placeholder="this.placeholder"
        format="dd / MM / yyyy"
        value-format="yyyy-MM-dd"
        style="width: 100%"
        :size="size"
    >
    </el-date-picker>
</template>

<script>
export default {
    props: {
        value: {
            required: false,
            default:  ''
        },
        size: {
            required: false,
            default:  ''
        },
        placeholder: {
            type: String,
            required: false,
            default: 'dd / mm / aaaa'
        }
    },
    data() {
        return {
            internal_value: this.value,
        };
    },
    watch: {
        value: function() {
            this.internal_value = this.value;
        },
        internal_value: function() {
            this.$emit('input', this.internal_value);
        }
    }
}
</script>
