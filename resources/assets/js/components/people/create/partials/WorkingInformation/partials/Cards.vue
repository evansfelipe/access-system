<style lang="scss" scoped>
    .btn-link {
        padding: 0;
        text-decoration: none;
    }

    .btn-remove {
        position: absolute;
        right: 15px;
        z-index: 10;
        cursor: pointer;
        color: #CC0000;
    }

    .card-enter-active, .card-leave-active { transition: all .5s }
    .card-enter, .card-leave-to { opacity: 0 }
    .card-enter { transform: translateX(-10px) }
    .card-leave-active { max-height: 100vh }
    .card-leave-to {
        max-height: 0;
        transform: translateX(10px);
    }
</style>

<template>
    <div>
        <transition-group name="card" tag="div">
            <div v-for="card in cards" :key="`card-${card.key}`" class="form-row">
                <i class="btn-remove far fa-trash-alt" v-if="cards.length > 1" @click="removeCard(card)"></i>
                <!-- Card number -->
                <form-item col="col-md-12 col-lg-4" label="Número" :errors="errors[card.key] ? errors[card.key]['number'] : []">
                    <div class="col">
                        <input  type="text" name="number" class="form-control"
                                :value="card.number" @input="(e) => editCard(card, 'number', e.target.value)">
                    </div>
                </form-item>
                <!-- Card from -->
                <form-item col="col-md-6 col-lg-4" label="Valida desde" :errors="errors[card.key] ? errors[card.key]['from'] : []">
                    <div class="col">
                        <date-picker :value="card.from" @input="value => editCard(card, 'from', value)"/>
                    </div>
                </form-item>
                <!-- Card until -->
                <form-item col="col-md-6 col-lg-4" label="Valida hasta" :errors="errors[card.key] ? errors[card.key]['until'] : []">
                    <div class="col">
                        <date-picker :value="card.until" @input="value => editCard(card, 'until', value)"/>
                    </div>
                </form-item>
            </div>
        </transition-group>
        <div class="row">
            <div class="col text-right">
                <button class="btn btn-link" @click="addCard">
                    <i class="fas fa-plus"></i> Agregar tarjeta
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        cards: {
            type: Array,
            required: true
        },
        errors: {
            type: Array,
            required: true
        }
    },
    methods: {
        addCard: function() {
            this.$emit('add');
        },
        editCard: function(card, attribute, value) {
            this.$emit('edit', {card, attribute, value});
        },
        removeCard: function(card) {
            this.$emit('remove', card);
        }
    },
}
</script>
