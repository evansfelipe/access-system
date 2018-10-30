<style lang="scss" scoped>
    table {
        width: 100%;
        cursor: pointer;
        & > thead > tr {
            border: 0;
            border-bottom: 1px solid rgb(222,222,222);
            & > th {
                border: 0;
                padding: 0.75em;
            }
        }

        & > tbody > tr {
            &:hover {
                background-color: rgb(240, 240, 240);
            }
            & > td {
                border-bottom: 1px solid rgba(222,222,222, .25);
                padding: 0.5em;
            }

            &:last-of-type > td {
                border-bottom: 0;
            }
        }
    }

    .page-item.active > .page-link {
        background-color: #3F729B;
        &:hover { color: white }
    }

    .updating-enter-active, .updating-leave-active { transition: all .3s }
    .updating-enter, .updating-leave-to { opacity: 0; }
    .updating-enter-to, .updating-leave { opacity: 1; }

    div.updating {
        background-color: #3F729B;
        color: white;
        position: absolute;
        padding: .5em 0;
        top: 0;
        left: calc(50% - 100px);
        z-index: 100;
        width: 200px;
        border-bottom-right-radius: .25rem;
        border-bottom-left-radius: .25rem;
        display: flex;
        align-items: center;
        justify-content: center; 
    }

    div.wrapper {
        overflow-x: auto;
        position: relative;
    }
</style>

<template>
    <div class="wrapper grey-border p-0">
        <!-- Loading badget -->
        <transition name="updating">
            <div v-if="updating" class="updating shadow-sm">
                <i class="fas fa-circle-notch fa-spin mr-2"></i> <strong>Cargando...</strong>
            </div>
        </transition>
        <!-- List of data -->
        <table>
            <thead>
                <tr>
                    <th v-if="pickable.active" class="pickable" @click="pickAll">
                        <i v-if="all_picked" class="far fa-check-square text-unique"></i>
                        <i v-else class="far fa-square" style="color: rgba(0,0,0,0.3)"></i>
                    </th>
                    <th v-for="(column,key) in columns" :key="key" @click="sortColumn(column.name)"
                        :style="`width: ${column.hasOwnProperty('width')? column.width : (100/columns.length)}%`"
                    >
                        {{ column.text }}
                        <i  v-if="sort.column === column.name && sort.order !== 'none'" 
                            :class="`float-right fas fa-sort-${sort.order === 'asc' ? 'up' : 'down'}`">
                        </i>
                    </th>
                </tr>
            </thead>
            <tbody>
                <template v-if="rows.length > 0">
                    <tr v-for="(row, key) in rows" :key="key" @click="click(row)">
                        <td v-if="pickable.active" class="pickable text-center">
                            <i v-if="pickable.list.includes(row.id)" class="far fa-check-square text-unique"></i>
                            <i v-else class="far fa-square" style="color: rgba(0,0,0,0.3)"></i>
                        </td>
                        <td v-for="(column, column_key) in columns" :key="column_key">
                            {{ row[column.name] ? row[column.name] : '-' }}
                        </td>
                    </tr>
                </template>
                <tr v-else>
                    <td :colspan="columns.length + (pickable.active ? 1 : 0)" class="text-center">
                        <strong>{{ noRowsMessage }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        columns: {
            type:     Array,
            required: true
        },
        rows: {
            type:     Array,
            required: true
        },
        rowsquantity: {
            type:     Number,
            required: false,
            default:  null
        },
        pickable: {
            type: Object,
            required: false,
            default: () => {
                return {
                    active: false,
                    list: []
                }
            },
        },
        noRowsMessage: {
            type: String,
            required: false,
            default: 'No se encontraron resultados'
        },
        updating: {
            type: Boolean,
            required: false,
            default: false
        },
        sortable: {
            type: Boolean,
            required: false,
            default: true
        }
    },
    data() {
        let rows_q = (this.rowsquantity !== null) ? this.rowsquantity : 10;
        return {
            sort: {
                column: '',
                order: '',
            },
        }
    },
    computed: {
        /**
         * Checks if all the shown rows are picked
         */
        all_picked: function() {
            let ret = this.pickable.list.length > 0 ? true : false;
            let i = 0;
            while(ret && i < this.rows.length) {
                if(!this.pickable.list.includes(this.rows[i].id)) ret = false;
                i++;
            };
            return ret;
        },
    },
    methods: {
        click: function(row) {
            if(!this.updating) {
                this.$emit('rowclicked', row);
            }
        },
        sortColumn: function(name) {
            if(this.sortable && !this.updating) {
                if(this.sort.column !== name) {
                    this.sort.column = name;
                    this.sort.order  = 'asc';
                }
                else if(this.sort.column === name && this.sort.order === 'asc') {
                    this.sort.order = 'desc';
                }
                else {
                    this.sort.column = null;
                    this.sort.order  = null;
                }
    
                this.$emit('sort', this.sort);
            }
        },
        pickAll: function() {
            if(!this.all_picked) {
                this.rows.forEach(row => {
                    if(!this.pickable.list.includes(row.id)) this.$emit('rowclicked', row);                  
                });
            }
            else {
                this.rows.forEach(row => {
                    this.$emit('rowclicked', row);                  
                });
            }
        }

    }
}
</script>
