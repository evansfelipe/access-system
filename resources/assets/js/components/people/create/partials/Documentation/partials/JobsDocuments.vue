<template>
    <div>
        <div v-for="job in jobs" :key="job.key">
            <!-- Job information for identification -->
            <div class="form-row">
                <div class="col">
                    <hr-label>{{job.company_name + ' | ' + job.activity_name}}</hr-label>
                </div>
            </div>
            <!-- File inputs -->
            <div class="form-row">
                <div class="col-md-12 col-lg-6">
                    <expiration-file    label="Nota de la empresa" :errors="errors[job.key]"
                                        name="company_note" :value="job.company_note"
                                        @expiration-changed="value => update({job_key: job.key, name: 'company_note.expiration', value})"
                                        @file-changed="(file, name) => {    update({job_key: job.key, name: 'company_note.file', value: file});
                                                                            update({job_key: job.key, name: 'company_note.name', value: name}); }"
                    />
                </div>
                <div class="col-md-12 col-lg-6">
                    <expiration-file    label="Certificado de cobertura ART" :errors="errors[job.key]"
                                        name="art_file" :value="job.art_file"
                                        @expiration-changed="value => update({job_key: job.key, name: 'art_file.expiration', value})"
                                        @file-changed="(file, name) => {    update({job_key: job.key, name: 'art_file.file', value: file});
                                                                            update({job_key: job.key, name: 'art_file.name', value: name}); }"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {
        'expiration-file': require('./ExpirationFile.vue'),
    },
    props: {
        jobs: {
            type: Array,
            required: true
        },
        errors: {
            type: Array,
            required: true
        }
    },
    methods: {
        update: function({job_key, name, value}) {
            this.$store.commit('updateJob', {job_key, attribute: name, value});
        }
    }
}
</script>
