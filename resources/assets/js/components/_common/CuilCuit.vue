<style lang="scss" scoped>
    input {
        display: inline-block;
        // Hides the arrows in the number input
        &::-webkit-outer-spin-button,
        &::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }
        &:focus {
            position: relative;
            z-index: 5;
        }
    }
    #cuil1 {
        width: 20%;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    #cuil2 {
        width: 60%;
        border-radius: 0;
        border-left: 0;
        border-right: 0;
    }
    #cuil3 {
        width: 20%;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>

<template>
    <div>
        <input  type="number" :value="val[0]" id="cuil1" class="form-control" @input="update" placeholder="xx" 
        ><input type="number" :value="val[1]" id="cuil2" class="form-control" @input="update" placeholder="xxxxxxxx" 
        ><input type="number" :value="val[2]" id="cuil3" class="form-control" @input="update" placeholder="x" >
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: String,
            required: false,
        }
    },
    computed: {
        val: function() {
            return this.value.split('-');
        }
    },
    methods: {
        update: function() {
            let val1 = document.getElementById("cuil1").value;
            let val2 = document.getElementById("cuil2").value;
            let val3 = document.getElementById("cuil3").value;
            if( val1 === '' && val2 === '' && val3 === '' )
                this.$emit('input', '');
            else
                this.$emit('input', val1 + '-' + val2 + '-' + val3);
        }
    },
}
</script>

