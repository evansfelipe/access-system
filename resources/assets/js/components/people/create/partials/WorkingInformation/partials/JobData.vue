<template>
    <div class="row">
        <!-- Company -->
        <form-item col="col-4" label="Empresa" :errors="company_errors">
            <div class="col">
                <select2    placeholder="Seleccione una empresa" :value="job.company_id" :options="companies"
                            @input="(value) => editJob('company_id', value)"/>
            </div>
        </form-item>
        <!-- Activity -->
        <form-item col="col-4" label="Actividad" :errors="activity_errors">
            <div class="col">
                <select2    placeholder="Seleccione una actividad" :value="job.activity_id" :options="activities"
                            @input="(value) => editJob('activity_id', value)"/>
            </div>
        </form-item>
        <!-- Subactivities -->
        <form-item col="col-4" label="Subactividad/es" :errors="subactivities_errors">
            <div class="col">
                <select2    placeholder="Seleccione subactividad/es" :value="job.subactivities" :options="subactivities_options"
                            :tags="true" :name="'subactivities'" :multiple="true"
                            @input="(value) => editJob('subactivities', value)"/>
            </div>
        </form-item>
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
        companies: {
            type: Array,
            required: false,
            default: () => []
        },
        activities: {
            type: Array,
            required: false,
            default: () => []
        },
        subactivities: {
            type: Array,
            required: false,
            default: () => []
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
            return this.subactivities.concat(this.job.subactivities.map(s => { return { id: s, text: s } }));
        }
    },
    methods: {
        editJob: function(attribute, value) {
            let data = {
                company_id:     attribute === 'company_id'      ? value : this.job.company_id,
                activity_id:    attribute === 'activity_id'     ? value : this.job.activity_id,
                subactivities:  attribute === 'subactivities'   ? value : this.job.subactivities,
            }
            this.$emit('change', data);
        }
    }
}
</script>
