<style lang="scss" scoped>
    .badge {
        font-size: 100% !important;
        font-weight: normal;
        border: 1px solid #3F729B;
        color: #3F729B;
        & > div > i:hover {
            color: rgb(160, 0, 0);
            cursor: pointer;
        }
    }

    // Without the z-index the label is under the input
    label.custom-file-label {
        z-index: 1000;
    }

    .no-expiration {
        color: grey;
        font-style: italic;
        font-size: 75%;
    }
</style>

<template>
    <div class="form-row">
        <form-item col="col" :label="label" :errors="errors">
            <!-- File uploader -->
            <div class="col-8">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" :id="name" lang="es"  @change="fileUploaded">
                    <label class="custom-file-label" :for="name">
                        <!-- Shows file name when it's selected -->
                        <span v-if="file.selected" class="badge" @click="e => e.preventDefault()">
                            <div @click="deleteFile" class="d-inline"><i class="fas fa-times fa-sm"></i></div>
                            <abbreviation-text :text="file.name" :length="25"/>
                        </span>
                        <!-- Default message instead -->
                        <span v-else>Seleccionar Archivo</span>                        
                    </label>
                </div>
            </div>
            <!-- Expiration date -->
            <div class="col-4 text-center">
                <input v-if="expiration" type="date" :name="name + '_expiration'" class="form-control" @input="e => updated(e.target.value)">
                <span v-else class="no-expiration">Sin vencimiento</span>
            </div>
        </form-item>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            required: true
        },
        name: {
            type: String,
            required: true
        },
        expiration: {
            type: Boolean,
            required: false,
            default: false
        },
        errors: {
            type: Array,
            required: false,
            default: () => []
        }
    },
    data() {
        return {
            file: {
                selected: false,
                name: '',
            }
        }
    },
    methods: {
        fileUploaded: function(e) {
            if(e.target.files.length > 0) {
                this.file.selected = true;
                this.file.name = e.target.files[0].name;
                this.$emit('updated', {name: this.name, value: e.target.files[0]});
            }
        },
        updated: function(value) {
                this.$emit('updated', {name: this.name + '_expiration', value: value});
        },
        deleteFile: function(e) {
            e.preventDefault();
            this.file = {
                selected: false,
                name: '',
            }
            this.$emit('fileUploaded', null);
        }
    }
}
</script>

