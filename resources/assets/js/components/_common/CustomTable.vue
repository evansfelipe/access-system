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
    }
</style>

<template>
    <div>
        <table v-if="shown_rows.length > 0">
            <thead>
                <tr>
                    <th v-for="(column,key) in columns" :key="key" @click="sortColumn(key)">
                        {{ column.text }}
                        <i v-if="sort.column === key && sort.order !== 'none'" :class="'float-right centered fas fa-sort-' + (sort.order === 'asc' ? 'up' :  'down' )"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row,key) in shown_rows" :key="key" @click="click(row)">
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
            default: {
                strict: false,
                conditions: {}
            }
        }
    },
    data() {
        return {
            shown_rows: JSON.parse(JSON.stringify(this.rows)),
            sort: {
                column: 0,
                order: 'none',
            }
        }
    },
    watch: {
        rows: function() {
            this.shown_rows = JSON.parse(JSON.stringify(this.rows));
        },
        filter: {
            handler: function() {
                
                this.shown_rows = this.rows.filter(row => {
                    let ret;
                    if(this.filter.strict) {
                        ret = true;
                        this.columns.forEach(column => {
                            if(this.filter.conditions[column.name])
                                ret = ret && row[column.name].matches(this.filter.conditions[column.name]);
                        });
                    }
                    else {
                        ret = false;
                        this.columns.forEach(column => {
                            if(this.filter.conditions['all'] !== undefined) {
                                let aux = typeof row[column.name] === 'String' ? row[column.name] : row[column.name].toString();
                                ret = ret || aux.matches(this.filter.conditions['all']);
                            }
                        });
                    }
                    return ret;
                });
            },
            deep: true
        }
    },
    methods: {
        click: function(row) {
            this.$emit('rowclicked', row);
        },
        sortColumn: function(key) {
            if(this.shown_rows.length > 0) {
                if(this.sort.column === key) {
                    switch(this.sort.order) {
                        case 'none': this.sort.order = 'asc'; break;
                        case 'asc': this.sort.order = 'desc'; break;
                        case 'desc': this.sort.order = 'none'; break;
                    }
                }
                else {
                    this.sort = {
                        column: key,
                        order: 'asc'
                    }
                }
                let mult = 0;
                switch(this.sort.order) {
                    case 'none': this.shown_rows =  JSON.parse(JSON.stringify(this.rows)); break;
                    case 'asc': mult = 1; break;
                    case 'desc': mult = -1; break;
                }
                
                if(mult) this.shown_rows.sort((a, b) => {
                    let aux1 = typeof a[this.columns[key].name] === 'String' ? a[this.columns[key].name] : a[this.columns[key].name].toString();
                    let aux2 = typeof b[this.columns[key].name] === 'String' ? b[this.columns[key].name] : b[this.columns[key].name].toString();
                    return mult * aux1.localeCompare(aux2);
                });                
            }
        }
    }
}
</script>
