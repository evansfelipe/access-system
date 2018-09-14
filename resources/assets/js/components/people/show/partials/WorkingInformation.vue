<style lang="scss" scoped>
    div.job {
        border-radius: 3px;
        border: 1px solid rgb(222,222,222);
        padding: 1em;
    }
</style>

<template>
    <div>
        <div v-for="(job, key) in personCompany.jobs" :key="key" class="job my-2">
            <!-- Company name, activity & subactivities -->
            <div class="row">
                <div class="col">
                    <h5>
                        <template v-if="job.company_id">
                            {{ job.company_name }} <span style="color: lightgrey">|</span>
                        </template>
                        <span style="color: grey">{{ job.activity_name }}</span>
                    </h5>
                    <span v-for="(subactivity, skey) in job.subactivities" :key="skey" class="badge badge-ligth mr-1">
                        {{ subactivity }}
                    </span>
                    <hr>
                </div>
            </div>
            <!-- Company info, ART & cards -->
            <div class="row">
                <div class="col-4">
                    <!-- Company -->
                    <template v-if="job.company_id">
                        <table>
                            <tr>
                                <td><small>Área:</small></td>
                                <td class="font-weight-bold">{{ job.company_area }}</td>
                            </tr>
                            <tr>
                                <td><small>CUIT:</small></td>
                                <td class="font-weight-bold">{{ job.company_cuit }}</td>
                            </tr>
                        </table>
                        <small class="text-link" @click="$router.push(`/companies/show/${job.company_id}`)">Más información de la empresa</small>
                        <hr>
                    </template>
                    <!-- ART -->
                    <table>
                        <tr>
                            <td><small>Aseguradora:</small></td>
                            <td class="font-weight-bold">{{ job.art_company }}</td>
                        </tr>
                        <tr>
                            <td><small>Nº de socio:</small></td>
                            <td class="font-weight-bold">{{ job.art_number }}</td>
                        </tr>
                    </table>
                </div>
                <!-- Cards -->
                <div class="col-8">
                    <div class="row">
                        <div v-for="(card, key) in job.cards" :key="key" class="col-6">
                            <access-card :number="card.number" :from="card.from" :until="card.until"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        personCompany: {
            type:     Object,
            required: true
        }
    }
}
</script>

