<style lang="scss" scoped>
    div.vehicle-card {
        background-color: whitesmoke;
        border: 1px solid rgb(215, 215, 215);
        height: 100%;
        border-radius: 4px;
        padding: 1em;
        padding-left: 1.5em;
        cursor: pointer;
        & > h6 { font-weight: normal }
        &:hover { background-color: rgb(230, 230, 230) }
        &.selected {
            & > h6 {
                font-weight: bold;
            }
            background-color: #4285F4;
            border-color: rgb(61, 123, 223);
            color: white;
            &:hover { background-color: rgb(61, 123, 223) }
        }
    }
</style>


<template>
    <div>
        <div v-if="list_filtered.length" class="form-row">
            <div class="col-1 d-flex jutify-content-center align-items-center">
                <button class="btn btn-link" @click="changeToPage(pagination.current - 1)" :disabled="(pagination.current === 0)? true : false">
                    <i class="fas fa-angle-double-left"></i>
                </button>
            </div>
            <div class="col-10">
                <div class="form-row">
                    <div v-for="(element, key) in pagination.page" :key="key" class="col-4">
                        <div :class="`vehicle-card ${element.id === selected ? 'selected' : ''}`" @click="pickElement(element.id)">
                            <i v-if="element.id === selected" class="fas fa-check-circle float-right"></i>
                            <i v-else class="far fa-circle float-right"></i>
                            <h6 v-for="(key_name,key) in keys" :key="key">{{ element[key_name] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1 d-flex jutify-content-center align-items-center">
                <button class="btn btn-link" @click="changeToPage(pagination.current + 1)" :disabled="(pagination.current === pagination.last-1)? true : false">
                    <i class="fas fa-angle-double-right"></i>
                </button>
            </div>
        </div>
        <h5 v-else class="text-center m-5">No se encontraron resultados</h5>
    </div>
</template>

<script>
export default {
    props: {
        selected: {
            type: Number,
            required: false,
            default: 0
        },
        list: {
            type: Array,
            required: true
        },
        keys: {
            type: Array,
            required: true
        },
        filter: {
            type: String,
            required: false
        }
    },
    data() {
        return {
            pagination: {
                quantity: 3,
                current: 0,
                last: Math.ceil((this.list.length) / 3),
                page: [],
            }
        };
    },
    computed: {
        list_filtered: function() {
            let search = this.filter;
            return this.list.filter((element) => {
                let ret = false;
                this.keys.forEach(key => {
                    ret = ret || element[key].toString().matches(search);
                });
                return ret;
            });
        }
    },
    watch: {
        list_filtered: {
            handler: function() {
                this.paginate(this.pagination.current);
            },
            deep: true
        }
    },
    mounted() {
        this.paginate(0);
    },
    methods: {
        paginate: function(page_number) {
            this.pagination.last = Math.ceil((this.list_filtered.length) / this.pagination.quantity);
            this.changeToPage(page_number);
        },
        changeToPage: function(number) {
            this.pagination.current = (number >= 0 && number <= this.pagination.last - 1)  ? number : (number < 0 ? 0 : this.pagination.last - 1);
            this.pagination.page = this.list_filtered.slice(this.pagination.current * this.pagination.quantity, this.pagination.current * this.pagination.quantity + this.pagination.quantity);
        },
        pickElement: function(id) {
            this.$emit('selection', id);
        }
    }
}
</script>
