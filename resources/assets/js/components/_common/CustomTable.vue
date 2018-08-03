<style lang="scss" scoped>
    table {
        display: flex;
        flex-flow: column;
        height: 100%; width: 100%;
        & > thead {
            flex: 0 0 auto;
            width: calc(100% - 5px);
            & > tr {
                border: 0;
                border-bottom: 1px solid grey;
                & > th {
                    padding-left: 1em;
                    cursor: pointer; 
                    border: 0;
                }
            }
        }

        & > thead, & > tbody > tr {
            display: table;
            table-layout: fixed;
        }

        & > tbody {
            flex: 1 1 auto;
            display: block;
            overflow-y: scroll;
            & > tr {
                width: 100%;
            }
            & > tr > td > i.selected-item {
                color: #3F729B;
            }
            & > tr:hover {
                cursor: pointer;
                background-color: rgb(240, 240, 240);
            }
            & > tr > td {
                border: 0;
                border-bottom: 1px solid whitesmoke;
                padding: 8px;
            } 
        }

        & > thead > tr > th.pickable, & > tbody > tr > td.pickable {
            width: 3em;
        }
    }
</style>

<template>
    <div class="d-flex align-items-center justify-content-center" :style="'min-height:40vh; height:' + maxHeight">
        <table v-if="shown_rows.length > 0">
            <thead>
                <tr>
                    <th v-if="pickable.active" class="pickable"></th>
                    <th v-for="(column,key) in columns" :key="key" @click="sortColumn(key)">
                        {{ column.text }}
                        <i v-if="sort.column === key && sort.order !== 0" :class="'float-right centered fas fa-sort-' + (sort.order === 1 ? 'up' :  'down' )"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row,key) in shown_rows" :key="key" @click="click(row)">
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
        <h3 v-else class="text-center justified-center">No se encontraron coincidencias</h3>
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
        maxHeight: {
            type: String,
            required: false,
            default: '100%'
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
            shown_rows: JSON.parse(JSON.stringify(this.rows)),
            sort: {
                column: 0,
                order: 0,
            }
        }
    },
    watch: {
        rows: {
            handler: function() {
                this.shown_rows = JSON.parse(JSON.stringify(this.rows));
            },
            deep: true
        },
        filter: {
            handler: function() {
                this.shown_rows = JSON.parse(JSON.stringify(this.rows)).filter(row => {
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
            deep: false
        }
    },
    methods: {
        click: function(row) {
            this.$emit('rowclicked', row);
        },
        sortColumn: function(key, skipOrder) {
            if(this.shown_rows.length > 0) {
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
                    this.shown_rows = JSON.parse(JSON.stringify(this.rows));
                }
                else {
                    let col_name = this.columns[key].name;
                    this.shown_rows.sort((a, b) => this.sort.order * a[col_name].toString().localeCompare(b[col_name].toString()));   
                }
            }
        }
    }
}
</script>
