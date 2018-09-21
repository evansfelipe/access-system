<style lang="scss" scoped>
    div.active-card {
        display: inline-block;
        width: 100%;
        max-width: 16em;
        border-radius: 5px;
        color: white;
        background: linear-gradient(to right, #0d47a1 , #4285F4);
        margin-bottom: 5px;
        margin-top: 5px;
        & > hr {
            height: 12px;
            border: 0;
            box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5);
            margin: 0;
        }
        & > div.title {
            padding: 5%;
            padding-bottom: 5px;
            & > h4 {
                display: inline-block;
            }
        }
        & > div.info {
            padding: 5%;
            padding-top: 0;
        }
    }
</style>

<template>
    <div class="active-card">
        <div class="title">
            <h5>Tarjeta de acceso</h5>
        </div>
        <hr>
        <div class="info">
            <h4 id="display">{{ number }}</h4>
            <small class="d-block">Desde: <b>{{ from }}</b></small>
            <small class="d-block">Hasta:&nbsp; <b>{{ until }}</b></small>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        number: {
            required: true,
            type: String
        },
        from: {
            required: true,
            type: String
        },
        until: {
            required: true,
            type: String
        }
    }
}
</script>
