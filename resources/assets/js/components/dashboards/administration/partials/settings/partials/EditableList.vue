<style lang="scss" scoped>
    i { cursor: pointer }

    table {
        width: 100%;
        td { vertical-align: baseline }
        & > tbody {
            & > tr {
                & > td.content {
                    width: 85%;
                    text-align: left;
                }
                & > td.option {
                    text-align: right;
                    color: transparent;
                    &.active { color: black; }
                }

                &:hover > td.option { color: black; }
            }
        }
        & > tfoot > tr > td { padding-top: 10px }
    }

    div.list-item {
        position: relative;
        padding-left: 8px;
        & > div.inputs {
            display: inline-block;
            width: 80%;
        }

        & > div.buttons {
            position: absolute;
            right: 0; top: 0;
            display: inline-block;
            text-align: right;
            visibility: hidden;
            &.active {
                visibility: visible;
            }
            & > button.btn.btn-link {
                & > * { color: black }
                padding-top: 0; 
                padding-bottom: 0; 
            }
        }

        &:hover > div.buttons {
            visibility: visible;
        }

        &.hoverable:hover {
            background-color: rgba(240, 240, 240, .5);
        }
    }
</style>

<template>
    <div>
        <template v-if="loading">
            <i class="fas fa-spinner fa-spin mr-2"></i> Actualizando lista...
        </template>
        <template v-else-if="items.length > 0">
            <div class="list-item hoverable" v-for="(item, key) in items" :key="item.id ? item.id : key">
                <div class="inputs">
                    <!-- Edit slot -->
                    <slot v-if="editing.is && editing.id === item.id" name="input" :values="editing.values" :errors="editing.errors"/>
                    <!-- Item show slot -->
                    <div class="cursor-pointer" v-else @click="edit(item)">
                        <slot name="show" :item="item"/>
                    </div>
                </div>
                <div :class="`buttons ${editing.is && editing.id === item.id ? 'active' : ''}`">
                    <!-- Cancel edit button -->
                    <button v-if="editing.is && !editing.saving && editing.id === item.id" class="btn btn-link" @click="cancelEdit">
                        <i class="fas fa-times"></i>
                    </button>
                    <!-- Edit button -->
                    <button v-if="!editing.is || (editing.is && editing.id !== item.id)" class="btn btn-link" @click="edit(item)">
                        <i class="fas fa-pen"></i>
                    </button>
                    <!-- Save edit button -->
                    <button v-else-if="editing.is && !editing.saving && editing.id === item.id" class="btn btn-link" @click="saveEdit">
                        <i class="fas fa-check"></i>
                    </button>
                    <!-- Loading icon -->
                    <button v-else-if="editing.id === item.id" class="btn btn-link">
                        <i class="fas fa-spinner fa-spin"></i>
                    </button>
                </div>
            </div>
        </template>
        <h6 v-else class="text-center">
            No hay datos
        </h6>
        <div v-if="!loading" class="list-item">
            <div class="inputs">
                <!-- Create slot -->
                <slot v-if="creating.is" name="input" :values="creating.values" :errors="creating.errors"/>
            </div>
            <div class="buttons active">
                <!-- Cancel create button -->
                <button v-if="creating.is && !creating.saving" class="btn btn-link" @click="cancelCreate">
                    <i class="fas fa-times"></i>
                </button>
                <!-- Create button -->
                <el-tooltip v-if="!creating.is" class="item" effect="dark" content="Agregar nuevo item a la lista" placement="left">
                    <button class="btn btn-link" @click="create">
                        <i class="fas fa-plus"></i>
                    </button>
                </el-tooltip>
                <!-- Save create button -->
                <button v-else-if="creating.is && !creating.saving" class="btn btn-link" @click="saveCreate">
                    <i class="fas fa-check"></i>
                </button>
                <!-- Loading icon -->
                <button v-else class="btn btn-link">
                    <i class="fas fa-spinner fa-spin"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        loading: {
            type:       Boolean,
            required:   false,
            default:    false
        },
        items: {
            type:       Array,
            required:   true
        },
        data: {
            type:       Object,
            required:   true
        }
    },
    data() {
        return {
            creating: {
                is:     false,
                saving: false,
                values: this.data_model,
                errors: []
            },
            editing: {
                id:     null,
                is:     false,
                saving: false,
                values: this.data_model,
                errors: []
            }
        };
    },
    computed: {
        data_model: function() {
            return this.clone(this.data.model);
        }
    },
    methods: {
        create: function() {
            if(!this.creating.saving) {
                this.creating.is = true;
                this.creating.values = this.data_model;
                this.creating.errors = [];
                this.$emit('create');
            }
        },
        cancelCreate: function() {
            if(!this.creating.saving) {
                this.creating.is     = false;
                this.creating.values = this.data_model;
                this.creating.errors = [];
            }
        },
        saveCreate: function() {
            this.creating.saving = true;
            axios.post(this.data.create, this.creating.values)
            .then(response => {
                this.creating.is = false; 
                this.$emit('save-create-success', response.data);
            })
            .catch(error => {
                if(error.response.status === 422) {
                    console.log(error.response.data.errors);
                    
                    this.creating.errors = error.response.data.errors;
                }
            })
            .finally(() => this.creating.saving = false);
        },
        edit: function(item) {
            if(!this.editing.saving) {
                this.editing.id = item.id;
                this.editing.is = true;
                this.editing.values = this.clone(item);
                this.editing.errors = [];
                this.$emit('edit');
            }
        },
        cancelEdit: function() {
            if(!this.editing.saving) {
                this.editing.id     = null;
                this.editing.is     = false;
                this.editing.values = this.data_model;
                this.editing.errors = [];
            }
        },
        saveEdit: function() {
            this.editing.saving = true;
            axios.put(this.data.edit.replace(':id', this.editing.id), this.editing.values)
            .then(response => {
                this.$emit('save-edit-success', response.data);
                this.editing.id = null;
                this.editing.is = false; 
            })
            .catch(error => {
                if(error.response.status === 422) {
                    this.editing.errors = error.response.data.errors;
                }
            })
            .finally(() => this.editing.saving = false);
        }
    },
    watch: {
        items: function() {
            this.cancelCreate();
            this.cancelEdit();
        },
        'editing.id': function(value) {
            if(value !== null) {
                this.cancelCreate();
            }
        },
        'creating.is': function(value) {
            if(value) {
                this.cancelEdit();
            }
        }
    }
}
</script>
