<style lang="scss" scoped>
ul.steps-vertical {
  list-style-type: none;
  counter-reset: steps;
  margin: 0;
  padding: 0;
  & > li {
    padding: 0 0 20px 50px;
    position: relative;
    margin: 0;

    & > div.content {
        margin-top: .8em;
        border: 1px solid rgb(222,222,222);
        padding: 1em;
        border-radius: 5px;
    }
  }
}

ul.steps-vertical li.completed:after {
  background-color: #00695c;
}

ul.steps-vertical li.active:after {
    background-color: #3F729B;
}

ul.steps-vertical li:after {
    position: absolute;
    top: 0;
    left: 0;
    content: counter(steps);
    counter-increment: steps;
    background-color: #9e9e9e;
    border-radius: 50%;
    display: inline-block;
    height: 1.5em;
    width: 1.5em;
    text-align: center;
    color: white;
    font-weight: bold;
    outline: 5px solid white;
}
ul.steps-vertical li:before {
  position: absolute;
  left: 0.75em;
  top: 0;
  content: "";
  height: 100%;
  width: 0;
  border-left: 1px solid rgb(202, 202, 202);
}
ul.steps-vertical li:last-of-type:before {
  border: none;
}

td.small {
    padding-right: 0.5em;
    font-size: 75%;
}

td.strong {
    font-weight: bold;
}

div.vehicle-card {
    background-color: whitesmoke;
    border: 1px solid rgb(215, 215, 215);
    border-radius: 4px;
    padding: 1em;
    padding-left: 1.5em;
    cursor: pointer;
    & > h6 {
        font-weight: normal;
    }
    &:hover {
        background-color: rgb(230, 230, 230);
    }
    &.selected {
        & > h6 {
            font-weight: bold;
        }
        background-color: #4285F4;
        border-color: rgb(61, 123, 223);
        color: white;
        &:hover {
            background-color: rgb(61, 123, 223);
        }
    }
}

input.vehicles-search {
    height: 20px;
    border: 0;
    border-bottom: 1px solid rgb(222,222,222);
    outline: none;
    margin-right: 3px;
}

</style>

<template>
    <div class="container">
        <loading-cover v-if="connecting" message="Cargando..."/>
        <ul class="nav nav-tabs">
            <tab-item v-for="(p,key) in people" :key="key" :active="tab === key" @click.native="changeTab(key)">
                <div class="d-inline-block mr-2" @click="closeTab(key)"><i class="fas fa-times"></i></div>
                {{ p.values.full_name }}
            </tab-item>
        </ul>
        <div class="card card-default borderless-top-card">
            <div class="card-body" style="padding: 2em 3em;">

                <template v-if="person">
                    <ul class="steps-vertical">
                        <li :class="person.step === 0 ? 'active' : (person.step > 0 ? 'completed' : '')">
                            Reconocimiento
                            <div v-if="person.step === 0" class="content">
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
                            <div v-if="person.step === 1 && person.values.vehicles.length" class="d-inline-block float-right">
                                <input v-if="person.vehicles_search" v-model="person.vehicles_search_input" type="text" placeholder="Búsqueda" class="vehicles-search" ref="vehicles_search">
                                <i class="fas fa-search cursor-pointer" @click="toggleVehicleSearch"></i>
                            </div>
                            <div v-if="person.step === 1" class="content">
                                <div v-if="vehicles_filtered.length" class="row">
                                    <div v-for="(vehicle, key) in vehicles_filtered" :key="key" class="col-3">
                                        <div :class="`vehicle-card ${vehicle.id === person.vehicle ? 'selected' : ''}`" @click="person.selectVehicle(vehicle.id)">
                                            <i v-if="vehicle.id === person.vehicle" class="fas fa-check-circle float-right"></i>
                                            <i v-else class="far fa-circle float-right"></i>
                                            <h6>{{ vehicle.type }}</h6>
                                            <h6>{{ vehicle.plate }}</h6>
                                            <h6>{{ vehicle.brand + ', ' + vehicle.model }}</h6>
                                            <h6>{{ vehicle.year }}</h6>
                                            <h6>{{ vehicle.colour }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <h4 v-else class="text-center">No hay nada para mostrar</h4>
                            </div>
                        </li>
                        <li :class="person.step === 2 ? 'active' : (person.step > 2 ? 'completed' : '')">
                            Observaciones
                            <div v-if="person.step === 2" class="content">
                                <div class="row">
                                    <div class="col-4">
                                        <textarea class="form-control" rows="4" placeholder="Nueva observación" v-model="person.textarea"></textarea>
                                        <button class="btn btn-outline-success btn-block mt-1">Enviar</button>
                                    </div>
                                    <div class="col-8">
                                        <custom-table :columns="observations_columns" :rows="person.values.observations" @rowclicked="toggleObservation"/>
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
        this.observations = [];
    }

    addObservation(observation) {
        this.observations.push(observation);
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
    data() {
        return {
            connecting: false,
            client: null,
            people: [],
            person: null,
            tab: 0,

            observations_columns: [
                {name: 'user', text: 'Usuario'},
                {name: 'text', text: 'Texto'}
            ]
        };
    },
    computed: {
        vehicles_filtered: function() {
            let search = this.person.vehicles_search_input;
            return this.person.values.vehicles.filter(({plate, brand, model, type, colour, year}) => {
                return  plate.matches(search) || brand.matches(search) || model.matches(search) ||
                        type.matches(search) || colour.matches(search) || year.toString().matches(search);
            });
        }
    },
    mounted() {
        this.create();
    },
    methods: {
        create: function() {
            this.client = new Messaging.Client(
                "mqtt.fi.mdp.edu.ar",
                9001,
                "myclientid_" + parseInt(Math.random() * 100, 10)
            );
            this.client.onConnectionLost = (response) => {};


            this.client.onMessageArrived = (message) => {
                // Parses the JSON message.
                let object = JSON.parse(message.payloadString);
                // Checks if the message has the correct format.
                if(object.hasOwnProperty('card_number') && object.card_number) {
                    // Gets the data associated with the card number.
                    axios.get('security/person/' + object.card_number)
                    .then(response => {
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

            this.connecting = true;

            this.client.connect({
                timeout: 3,
                //Gets Called if the connection has sucessfully been established
                onSuccess: () => {
                    this.client.subscribe('testtopic/Usuario', {qos: 2});
                    this.connecting = false;
                },
                //Gets Called if the connection could not be established
                onFailure: (message) => {
                    this.connecting = false;
                }
            });
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

