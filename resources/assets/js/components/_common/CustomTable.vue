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
        position: relative;
    }
</style>

<template>
    <div class="wrapper grey-border p-0">
        <transition name="updating">
            <div v-if="updating" class="updating shadow-sm">
                <i class="fas fa-circle-notch fa-spin mr-2"></i> <strong>Cargando...</strong>
            </div>
        </transition>
        <table>
            <thead>
                <tr>
                    <th v-if="pickable.active" class="pickable" @click="pickAll">
                        <i v-if="all_picked" class="far fa-check-square text-unique"></i>
                        <i v-else class="far fa-square" style="color: rgba(0,0,0,0.3)"></i>
                    </th>
                    <th v-for="(column,key) in columns" :key="key" @click="sortColumn(key)"
                        :style="`width: ${column.hasOwnProperty('width')? column.width : (100/columns.length)}%`"
                    >
                        {{ column.text }}
                        <i v-if="sort.column === key && sort.order !== 0" :class="'float-right centered fas fa-sort-' + (sort.order === 1 ? 'up' :  'down' )"></i>
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
                            {{ row[column.name] }}
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
        advancedsearch: {
            type:     Boolean,
            required: false,
            default:  false
        },
        filter: {
            type:     Object,
            required: false,
            default:  () => {
                return {
                    strict: false,
                    conditions: {}
                };
            }
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
        }
    },
    data() {
        let rows_q = (this.rowsquantity !== null) ? this.rowsquantity : 10;
        return {
            shown_rows: this.clone(this.rows),
            condition: '',
            sort: {
                column: -1,
                order: 0,
            },
            pagination: {
                quantity: rows_q,
                current: 0,
                last: Math.ceil((this.rows.length) / rows_q),
                page: [],
            },
            options: {
                rows: [
                    {id: 10},
                    {id: 25},
                    {id: 50},
                    {id: 100},
                ]
            }
        }
    },
    mounted() {
        this.paginate();
    },
    computed: {
        /**
         * Checks if all the shown rows are picked
         */
        all_picked: function() {
            let ret = this.pickable.list.length > 0 ? true : false;
            let i = 0;
            while(ret && i < this.shown_rows.length) {
                if(!this.pickable.list.includes(this.shown_rows[i].id)) ret = false;
                i++;
            };
            return ret;
        },
        /**
         * Array that has the range of buttons for the navigation pages.
         */
        range: function () {
            let range = [];
            let first;
            let last;
            if(this.pagination.last === 9) {
                first = 1;
                last = 7;
            }
            else {
                first = (this.pagination.current - 2 < 1) ? 1 : this.pagination.current - 2;
                last = (this.pagination.current + 2 < this.pagination.last - 2) ? this.pagination.current + 2 : this.pagination.last - 2;
                switch (first) {
                    case 1: 
                        last = (this.pagination.last - 2 > 6) ? 6 : this.pagination.last - 2;
                        break;
                    case 2:
                        first = 1;
                        last = (this.pagination.last - 2 > 6) ? 6 : this.pagination.last - 2;
                        break;
                }
                switch (last) {
                    case this.pagination.last - 2:
                        first = (this.pagination.last - 7 > 1) ? this.pagination.last - 7 : 1;
                        break;
                    case this.pagination.last-3:
                        first = (this.pagination.last - 7 > 1) ? this.pagination.last - 7 : 1;
                        last = this.pagination.last - 2;
                        break;
                }
            }
            for (let i = first; i <= last; i++) {
                range.push(i);
            }
            return range;
        },
    },
    watch: {
        rows: {
            handler: function() {
                this.shown_rows = this.clone(this.rows);
                this.sortColumn(this.sort.column, true);
                this.filterRows();
            },
            deep: true
        },
        condition: function() {
            // Filters the table in the case that the condition inside the component is used.
            this.filterRows();
        },
        filter: {
            // Filters the table in the case that the conditions to filter the table are sent.
            handler: function() {
                this.shown_rows = this.clone(this.rows).filter(row => {
                    let ret = this.filter.strict ? true : false;
                    if(this.filter.strict) {
                        this.columns.forEach(column => {
                            if(this.filter.conditions[column.name])
                                ret = ret && row[column.name].toString().matches(this.filter.conditions[column.name]);
                        });
                    }
                    else {
                        this.columns.forEach(column => {
                            if(this.filter.conditions['all'] !== undefined) {
                                let aux = typeof row[column.name] === 'String' ? row[column.name] : row[column.name].toString();
                                ret = ret || aux.matches(this.filter.conditions['all']);
                            }
                        });
                    }
                    return ret;
                });
                this.sortColumn(this.sort.column, true);
            },
            deep: true
        },
        'shown_rows.length': function() {
            this.paginate();
        },
        'pagination.quantity': function() {
            this.paginate();
        },
        advancedsearch: function() {
            this.condition = '';
            this.shown_rows = this.clone(this.rows);
        }
    },
    methods: {
        paginate: function() {
            this.pagination.last = Math.ceil((this.shown_rows.length) / this.pagination.quantity);
            this.changeToPage(0);
        },
        changeToPage: function(number) {
            this.pagination.current = (number >= 0 && number <= this.pagination.last - 1)  ? number : (number < 0 ? 0 : this.pagination.last - 1);
            this.pagination.page = this.shown_rows.slice(this.pagination.current * this.pagination.quantity, this.pagination.current * this.pagination.quantity + this.pagination.quantity);
        },
        click: function(row) {
            if(!this.updating) {
                this.$emit('rowclicked', row);
            }
        },
        sortColumn: function(key, skipOrder) {
            if(this.shown_rows.length > 0 && key !== -1) {
                if(!skipOrder) {
                    if(this.sort.column === key) {
                        switch(this.sort.order) {
                            case 0:
                                this.sort.order = 1;
                                break;
                            case 1:
                                this.sort.order = -1;
                                break;
                            case -1:
                                this.sort.order = 0;
                                break;
                        }    
                    }
                    else {
                        this.sort.column = key;
                        this.sort.order = 1;
                    }
                }
                if(this.sort.order === 0 && !skipOrder) {
                    this.shown_rows = this.clone(this.rows);
                    this.filterRows();
                }
                else {
                    let col_name = this.columns[key].name;
                    this.shown_rows.sort((a, b) => this.sort.order * a[col_name].toString().localeCompare(b[col_name].toString()));   
                }
            }
            this.changeToPage(this.pagination.current);
        },
        filterRows: function() {
            this.shown_rows = this.clone(this.rows).filter(row => {
                let ret = false;
                this.columns.forEach(column => {
                    if(this.condition !== undefined) {
                        let aux = typeof row[column.name] === 'String' ? row[column.name] : row[column.name].toString();
                        ret = ret || aux.matches(this.condition);
                    }
                });
                return ret;
            });
            this.sortColumn(this.sort.column, true);
        },
        pickAll: function() {
            if(!this.all_picked) {
                this.shown_rows.forEach(row => {
                    if(!this.pickable.list.includes(row.id)) this.$emit('rowclicked', row);                  
                });
            }
            else {
                this.shown_rows.forEach(row => {
                    this.$emit('rowclicked', row);                  
                });
            }
        }

    }
}
</script>
