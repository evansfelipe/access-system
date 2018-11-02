<template>
    <div>
        <!-- List -->
        <div class="row">
            <div class="col">
                <custom-table
                    :updating="updating"
                    :columns="columns"
                    :rows="paginator.data"
                    :pickable="pickable"
                    @rowclicked="rowClicked"
                    @sort="sortHandler"
                />
            </div>
        </div>
        <!-- Pagination and Search -->
        <div class="row mt-3">
            <div class="col-6">
                <search-input v-if="wildcard" :updating="updating" @input="searchHandler" placeholder="Buscar..."/>
            </div>
            <div class="col-6">
                <paginator-links v-if="paginator.data.length > 0" :paginator="paginator" @paginate="page => paginate(page)"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        list: {
            type: String,
            required: true
        },
        columns: {
            type: Array,
            required: true
        },
        filters: {
            type: Object,
            required: true
        },
        wildcard: {
            type: Boolean,
            required: false,
            default: true
        },
        pickable: {
            type: Object,
            required: false,
            default: () => { return { active: false, list: [] } },
        },
        paginateOnMounted: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    mounted() {
        if(this.paginateOnMounted) {
            this.paginate(1);
        }
    },
    data() {
        return {
            search: '',
            sort: {
                column: null,
                order:  null,
            }
        };
    },
    computed: {
        /**
         * 
         */
        updating: function() {
            return this.$store.getters[this.list].updating;
        },
        /**
         * 
         */
        paginator: function() {
            return this.$store.getters[this.list].paginator;
        }
    }, 
    methods: {
        /**
         * Asks to the server for the given page.
         */
        paginate: function(page) {
            let filters = {...this.filters};
            if(this.wildcard) {
                filters.wildcard = this.search;
            }
            this.$store.dispatch('paginateList', {what: this.list, page, filters: filters, sort: this.sort});
        },
        /**
         * Paginates the list using the new sort condition.
         */
        sortHandler: function(sort) {
            this.sort = sort;
            this.paginate(1);
        },


        searchHandler: function(value) {
            this.search = value;
            this.paginate(1);
        },

        rowClicked: function(row) {
            this.$emit('rowclicked', row);
        }
    },
    watch: {
        filters: {
            handler: function() {
                this.paginate(1);
            },
            deep: true
        }
    }
}
</script>

