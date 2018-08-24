<style lang="scss" scoped>
    a {
        color: black;
        &:hover {
            color: #3F729B;
            text-decoration: none;
        }
    }

    label, small {
        display: block;
    }

    div.active-card {
        display: inline-block;
        width: 100%;
        border-radius: 5px;
        color: white;
        background: linear-gradient(to right, #0d47a1 , #4285F4);
        margin-bottom: 5px;
        margin-top: 5px;
    }

    div.active-card > hr {
        height: 12px;
        border: 0;
        box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5);
        margin: 0;
    }

    div.active-card > div.title {
        padding: 5%;
        padding-bottom: 5px;
    }

    div.active-card > div.title > h4 {
        display: inline-block;
    }

    div.active-card > div.info {
        padding: 5%;
        padding-top: 0;
    }
        
    div.table-container {
        height: 30vh;
    }

    div.job {
        border-radius: 3px;
        border: 1px solid rgb(222,222,222);
        padding: 1em;
    }
</style>

<template>
    <div>
        <!-- Basic information -->
        <div class="row d-flex align-items-center">
            <div class="col-3">
                <label>Nivel de riesgo:</label>
                <strong>{{ personCompany.risk }}</strong>
            </div>
            <div class="col-3">
                <label>Vencimiento  curso PBIP:</label>
                <strong>{{ personCompany.pbip }}</strong>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col">
                        <label>ART:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <small>Aseguradora</small>
                        <strong>{{ personCompany.art_company }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Número</small>
                        <strong>{{ personCompany.art_number }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Vencimiento</small>
                        <strong>{{ personCompany.art_expiration }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <!-- Separator -->
        <div class="row">
            <div class="col">
                <hr>
            </div>
        </div>
        <!-- Jobs list -->
        <div v-for="(job, key) in personCompany.jobs" :key="key" class="job mb-3">
            <div class="row">
                <div class="col">
                    <h5>
                        <template v-if="job.company_id">
                            {{ job.company_name }} <span style="color: lightgrey">|</span>
                        </template>
                        <span style="color: grey">{{ job.activity_name }}</span>
                    </h5>
                    <span v-for="(subactivity, sub_key) in job.subactivities" :key="sub_key" class="badge badge-ligth mr-1">
                        {{ subactivity }}
                    </span>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-4 d-flex align-items-center">
                    <template v-if="job.company_id">
                        <table>
                            <tr>
                                <td>Área:</td>
                                <td class="font-weight-bold">{{ job.company_area }}</td>
                            </tr>
                            <tr>
                                <td>CUIT:</td>
                                <td class="font-weight-bold">{{ job.company_cuit }}</td>
                            </tr>
                            <tr>
                                <td>Venc.:</td>
                                <td class="font-weight-bold">{{ job.company_expiration }}</td>
                            </tr>
                        </table>
                    </template>
                    <h6 v-else class="text-center" style="color: grey">
                        No hay empresa asignada para esta actividad
                    </h6>
                </div>
                <div class="col-8">
                    <div class="row">
                        <div v-for="(card, key) in job.cards" :key="key" class="col-6">
                            <div class="active-card shadow-sm">
                                <div class="title">
                                    <h5>Tarjeta de acceso</h5>
                                </div>
                                <hr>
                                <div class="info">
                                    <h4 id="display">{{ card.number }}</h4>
                                    <small>Desde: <b>{{ card.from }}</b></small>
                                    <small>Hasta:&nbsp; <b>{{ card.until }}</b></small>
                                </div>
                            </div>
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
            required: true,
            type: Object
        }
    }
}
</script>

