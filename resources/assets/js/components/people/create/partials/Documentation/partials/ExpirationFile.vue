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
        z-index: 100;
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
            <div :class="'col-' + inputfilecol">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" :id="name" lang="es" @change="fileUploaded">
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
            <div :class="'col-' + input_date_col + ' text-center'">
                <input type="date" :name="name + '_expiration'" class="form-control" title="Vencimiento" @input="e => updated('expiration', e.target.value)">
            </div>
            <div v-if="checked !== null" class="col-1 d-flex align-items-center">
                <switch-box title="¿Requerido?" @update="(value) => updated('required', value)" />
            </div>
        </form-item>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            required: false,
            default: null
        },
        name: {
            type: String,
            required: true
        },
        checked: {
            type: Boolean,
            required: false,
            default: null
        },
        inputfilecol: {
            type:  Number,
            required: false,
            default: 8
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
            },
            switch: this.checked
        }
    },
    computed: {
        input_date_col: function() {
            let checkbox_col = this.checked === null? 0 : 1;
            return 12 - this.inputfilecol - checkbox_col;
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
        updated: function(which, value) {
            this.$emit('updated', {name: this.name + '_' + which, value: value});
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

