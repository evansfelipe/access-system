<template>
    <div>
        <div class="form-row">
            <!-- Company -->
            <form-item col="col-4" label="Empresa" :errors="company_errors">
                <div class="col">
                    <remote-select2 :value="job.company_id" path="/selects/companies" placeholder="Seleccione una empresa"
                                    @input="value => editJob('company_id', value)" :fixed-params="{id: job.company_id}"/>
                </div>
            </form-item>
            <!-- Activity -->
            <form-item col="col-4" label="Actividad" :errors="activity_errors">
                <div class="col">
                    <remote-select2 :value="job.activity_id" path="/selects/activities" placeholder="Seleccione una actividad"
                                    @input="value => editJob('activity_id', value)" :fixed-params="{id: job.activity_id}"/>
                </div>
            </form-item>
            <!-- Subactivities -->
            <form-item col="col-4" label="Subactividad/es" :errors="subactivities_errors">
                <div class="col">
                    <remote-select2 :value="job.subactivities" path="/selects/subactivities" placeholder="Seleccione subactividad/es" multiple
                                    @input="value => editJob('subactivities', value)" :fixed-params="{name: job.subactivities, activity_id: [job.activity_id]}"/>
                </div>
            </form-item>
        </div>

        <div class="form-row">
            <form-item col="col-12" label="Grupo/s" :errors="groups_errors">
                <div class="col">
                    <select2    placeholder="Seleccione grupo/s" :value="job.groups" :options="groups_options"
                                :multiple="true" name="groups"   @input="value => editJob('groups', value)"
                    />
                </div>
            </form-item>
        </div>

        <div class="form-row">
            <div class="col-12">
                <hr-label>ART</hr-label>
            </div>
            <!-- ART Company -->
            <form-item  label="Aseguradora" col="col-6" :errors="errors['art_company'] || []">
                <div class="col">
                    <input  type="text" class="form-control" :value="job.art_company"
                            @input="(e) => editJob('art_company', e.target.value)">
                </div>
            </form-item>
            <!-- ART Number -->
            <form-item label="Número de socio" col="col-6" :errors="errors['art_number'] || []">
                <div class="col">
                    <input  type="number" class="form-control" :value="job.art_number"
                            @input="(e) => editJob('art_number', e.target.value)">
                </div>
            </form-item>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        job: {
            type: Object,
            required: true
        },
        errors: {
            type: Array,
            required: true
        },
    },
    computed: {
        company_errors: function() {
            return this.errors['company_id'] || [];
        },
        activity_errors: function() {
            return this.errors['activity_id'] || [];
        },
        subactivities_errors: function() {
            return this.errors['subactivities'] || [];
        },
        subactivities_options: function() {
            return this.$store.getters.subactivities.forParent('activity_id', this.job.activity_id).asOptions();
        },
        groups_errors: function() {
            return this.errors['groups'] || [];
        },
        groups_options: function() {
            return this.$store.getters.groups.forParent('company_id', this.job.company_id, false).asOptions();
        }
    },
    methods: {
        editJob: function(attribute, value) {
            this.$emit('change', {attribute, value});
        }
    },
    watch: {
        'job.company_id': function() {
            let groups = this.job.groups.filter(group => {
                let data = this.$store.getters.groups.getById(group);
                return data.company_id === this.job.company_id || data.company_id === null;
            });
            this.editJob('groups', groups);
        }
    }
}
</script>
