<template>
    <div class="row">
        <div class="offset-1 col-5" style="border-right: 1px solid grey">
            <h4>{{ personCompany.company_name }}</h4>
            <div class="row">
                <div class="col-6">
                    <small>CUIT</small>
                    <br>
                    <strong>{{ personCompany.company_cuit }}</strong>
                </div>
                <div class="col-6">
                    <small>Sector</small>
                    <br>
                    <strong>{{ personCompany.company_area }}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-6">
                    <small>Actividad / Categoría</small>
                    <br>
                    <strong>{{ personCompany.activity }}</strong>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="row">
                <div class="col-6">
                    <small>ART</small>
                    <br>
                    <strong>{{ personCompany.art_company }}</strong>
                </div>
                <div class="col-6">
                    <small>Vencimiento</small>
                    <br>
                    <strong>{{ personCompany.art_expiration }}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-6">
                    <small>Vencimiento curso PBIP</small>
                    <br>
                    <strong>{{ personCompany.pbip }}</strong>
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

