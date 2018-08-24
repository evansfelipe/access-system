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
            <div v-for="(card, key) in cards" :key="`card-${card.key}`" class="row">
                <i class="btn-remove far fa-trash-alt" v-if="cards.length > 1" @click="removeCard(card)"></i>
                <!-- Card number -->
                <form-item label="Número de la tarjeta" :errors="errors[card.key] ? errors[card.key]['number'] : []">
                    <div class="col">
                        <input  type="text" name="number" class="form-control"
                                placeholder="Número de tarjeta" :value="card.number" @input="(e) => editCard(card, 'number', e.target.value)">
                    </div>
                </form-item>
                <!-- Card from -->
                <form-item label="Valida desde" :errors="errors[card.key] ? errors[card.key]['from'] : []">
                    <div class="col">
                        <input type="date" name="from" class="form-control" :value="card.from" @input="(e) => editCard(card, 'from', e.target.value)">
                    </div>
                </form-item>
                <!-- Card until -->
                <form-item label="Valida hasta" :errors="errors[card.key] ? errors[card.key]['until'] : []">
                    <div class="col">
                        <input type="date" name="until" class="form-control" :value="card.until" @input="(e) => editCard(card, 'until', e.target.value)">
                    </div>
                </form-item>
                <div v-if="key < cards.length - 1" class="col-12">
                    <hr>
                </div>
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
            let data = {
                number: attribute === 'number'  ? value : card.number,
                from:   attribute === 'from'    ? value : card.from,
                until:  attribute === 'until'   ? value : card.until
            };
            this.$emit('edit', {card, data});
        },
        removeCard: function(card) {
            this.$emit('remove', card);
        }
    },
}
</script>
