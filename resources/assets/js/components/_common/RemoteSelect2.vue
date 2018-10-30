<template>
    <div>
        <select2 :value="value"
                 :loading="loading"
                 :options="options"
                 :remote-method="remote"
                 :multiple="multiple" :tags="tags"
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
        },
        tags: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    data() {
        return {
            loading: false,
            options: []
        };
    },
    mounted() {
        this.get(this.fixedParams);
    },
    methods: {
        remote: function(filter) {
            filter = filter.trim();
            if(filter.length >= 2) {
                this.get({filter, ...this.fixedParams});
            }
            else {
                this.get(this.fixedParams);
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

