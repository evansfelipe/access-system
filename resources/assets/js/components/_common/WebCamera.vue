<style lang="scss" scoped>
    div.camera-modal {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        z-index: 15;
        top: 0; bottom: 0; left: 0; right: 0;
        background-color: rgba(0, 0, 0, .4);
        visibility: visible;
        &.closed {
            visibility: hidden;
            height: 0;
        }
        & > div.container {
            width: 40%;
        }
    }
</style>


<template>
    <div class="text-center">
        <div class="row">
            <div class="offset-1 col-10">
                <img class="img-fluid rounded-circle shadow" :src="dataURI || '/pictures/no-image.jpg'"/>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col">
                <button class="btn btn-link" @click="openCamera">
                    <i class="fas fa-camera" title="Tomar foto"></i>
                </button>
                <span style="color: grey">|</span>
                <button class="btn btn-link" @click="openCamera">
                    <i class="fas fa-upload" title="Subir foto"></i>
                </button>
            </div>
        </div>

        <div :class="'camera-modal' + (camera_opened ? '' : ' closed')">
            <div class="container">
                <div class="card card-default">
                    <div class="card-body">
                        <i class="fas fa-times float-right cursor-pointer" @click="cancel"></i>
                        <div id="camera" class="d-inline-block"></div>
                        <hr>
                        <button class="btn btn-sm btn-outline-primary" @click="takePicture">Tomar foto</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            camera_opened: false,
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
        cancel: function() {
            this.camera_opened = false;
        }
    },
    watch: {
        camera_opened: function() {
            if(this.camera_opened) {
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
            }
            else {
                Webcam.reset();
            }
        }
    }
}
</script>

