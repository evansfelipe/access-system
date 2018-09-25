<template>
    <div>
        <div class="grey-border">
            <jobs-documents :jobs="jobs" :errors="job_documents_errors"/>
            <hr>
            <other-documents :documents="other_documents" :errors="other_documents_errors"/>
        </div>
        <div class="grey-border mt-2">
            <label>¿Qué documentos son requeridos para el ingreso?</label>
            <documents-required :documents-required="documents_required"/>
        </div>
    </div>
</template>

<script>
export default {
    components: {
        'jobs-documents':       require('./partials/JobsDocuments.vue'),
        'other-documents':      require('./partials/OtherDocuments.vue'),
        'documents-required':   require('./partials/DocumentsRequired'),
    },
    props: {
        errors: {
            type: Array,
            required: true
        }
    },
    computed: {
        documents_required: function() {
            return this.$store.getters.person.values.documentation.documents_required;
        },
        jobs: function() {
            return this.$store.getters.person.values.working_information.jobs.map(job => {
                let company = this.$store.getters.companies.getById(job.company_id);
                let activity = this.$store.getters.activities.getById(job.activity_id);
                return {
                    key: job.key,
                    company_name: company ? company.name : 'Empresa',
                    activity_name: activity ? activity.name : 'Actividad',
                    company_note: job.company_note,
                    art_file: job.art_file
                }
            });
        },
        job_documents_errors: function() {
            return [];
        },
        other_documents: function() {
            return this.$store.getters.person.values.documentation.documents;
        },
        other_documents_errors: function() {
            let ret = [];
            if(this.errors['documents']) {
                for(let key in this.errors['documents']) {
                    let aux = [];
                    aux = this.errors['documents'][key].file ? aux.concat(this.errors['documents'][key].file) : aux;
                    aux = this.errors['documents'][key].expiration ? aux.concat(this.errors['documents'][key].expiration) : aux;
                    ret[key] = aux;
                }
            }
            return ret;
        }
    },
}
</script>
