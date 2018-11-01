<template>
    <index-wrapper @advanced-search-submit="commitFilters" @advanced-search-clear="advancedSearchClear">
        <!-- Advanced search -->
        <template slot="advanced-search-filters">
            <div class="form-row">
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Nombre" v-model="tempFilters.name">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Empresa" v-model="tempFilters.company">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Zona" v-model="tempFilters.zone">
                </div>
                <div class="col-12 col-md-3">
                    <input type="text" class="form-control form-control-sm" @keyup.enter="commitFilters" placeholder="Franja horaria" v-model="tempFilters.range">
                </div>
            </div>
        </template>
        <!-- List -->
        <template slot="main-content">
            <remote-custom-table    list="groups" 
                                    :columns="columns"
                                    :filters="filters"
                                    @rowclicked="showProfile"
                                    :paginate-on-mounted="true"
                                    :wildcard="false"
            />
        </template>
    </index-wrapper>
</template>

<script>
export default {
    data: function() {
        return {
            columns: [
                { name: 'name',         text: 'Nombre',         width:'40' },
                { name: 'company',      text: 'Empresa',        width:'20' },
                { name: 'zone',         text: 'Zona',           width:'20' },
                { name: 'range',        text: 'Franja horaria', width:'20' }
            ],
            filters: {},
            tempFilters: {
                name:       "",
                company:    "",
                zone:       "",
                range:      ""
            },
        }
    },
    methods: {
        /**
         * Redirects to the profile of the clicked group.
         */
        showProfile: function(group) {
            this.$router.push(`/groups/show/${group.id}`);
        },
        /**
         * Restarts filters and pagination.
         */
        advancedSearchClear: function() {
            this.tempFilters = {
                name:       "",
                company:    "",
                zone:       "",
                range:      ""
            };
            this.commitFilters();
        },
        /**
         * 
         */
        commitFilters: function() {
            this.filters = {
                name:       this.tempFilters.name,
                company:    this.tempFilters.company,
                zone:       this.tempFilters.zone,
                range:      this.tempFilters.range
            };
        }
    }
}
</script>