<style lang="scss" scoped>
    table {
        width: 100%;
        cursor: pointer;
        & > thead > tr {
            border: 0;
            border-bottom: 1px solid grey;
            & > th {
                border: 0;
                padding: 0.5em;
            }
        }

        & > tbody > tr {
            &:hover {
                background-color: rgb(240, 240, 240);
            }
            & > td {
                border: 0;
                border-bottom: 1px solid whitesmoke;
                padding: 0.5em;
            }
        }
    }
</style>

<template>
    <div>
        <!-- Data situation -->
        <div class="row mb-2">
            <div class="col-4">
                <input type="text" class="form-control form-control-sm" style="height: 28px" placeholder="Búsqueda" v-model="condition">
            </div>
            <!-- Search input -->
            <div v-show="shown_rows.length > 0" class="offset-5 col-3 text-right">
                Mostrar 
                <select class="form-control form-control-sm" style="width: auto; display: inline" v-model.number="pagination.quantity">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select> 
                filas
            </div>
        </div>
        <template v-if="shown_rows.length > 0">
            <!-- Data displayed on a table -->
            <div class="row">
                <div class="col">
                    <table>
                        <thead>
                            <tr>
                                <th v-if="pickable.active" class="pickable"></th>
                                <th v-for="(column,key) in columns" :key="key" @click="sortColumn(key)" :style="'width: ' + 100/columns.length + '%'">
                                    {{ column.text }}
                                    <i v-if="sort.column === key && sort.order !== 0" :class="'float-right centered fas fa-sort-' + (sort.order === 1 ? 'up' :  'down' )"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row,key) in pagination.page" :key="key" @click="click(row)">
                                <td v-if="pickable.active" class="pickable text-center" :key="key + '-pickable'">
                                    <i v-if="pickable.list.includes(row.id)" class="far fa-check-square text-unique"></i>
                                    <i v-else class="far fa-square" style="color: rgba(0,0,0,0.3)"></i>
                                </td>
                                <td v-for="(column,column_key) in columns" :key="column_key">
                                    {{ row[column.name] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col">
                    <ul class="pagination justify-content-end" style="margin-bottom: 0px">
                        <li :class="`page-item ${pagination.current <= 0 ? 'disabled cursor-not-allowed' : ''}`" @click="changeToPage(pagination.current - 1)">
                            <a class="page-link" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                        </li>
                        <li v-for="(i, key) in pagination.last" :key="key" :class="`page-item ${key === pagination.current ? 'active' : ''}`" @click="changeToPage(key)">
                            <a class="page-link">{{ i }}</a>
                        </li>
                        <li :class="`page-item ${pagination.current >= this.pagination.last - 1 ? 'disabled cursor-not-allowed' : ''}`" @click="changeToPage(pagination.current + 1)">
                            <a class="page-link" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </template>
        <!-- No data situation -->
        <h3 v-else class="text-center mt-5 mb-5">No se encontraron coincidencias</h3>
    </div>
</template>

<script>
export default {
    props: {
        columns: {
            type: Array,
            required: true
        },
        rows: {
            type: Array,
            required: true
        },
        filter: {
            type: Object,
            required: false,
            default: () => {
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
        }
    },
    data() {
        return {
            shown_rows: this.clone(this.rows),
            condition: '',
            sort: {
                column: -1,
                order: 0,
            },
            pagination: {
                quantity: 10,
                current: 0,
                last: Math.ceil((this.rows.length) / 10),
                page: [],
            }
        }
    },
    mounted() {
        this.paginate();
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
                                ret = ret && row[column.name].matches(this.filter.conditions[column.name]);
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
        }
    }
}
</script>
