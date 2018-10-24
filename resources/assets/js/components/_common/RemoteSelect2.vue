<template>
    <div>
        <select2 :value="value"
                 :loading="loading"
                 :options="options"
                 :remote-method="remote"
                 :multiple="multiple"
                 @input="input" :size="size" :placeholder="placeholder"/> 
    </div>
</template>

<script>
export default {
    props: {
        size: {
            type: String,
            required: false,
            default: ''
        },
        multiple: {
            type: Boolean,
            required: false,
            default: false
        },
        value: {
            type: Number | String | Array,
            required: true
        },
        fixedParams: {
            type: Object,
            required: false,
            default: null
        },
        path: {
            type: String,
            required: true
        },
        placeholder: {
            type: String,
            required: false,
            default: ''
        }
    },
    data() {
        return {
            loading: false,
            options: []
        };
    },
    mounted() {
        if(this.fixedParams !== null) {
            this.get(this.fixedParams);
        }
    },
    methods: {
        remote: function(filter) {
            filter = filter.trim();
            if(filter !== '') {
                this.get({filter, ...this.fixedParams});
            }
            else {
                this.options = [];
            }
        },
        get: function(params) {
            this.loading = true;
            axios.get(this.path, {params})
                 .then(response => this.options = response.data)
                 .catch(error => this.options = [])
                 .finally(() => this.loading = false);
        },
        input: function(value) {
            this.$emit('input', value);
        }
    }
}
</script>

