<style scoped>
    div.active-card {
        display: inline-block;
        width: 100%;
        border-radius: 5px;
        color: white;
        background: linear-gradient(to right, #0d47a1 , #4285F4);
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
</style>

<template>
    <div class="row">
        <div class="col">
            <div class="row d-flex align-items-center">
                <div class="offset-1 col-4">
                    <br>
                    <div class="active-card">
                        <div class="title">
                            <h4>Tarjeta de acceso</h4>
                        </div>
                        <hr>
                        <div class="info">
                            <h3 id="display">{{ activeCard.number }}</h3>
                            <small><b>{{ person }}</b></small>
                            <br>
                            <small><b>{{ company }}</b></small>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="offset-1 col-6">
                    <div class="row">
                        <div class="col">
                            <small>Nivel de riesgo</small>
                            <br>
                            <strong>{{ activeCard.risk }}</strong>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col">Validez</div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <small>Desde: </small>
                                    <strong>{{ activeCard.from }}</strong>
                                </div>
                                <div class="col-6">
                                    <small>Hasta: </small>
                                    <strong>{{ activeCard.until }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <div v-if="inactiveCards.length==0" class="table-container d-flex align-items-center justify-content-center">
                        <h4>No hay tarjetas inactivas</h4>
                    </div>
                    <div v-else>
                        <h4>Tarjetas inactivas</h4>
                        <custom-table
                            :columns="columns"
                            :rows="inactiveCards"
                            maxHeight="30vh"
                        />
                    </div>
                </div>
            </div>   
        </div>
    </div>
</template>

<script>
export default {
    props: {
        activeCard: {
            required: true,
            type: Object
        },
        inactiveCards: {
            required: false,
            type: Array,
            default: () => []
        },
        person: {
            required:true,
            type: String
        },
        company: {
            required:true,
            type: String
        }
    },
    data: function() {
        return {
            columns: [ 
                {name: 'number', text: 'Número'},
                {name: 'from', text: 'Desde'},
                {name: 'until', text: 'Hasta'},
            ],
        }
    },
}
</script>

