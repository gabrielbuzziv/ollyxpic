<template>
    <div>
        <div class="worldmap" :id="id">


        </div>
        <pre class="margin-top-20">
            Floor: {{ floor }}
            Zoom: {{ zoom }}
        </pre>
    </div>
</template>

<script type="text/babel">
    import Leaflet from 'leaflet'

    export default {
        props: {
            id: String
        },

        data () {
            return {
                map: null,
                zoom: 0,
                floor: 0
            }
        },

        methods: {
            setupMap () {
                const $this = this
                this.map = Leaflet.map(this.id, { crs: Leaflet.CRS.Simple })
                this.map.addControl(new (Leaflet.Control.extend({
                    options: {
                        position: 'topleft'
                    },

                    onAdd (map) {
                        const container = Leaflet.DomUtil.create('div')

                        container.style.background = 'white'
                        container.style.width = '30px';
                        container.style.height = '30px';

                        container.onclick = () => {
                            $this.mapOverlay(7)
                        }

                        return container
                    }
                })))
            },

            mapOverlay (floor) {
                const bounds = [[0, 0], [1024, 1024]]

                Leaflet.imageOverlay(`/src/assets/images/map/${floor}.gif`, bounds).addTo(this.map)
                this.map.fitBounds(bounds)
            },

            setMarker (x, y) {
                Leaflet.marker(Leaflet.latLng([x, y])).addTo(this.map)
            },

            setView (x, y) {
                this.map.setView([x, y], this.zoom)
            }
        },

        mounted () {
            this.setupMap()

            setTimeout(() => {
                this.mapOverlay(this.floor)
                this.setMarker(40, 50)
                this.setView(30, 40)
            }, 1)

            this.map.on('zoomend', (e) => {
                this.zoom = e.target._animateToZoom
            })
        }
    }
</script>


<!--<template>-->
    <!--<div class="worldmap">-->
        <!--<div class="zoom">-->
            <!--<button class="btn btn-default" @click="zoomIn">-->
                <!--<i class="mdi mdi-plus"></i>-->
            <!--</button>-->

            <!--<button class="btn btn-default" @click="zoomOut">-->
                <!--<i class="mdi mdi-minus"></i>-->
            <!--</button>-->

            <!--<button class="btn btn-default" @click="test">-->
                <!--<i class="mdi mdi-map-marker"></i>-->
            <!--</button>-->
        <!--</div>-->
        <!--<div class="marker">-->
            <!--<i class="mdi mdi-map-marker"></i>-->
        <!--</div>-->
        <!--<div class="wrap" ref="wrapper">-->
            <!--<img :src="image_path('map', z)" :style="style" ref="map">-->
        <!--</div>-->
    <!--</div>-->
<!--</template>-->

<!--<script type="text/babel">-->
    <!--export default {-->
        <!--props: {-->
            <!--x: String,-->
            <!--y: String,-->
            <!--z: String-->
        <!--},-->

        <!--data () {-->
            <!--return {-->
                <!--posX: 0,-->
                <!--posY: 0,-->
                <!--scale: 1-->
            <!--}-->
        <!--},-->

        <!--computed: {-->
            <!--style () {-->
                <!--this.top = parseInt(this.y) * this.scale - 100-->
                <!--this.left = parseInt(this.x) * this.scale - 100-->

<!--//                return `top:-${this.top}px;left:-${this.left}px;transform:scale(${this.scale})`-->
            <!--}-->
        <!--},-->

        <!--methods: {-->
            <!--zoomIn () {-->
                <!--if (this.scale < 2) {-->
                    <!--this.scale = this.scale + 0.3;-->
                <!--}-->
            <!--},-->

            <!--zoomOut () {-->
                <!--if (this.scale > 0.4) {-->
                    <!--this.scale = this.scale - 0.3;-->
                <!--}-->
            <!--},-->

            <!--test () {-->
                <!--const wrapper = this.$refs.wrapper-->
                <!--const map = this.$refs.map-->

                <!--console.log(wrapper.getBoundingClientRect())-->
                <!--console.log(map.getBoundingClientRect())-->
            <!--}-->
        <!--},-->

        <!--mounted () {-->

            <!--var curYPos, curXPos, curDown;-->

            <!--window.addEventListener('mousemove', function(e){-->
                <!--if(curDown){-->
                    <!--window.scrollTo(document.body.scrollLeft + (curXPos - e.pageX), document.body.scrollTop + (curYPos - e.pageY));-->
                <!--}-->
            <!--});-->

            <!--window.addEventListener('mousedown', function(e){-->
                <!--curYPos = e.pageY;-->
                <!--curXPos = e.pageX;-->
                <!--curDown = true;-->
            <!--});-->

            <!--window.addEventListener('mouseup', function(e){-->
                <!--curDown = false;-->
            <!--});-->

<!--//            const wrapper = this.$refs.wrapper-->
<!--//            const map = this.$refs.map-->
<!--//-->
<!--//            map.style.top = `-220px`;-->
<!--//            map.style.left = `-220px`;-->
        <!--}-->
    <!--}-->
<!--</script>-->