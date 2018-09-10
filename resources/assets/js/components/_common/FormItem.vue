<style>
    .has-errors {
        text-align: justify;
        color: red;
        font-size: 85%;
    }
    .error-message {
        display: block;
    }
    .error-message:first-of-type {
        margin-top: 5px;
    }
    .item-wrapper input, .item-wrapper select, .item-wrapper span, .item-wrapper label {
        border-color: red !important;
    }
</style>

<template>
    <div :class="col">
        <div class="form-group">
            <div v-if="label" class="form-row">
                <div class="col">
                    <label>{{ label }}:</label>
                </div>
            </div>
            <div :class="`form-row ${errors.length ? 'item-wrapper' : ''}`">
                <slot></slot>
            </div>
            <div class="form-row">
                <div class="col">
                    <div v-if="errors.length" class="has-errors">
                        <strong class="error-message" v-for="(error, key) in errors" :key="key">{{ error }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        col: {
            type:     String,
            required: false,
            default:  'col'
        },
        label: {
            type:     String,
            required: false,
            default:  null
        },
        errors: {
            type:     Array,
            required: false,
            default:  () => []
        }
    }
}
</script>
