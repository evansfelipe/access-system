<style lang="scss" scoped>
    $z-index: 1000;
    div.stream-opened {
        // Display
        display: inline-flex;
        align-items: center;
        justify-content: center;
        // Position
        position: fixed;
        bottom: 1em; right: 1em;
        z-index: $z-index;
        // Size
        min-width: 300px; width: 20vw;
        min-height: 150px; height: 20vh;
        // Style
        border-radius: 5px;
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        transition: all .5s;

        &.fullscreen {
            bottom: 0; right: 0;
            width: calc(100%);
            height: calc(100%);
            padding: 1em;
            border-radius: 0;
        }

        &.playing {
            display: inline-block;
            & > video {
                cursor: pointer;
                border-radius: 5px;
                width: 100%; height: 100%;
                object-fit: cover;
            }

            & > div.options {
                position: absolute;
                top: 0; right: 0; bottom: 0; left: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                visibility: hidden;
                transition: background-color .5s;
                border-radius: 5px;
                background-color: rgba(0, 0, 0, 0.5);
                & > button.btn-link > * { color: white }
            }

            &:hover > div.options { visibility: visible }
        }

    }

    div.stream-closed {
        // Position
        position: fixed;
        bottom: 0; right: 1em;
        z-index: $z-index - 1;
        // Style
        border: 1px solid rgb(222,222,222);
        border-bottom: 0;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        background-color: white;
        & > .btn-link > * { color: black; }
    }

    .camera-enter, .camera-leave-to { transform: translate(0, 50vh); }
    .camera-enter-active, .camera-leave-active { transition: left 1s }
    .button-enter, .button-leave-to { transform: translate(0, 50vh) }
    .button-enter-active, .button-leave-active { transition: transform .5s }
</style>


<template>
    <div class="d-inline">
        <transition name="camera">
            <div v-show="!minimized" :class="`shadow stream-opened ${fullscreen ? 'fullscreen' : ''} ${!fatal_error && !loading ? 'playing' : ''}`">
                <!-- Loading -->
                <i v-if="loading" class="fas fa-circle-notch fa-spin fa-3x"></i>
                <!-- Fatal error -->
                <div v-else-if="fatal_error" class="text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                    Ocurrió un error al reproducir el video. <br>
                    <strong class="cursor-pointer" @click="loadStreaming">Recargar</strong> o 
                    <strong class="cursor-pointer" @click="handleMinimize">Minimizar</strong>
                </div>
                <!-- Streaming -->
                <video v-show="!loading && !fatal_error" :id="this._uid" muted="muted" @click="handleFullscreen"></video>
                <div v-show="!loading && !fatal_error && !fullscreen" class="options">
                    <button class="btn btn-link" @click="loadStreaming">
                        <i class="fas fa-sync fa-3x"></i>
                    </button>
                    <button class="btn btn-link" @click="handleFullscreen">
                        <i class="fas fa-expand fa-3x"></i>
                    </button>
                    <button class="btn btn-link" @click="handleMinimize">
                        <i class="fas fa-minus fa-3x"></i>
                    </button>
                </div>
            </div>
        </transition>
        <transition name="button">
            <div v-show="minimized" class="stream-closed">
                <button class="btn btn-link" @click="handleMinimize">
                    <i class="fas fa-video fa-lg"></i>
                </button>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        source: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            hls: null,
            loading: false,
            minimized: false,
            fullscreen: false,
            fatal_error: false,
        };
    },
    mounted() {
        this.loadStreaming();
    },
    methods: {
        loadStreaming: function() {
            if(this.hsl) this.hls.destroy()
            this.loading = true;
            this.fatal_error = false;
            var video = document.getElementById(this._uid);
            if(Hls.isSupported()) {
                this.hls = new Hls();
                this.hls.loadSource(this.source);
                this.hls.attachMedia(video);
                this.hls.on(Hls.Events.MANIFEST_PARSED, () => video.play());


                $(`#${this._uid}`).on('loadstart', () => this.loading = true);
                $(`#${this._uid}`).on('canplay', () => this.loading = false);
                this.hls.on(Hls.Events.ERROR, (e, data) =>  {
                    console.log(e, data);
                    this.fatal_error = data.fatal;
                    this.loading = false;
                })


            }
            else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = '/video/01/playlist.m3u8';
                video.addEventListener('loadedmetadata',function() {
                video.play();
                });
            }
        },
        handleFullscreen: function() {
            this.fullscreen = !this.fullscreen;
        },
        handleMinimize: function() {
            this.minimized = !this.minimized;
        }
    }
}
</script>
