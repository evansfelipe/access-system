<template>
    <div class="text-center">
        <!-- Photo -->
        <div class="row">
            <div class="offset-1 col-10">
                <img class="img-fluid rounded-circle shadow-sm" :src="dataURI || img"/>
            </div>
        </div>
        <!-- Buttons -->
        <div class="row mt-2">
            <div class="col">
                <button class="btn btn-link" title="Tomar foto" @click="openCamera">
                    <i class="fas fa-camera"></i>
                </button>
                <span style="color: grey">|</span>
                <button class="btn btn-link" title="Subir foto" @click="uploadFile">
                    <i class="fas fa-upload"></i>
                    <input type="file" @change="fileUploaded" style="display:none" ref="inputFile">
                </button>
            </div>
        </div>
        <!-- Camera modal -->
        <modal-wrapper :visible="camera_opened" @closed="cancel">
            <loading-cover v-if="!camera_ready" message="Iniciando camara..."/>
            <div id="camera" class="d-inline-block"></div>
            <hr>
            <button class="btn btn-sm btn-outline-primary" @click="takePicture">Tomar foto</button>
        </modal-wrapper>
    </div>
</template>

<script>
export default {
    props: {
        img: {
            type: String,
            required: false,
            default: '/pictures/no-image.jpg'
        }
    },
    data() {
        return {
            camera_opened: false,
            camera_ready:  false,
            dataURI: ''
        };
    },
    methods: {
        dataURItoBlob: function(dataURI) {
            // convert base64/URLEncoded data component to raw binary data held in a string
            var byteString;
            if (dataURI.split(',')[0].indexOf('base64') >= 0)
                byteString = atob(dataURI.split(',')[1]);
            else
                byteString = unescape(dataURI.split(',')[1]);

            // separate out the mime component
            var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

            // write the bytes of the string to a typed array
            var ia = new Uint8Array(byteString.length);
            for (var i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }

            return new Blob([ia], {type:mimeString});
        },
        openCamera: function() {
            this.camera_opened = true;
        },
        takePicture: function() {
            this.camera_opened = false;
            Webcam.snap((dataURI) => {
                this.dataURI = dataURI;
                this.$emit('pictureTaken', this.dataURItoBlob(dataURI));
            });
        },
        uploadFile: function () {
            this.$refs.inputFile.click();
        },
        fileUploaded: function(e) {
            let file =  e.target.files[0];
            this.$emit('pictureTaken',file);
            var reader  = new FileReader();

            reader.addEventListener("load", () => {
                this.dataURI = reader.result;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        },
        cancel: function() {
            this.camera_opened = false;
        }
    },
    watch: {
        camera_opened: function() {
            if(this.camera_opened) {
                this.camera_ready = false;
                this.$nextTick(() => {
                    Webcam.set({
                        width: 320 * 1.5,
                        height: 240 * 1.5,
                        dest_width: 640,
                        dest_height: 480,
                        crop_width: 480,
                        crop_height: 480,
                        image_format: 'jpeg',
                        jpeg_quality: 90,
                        force_flash: false
                    });
                    Webcam.attach('#camera');
                    Webcam.on('live', () => this.camera_ready = true);
                });
            }
            else {
                Webcam.reset();
            }
        }
    }
}
</script>

