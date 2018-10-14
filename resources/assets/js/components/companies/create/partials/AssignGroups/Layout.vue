<style lang="scss" scoped>
    .group-enter-active, .group-leave-active { transition: all .5s }
    .group-enter, .group-leave-to { opacity: 0 }
    .group-enter { transform: translateX(-10px) }
    .group-leave-active { max-height: 100vh }
    .group-leave-to {
        max-height: 0;
        transform: translateX(10px);
    }

    .btn-link {
        padding: 0;
        text-decoration: none;
    }

    .btn-remove {
        position: absolute;
        right: 15px;
        z-index: 10;
        cursor: pointer;
        color: #CC0000;
    }
</style>


<template>
    <div>
        <loading-cover v-if="this.$store.getters.gates.updating"/>
        <template v-else>
            <transition-group name="group" tag="div">
                <div class="grey-border mb-2" v-for="group in values.groups" :key="group.key">
                    <div class="form-row">
                        <div class="col" style="text-align: right">
                            <i class="btn-remove far fa-trash-alt" @click="deleteGroup(group)"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <group-information
                                :companyname="companyname"
                                :gates="gates"
                                :values="group"
                                :errors="groups_errors[group.key] ? groups_errors[group.key] : []"
                                @change="({attribute, value}) => editGroup(group, attribute, value)"
                            />
                        </div>
                    </div>
                </div>
            </transition-group>
            <!-- Add job button -->
            <div class="mt-3">
                <div class="row">
                    <div class="col text-right">
                        <button class="btn btn-link" @click="addGroup">
                            <i class="fas fa-plus"></i> Agregar grupo
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    components: {
        'group-information': require('./partials/GroupInformation')
    },
    props: {
        values: {
            required: true,
            type: Object
        },
        companyname: {
            required: true,
            type: String
        },
        errors: {
            required: true,
            type: Array
        }
    },
    data() {
        return {
            groups_errors: []
        }
    },
    beforeMount() {
        this.$store.dispatch('fetchList','gates');
    },
    computed: {
        gates: function() {
            return this.$store.getters.gates.asOptions();
        }
    },
    methods: {
        addGroup: function() {
            this.$store.commit('addGroup');
        },
        editGroup: function(group, attribute, value) {
            this.$store.commit('updateGroup', {group_key: group.key, attribute, value});
        },
        deleteGroup: function(group) {
            if(this.$store.getters.company.id && !group.key.toString().startsWith('T')) {
                this.$confirm('Si realiza esta acción se desasociarán las personas al grupo', '', {
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar',
                    type: 'error'
                })
                .then(() => {
                    this.$store.commit('deleteGroup', group);
                })
                .catch(() => {});
            }
            else {
                this.$store.commit('deleteGroup', group);
            }
        }
    },
    watch: {
        errors: function() {
            let ret = [];
            let errors = this.errors['groups'] ? this.errors['groups'] : [];
            console.log(this.errors['groups']);
            
            errors.forEach((e, index) => {
                if(this.values.groups[index]) {
                    let key = this.values.groups[index].key;
                    ret[key] = e;
                }
            });
            this.groups_errors = ret;
        }
    }
}
</script>
