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
</style>

<template>
    <div>
        <template v-if="rows.length > 0">
            <!-- <div v-if="rowsquantity === null" class="row mb-2">
                <div class="col-4">
                    Mostrar
                    <select2 :value="pagination.quantity" @input="value => pagination.quantity = value" :options="options.rows" :clearable="false" size="mini" width="30%"/>
                    filas
                </div>
                <div class="offset-4 col-4 d-flex justify-content-end">
                    <input v-model="condition" type="text" class="md-input" placeholder="Búsqueda" :disabled="advancedsearch" style="width:14em">
                    <i class="fas fa-search"></i>
                </div>
            </div> -->
            <!-- Data displayed on a table -->
            <div class="row">
                <div class="col">
                    <div class="grey-border p-0">
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
                                <template v-if="pagination.page.length > 0">
                                    <tr v-for="(row,key) in pagination.page" :key="key" @click="click(row)">
                                        <td v-if="pickable.active" class="pickable text-center" :key="key + '-pickable'">
                                            <i v-if="pickable.list.includes(row.id)" class="far fa-check-square text-unique"></i>
                                            <i v-else class="far fa-square" style="color: rgba(0,0,0,0.3)"></i>
                                        </td>
                                        <td v-for="(column,column_key) in columns" :key="column_key">
                                            {{ row[column.name] }}
                                        </td>
                                    </tr>
                                </template>
                                <tr v-else><td :colspan="columns.length" class="text-center">No se encontraron resultados de la búsqueda</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <!-- <div class="row mt-2">
                <div v-if="rowsquantity !== null" class="col-4">
                    <i class="fas fa-search"></i>
                    <input v-model="condition" type="text" class="md-input" placeholder="Búsqueda" style="width:12em" :disabled="advancedsearch">
                </div>
                <div v-if="shown_rows.length > 0" :class="`col-${rowsquantity !== null? '8' : '12'}`">
                    <ul class="pagination justify-content-end" style="margin-bottom: 0px">
                        <li :class="`page-item ${pagination.current <= 0 ? 'disabled' : ''}`" @click="changeToPage(pagination.current - 1)">
                            <a class="page-link" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                        </li>
                        <li :key="0" :class="`page-item ${ pagination.current === 0 ? 'active' : ''}`" @click="changeToPage(0)">
                            <a class="page-link">1</a>
                        </li>
                        <li v-if="range.length > 0 && range[0] !== 1" class="page-item disabled">
                            <a class="page-link"><span aria-hidden="true">...</span></a>
                        </li>
                        <li v-for="page in range" :key="page" :class="`page-item ${pagination.current === page ? 'active' : ''}`" @click="changeToPage(page)">
                            <a class="page-link">{{ page+1 }}</a>
                        </li>
                        <li v-if="range.length > 0 && range[range.length-1] !== pagination.last-2" class="page-item disabled">
                            <a class="page-link"><span aria-hidden="true">...</span></a>
                        </li>
                        <li v-if="pagination.last-1 !== 0" :key="pagination.last-1" :class="`page-item ${ pagination.current === pagination.last-1 ? 'active' : ''}`" @click="changeToPage(pagination.last-1)">
                            <a class="page-link">{{ pagination.last }}</a>
                        </li>
                        <li :class="`page-item ${pagination.current >= this.pagination.last - 1 ? 'disabled' : ''}`" @click="changeToPage(pagination.current + 1)">
                            <a class="page-link" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                        </li>
                    </ul>
                </div>
            </div> -->
        </template>
        <!-- No data situation -->
        <h4 v-else class="text-center mt-5 mb-5">{{ noRowsMessage }}</h4>
        
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
            this.$emit('rowclicked', row);
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
