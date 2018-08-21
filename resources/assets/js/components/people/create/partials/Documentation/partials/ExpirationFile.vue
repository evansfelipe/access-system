<template>
    <div class="form-row">
        <form-item col="col" :label="label" :errors="errors">
            <div class="col-8">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFileLang" lang="es"  @change="fileUploaded">
                    <label class="custom-file-label" for="customFileLang">
                        {{ file.selected ? file.name : 'Seleccionar Archivo' }}
                    </label>
                </div>
            </div>
            <div class="col-4">
                <input type="date" name="last_name" class="form-control">
            </div>
        </form-item>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            required: true
        },
        errors: {
            type: Array,
            required: false,
            default: () => []
        }
    },
    data() {
        return {
            file: {
                selected: false,
                name: '',
            }
        }
    },
    methods: {
        fileUploaded: function(e) {
            let file;
            console.log(e);
            
            if(e.target.files.length > 0) {
                this.file.selected = true;
                this.file.name = e.target.files[0].name;
            }
            else {
                console.log('vaciando archivo');
                
                this.file = {
                    selected: false,
                    name: ''
                }
            }
        }
    }
}
</script>

