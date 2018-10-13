<style lang="scss" scoped>
    label.custom-file-label, input.custom-file-input {
        height: 40px !important;
    }
    label.custom-file-label::after {
        height: 38px !important;
    }
    .badge {
        font-weight: normal;
        font-size: 80% !important;
        color: rgb(144, 145, 148);
        background-color: rgb(239,241,245);
        padding: 0.5em;
        & > div {
            display: inline-block;
            color: rgb(191,193,203);
            &:hover {
                color: rgb(143, 145, 152);
                cursor: pointer;
            }
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
                    <input type="file" class="custom-file-input" :id="name" lang="es" @change="fileChanged">
                    <label class="custom-file-label" :for="name">
                        <!-- Shows file name when it's selected -->
                        <span v-if="file.selected" class="badge" @click="e => e.preventDefault()">
                            <abbreviation-text :text="file.name" :length="25"/>
                            <div @click="deleteFile"><i class="el-icon-error"></i></div>
                        </span>
                        <!-- Default message instead -->
                        <span v-else>Seleccionar Archivo</span>                        
                    </label>
                </div>
            </div>
            <!-- Expiration date -->
            <div :class="'col-' + input_date_col + ' text-center'">
                <el-tooltip class="item" effect="dark" content="Vencimiento" placement="top">
                    <date-picker :name="`${name}_expiration`" :value="value.expiration" @input="value => expirationChanged(value)"/>
                </el-tooltip>
            </div>
        </form-item>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: Object,
            required: false,
            default: () => {
                return {
                    file: '',
                    expiration: ''
                }
            }
        },
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
            default: 7
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
    beforeMount() {
        // When you edit a person, the value has a name to know there is a file uploaded
        if (this.value.hasOwnProperty('name') && this.value.name !== null && this.value.name !== '') {
            this.file.selected = true;
            this.file.name = this.value.name;
        }
    },
    methods: {
        expirationChanged: function(value) {
            this.$emit('expiration-changed', value)
        },
        fileChanged: function(e) {
            if(e.target.files.length > 0) {
                this.file.selected = true;
                this.file.name = e.target.files[0].name;
                this.fileToDataURI(e.target.files[0]).then(file => this.$emit('file-changed', file, this.file.name));
            }
        },
        deleteFile: function(e) {
            e.preventDefault();
            this.file = { selected: false, name: '' }
            this.$emit('file-changed', '', '');
        }
    }
}
</script>

