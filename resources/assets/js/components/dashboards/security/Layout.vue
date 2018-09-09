<style lang="scss" scoped>
    ul.steps-vertical {
        // Removes ul style.
        list-style-type: none;
        margin: 0; padding: 0;
        // Number of items counter.
        counter-reset: steps;
        // Style for each li.
        & > li {
            position: relative;
            padding: 0 0 20px 50px;
            margin: 0;
            // Circle with the number of the li inside the ul.
            &:after {
                // Position & Display
                position: absolute;
                top: 0; left: 0;
                display: inline-block;
                // Item number.
                content: counter(steps);
                counter-increment: steps;
                // Fixed size and border radius to make a perfect circle.
                height: 1.5em; width: 1.5em;
                border-radius: 50%;
                // Font style.
                color: white;
                text-align: center;
                font-weight: bold;
                // Item style.
                background-color: #9e9e9e;
                outline: 5px solid white;
            }
            // Changes the color of the circle when the item is active or completed.
            &.active:after { background-color: #3F729B }
            &.completed:after { background-color: #00695c }
            // Line within two consecutive circles.
            &:before {
                position: absolute;
                left: 0.75em;
                top: 0;
                content: "";
                height: 100%;
                width: 0;
                border-left: 1px solid rgb(202, 202, 202);
            }
            &:last-of-type:before { border: none }
            // Style for the content of a item.
            & > div.content {
                margin-top: .8em;
                border: 1px solid rgb(222,222,222);
                padding: 1em;
                border-radius: 5px;
            }
        }
    }

    td.small {
        padding-right: 0.5em;
        font-size: 75%;
    }

    td.strong {
        font-weight: bold;
    }

</style>

<template>
    <div class="container">

        <div v-if="disconnected" class="alert alert-danger">
            Se ha perdido la conexión con el servidor. {{ this.connecting_attempts }}º intento de reconección. <i class="fas fa-spinner fa-spin fa-lg centered"></i>
            <button class="btn btn-link" @click="forceReconnectionMQTT">Reconectar manualmente</button>
        </div>

        <loading-cover v-if="connecting" message="Cargando..."/>
        <ul class="nav nav-tabs">
            <tab-item v-for="(p,key) in people" :key="key" :active="tab === key" @click.native="changeTab(key)">
                <div class="d-inline-block mr-2" @click="closeTab(key)"><i class="fas fa-times"></i></div>
                {{ p.values.full_name }}
            </tab-item>
        </ul>
        <div class="card card-default borderless-top">
            <div class="card-body" style="padding: 2em 3em;">

                <template v-if="person">
                    <ul class="steps-vertical">
                        <li :class="person.step === 0 ? 'active' : (person.step > 0 ? 'completed' : '')">
                            Reconocimiento
                            <div class="content">
                                <h3 class="text-center">{{ person.values.full_name }}</h3>
                                <div class="row d-flex align-items-center justify-content-center">
                                    <div class="col-3">
                                        <img class="img-fluid rounded-circle shadow-sm" :src="person.values.picture_path">
                                    </div>
                                    <div class="offset-1 col-3">
                                        <access-card :number="person.values.card.number" :from="person.values.card.from" :until="person.values.card.until"/>
                                    </div>
                                    <div class="col-4">
                                        <table class="ml-4">
                                            <tr>
                                                <td class="small">Riesgo</td>
                                                <td class="strong">{{ person.values.risk }}</td>
                                            </tr>
                                            <tr>
                                                <td class="small">{{ person.values.document_type }}</td>
                                                <td class="strong">{{ person.values.document_number }}</td>
                                            </tr>
                                            <tr v-if="person.values.company">
                                                <td class="small">Empresa</td>
                                                <td class="strong">{{ person.values.company }}</td>
                                            </tr>
                                            <tr>
                                                <td class="small">Actividad</td>
                                                <td class="strong">{{ person.values.activity }}</td>
                                            </tr>
                                            <tr>
                                                <td class="small">Subactividad {{ person.values.subactivities.length > 1 ? 'es' : '' }}</td>
                                                <td class="strong">{{ person.values.subactivities.join(', ') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li :class="person.step === 1 ? 'active' : (person.step > 1 ? 'completed' : '')">
                            Vehículos
                            <template v-if="person.step === 1">
                                <div class="d-inline-block float-right">
                                    <input v-if="person.vehicles_search" v-model="person.vehicles_search_input" type="text" placeholder="Búsqueda" class="md-input" ref="vehicles_search">
                                    <div class="d-inline cursor-pointer" @click="toggleVehicleSearch"><i class="fas fa-search cursor-pointer"></i></div>
                                </div>
                                <div class="content">
                                    <p-vehicles :vehicles="person.values.vehicles" 
                                                :filter="person.vehicles_search_input"
                                                @selection="id => person.selectVehicle(id)"
                                    />
                                </div>
                            </template>
                        </li>
                        <li :class="person.step === 2 ? 'active' : (person.step > 2 ? 'completed' : '')">
                            Observaciones
                            <div class="content">
                                <div class="row">
                                    <div class="col-4">
                                        <textarea class="form-control" rows="4" placeholder="Nueva observación" v-model="person.textarea" style="resize:none"></textarea>
                                        <button class="btn btn-outline-success btn-block mt-1" @click="newObservation">Enviar</button>
                                    </div>
                                    <div class="col-8">
                                        <custom-table   :columns="observations_columns" :rows="person.values.observations" :rowsquantity="5" 
                                                        @rowclicked="toggleObservation"
                                        />
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <div class="row">
                        <div class="col text-right">
                            <button class="btn btn-link" v-if="person.step > 0" @click="person.previousStep()">
                                <i class="fas fa-angle-double-left fa-sm"></i> Anterior
                            </button>
                            <button class="btn btn-link" v-if="person.step < 2" @click="person.nextStep()">
                                Siguiente <i class="fas fa-angle-double-right fa-sm"></i>
                            </button>
                            <button class="btn btn-outline-success btn-sm" v-if="person.step === 2">
                                Confirmar
                            </button>
                        </div>
                    </div>

                </template>
                
            </div>
        </div>
    </div>
</template>

<script>

class Person {
    constructor(values) {
        this.values = values;

        this.values.observations.forEach(observation => {
            observation.collapsed = true;
            observation.original = observation.text;
            observation.abbreviated = observation.text.length > 100 ? observation.text.substring(0, 99) + '...': observation.text;
            observation.text = observation.abbreviated;
        });

        this.textarea = "";
        this.vehicles_search_input = "";
        this.vehicles_search = false;

        this.step = 0;
        this.vehicle = null;
    }

    addObservation(observation) {
        observation.collapsed = true;
        observation.original = observation.text;
        observation.abbreviated = observation.text.length > 100 ? observation.text.substring(0, 99) + '...': observation.text;
        observation.text = observation.abbreviated;
        this.values.observations.unshift(observation);
    }

    selectVehicle(id) {
        this.vehicle = this.vehicle === id ? null : id;
    }

    previousStep() {
        this.step--;
    }

    nextStep() {
        this.step++;
    }
}

export default {
    components: {
        'p-vehicles': require('./partials/Vehicles')
    },
    data() {
        return {
            connecting: true,
            connecting_attempts: 0,
            disconnected: true,
            client: null,
            people: [],
            person: null,
            tab: 0,

            observations_columns: [
                {name: 'date', text: 'Fecha',   width: '15'},
                {name: 'user', text: 'Usuario', width: '15'},
                {name: 'text', text: 'Texto',   width: '60'}
            ]
        };
    },
    mounted() {
        this.connectMQTT();
    },
    methods: {
        createMQTT: function() {
            if(this.client === null) {
                this.client = new Messaging.Client(
                    "mqtt.fi.mdp.edu.ar",
                    9001,
                    "myclientid_" + parseInt(Math.random() * 100, 10)
                );

                this.client.onConnectionLost = (response) => {
                    this.disconnected = true;
                    this.connectMQTT();
                };

                this.client.onMessageArrived = (message) => {
                    // Parses the JSON message.
                    let object = JSON.parse(message.payloadString);
                    // Checks if the message has the correct format.
                    if(object.hasOwnProperty('card_number') && object.card_number) {
                        // Gets the data associated with the card number.
                        axios.get('security/person/' + object.card_number)
                        .then(response => {
                            console.log(response.data);
                            
                            let person = new Person(response.data);
                            this.person = person;
                            this.people.push(person);

                            this.tab = this.people.length - 1;

                        })
                        .catch(error => {
                            console.log(error);
                        });
                    }
                };
            }
        },
        connectMQTT: function() {
            this.createMQTT();

            this.client.connect({
                timeout: 3,
                onSuccess: () => {
                    this.client.subscribe('testtopic/Usuario', {qos: 2});
                    this.disconnected = false;
                    this.connecting = false;
                    this.connecting_attempts = 0;
                },
                onFailure: (message) => {
                    this.disconnected = true;
                    this.connecting = false;
                    this.connecting_attempts++;
                    setTimeout(() => {
                        this.connectMQTT()
                    }, 60000);
                }
            });
        },
        forceReconnectionMQTT: function() {
            this.client = null;
            this.connectMQTT();
        },
        changeTab: function(key) {
            this.tab = key;
            this.person = this.people[key];
        },
        closeTab: function(key) {
            if(this.tab !== key && key < this.tab) {
                this.tab--;
            }
            else if (this.tab === key) {
                this.tab = (key - 1 < 0) ? 0 : key - 1;
            }
            this.people.splice(key, 1);
            this.person = this.people[this.tab];
        },
        newObservation: function() {
            axios.post(`/security/person/${this.person.values.id}/new-observation`, {text: this.person.textarea})
                .then(response => {
                    this.person.textarea = '';
                    this.person.addObservation(response.data);
                })
                .catch(error => console.log(error));
        },
        toggleObservation: function(observation) {
            observation.text = observation.collapsed ? observation.original : observation.abbreviated;
            observation.collapsed = !observation.collapsed;
        },
        toggleVehicleSearch: function() {
            this.person.vehicles_search = !this.person.vehicles_search;
            this.person.vehicles_search_input = "";
            if(this.person.vehicles_search) {
                this.$nextTick(() => this.$refs.vehicles_search.focus())
            }
        }
    }
}
</script>

