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
                    />
                </div>
                <div class="col-md-12 col-lg-6">
                    <expiration-file    label="Certificado de cobertura ART" :errors="errors[job.key]"
                                        name="art_file" :value="job.art_file"
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
    }
}
</script>
