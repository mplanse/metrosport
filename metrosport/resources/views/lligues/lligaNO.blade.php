@extends('layouts.nav')

@section('content')
<!-- Agregamos data-url con la ruta correcta para la API -->
<div id="app"
    data-url="{{ route('lligues.info', ['id' => '__ID__']) }}"
    class="container mt-4">

    <h3>@{{ lliga.nom_lliga }}</h3>
<h1>@{{lliga.url_imagen}}</h1>
    <div class="card">
        <img :src="'assets/fotos_lliga/' + lliga.url_imagen" class="card-img-top img-fixed" alt="Lliga">

        <div class="card-body">
            <h5 class="card-title">@{{ lliga.nom_lliga }}</h5>
            <p class="card-text"><strong>Ubicación:</strong> @{{ lliga.lloc_lliga }}</p>
            <p class="card-text"><strong>Participantes:</strong> @{{ lliga.participants_actualment }} de @{{ lliga.nro_equips_participants }}</p>
            <p class="card-text fw-bold"><strong>Precio de entrada:</strong> @{{ lliga.preu_entrada }}€</p>
        </div>
    </div>

    <!-- Equipos inscritos -->
    <h4 class="mt-4">Equips inscrits</h4>
    <div class="row">
        <div class="col-md-4 mb-4" v-for="equipo in lliga.equips" :key="equipo.nom_equip">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@{{ equipo.nom_equip }}</h5>
                    <p class="card-text"><strong>Puntuación en liga:</strong> @{{ equipo.puntuacio_lliga }}</p>
                    <p class="card-text"><strong>Puntuación del equipo:</strong> @{{ equipo.puntuacio_equip }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Partidos -->
    <h4 class="mt-4">Partits</h4>
    <div class="row">
        <div class="col-md-4 mb-4" v-for="partit in lliga.partits" :key="partit.id_partit">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Partit @{{ partit.id_partit }}</h5>
                    <p class="card-text" v-if="partit.estat"><strong>Estado:</strong> @{{ partit.estat.nom_estat }}</p>
                    <p class="card-text" v-if="partit.ubicacio"><strong>Ubicación:</strong> @{{ partit.ubicacio.nom_ubicacio }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
const app = Vue.createApp({
    data() {
        return {
            lliga: {}
        };
    },
    mounted() {
        this.fetchLliga();
    },
    methods: {
        fetchLliga() {
            let url = document.getElementById('app').getAttribute('data-url');

            const lligaId = window.location.pathname.split('/').pop();
            url = url.replace('__ID__', lligaId);

            console.log("URL final de la API:", url);

            axios.get(url)
                .then(response => {
                    console.log("Respuesta de la API:", response.data);
                    this.lliga = response.data;
                })
                .catch(error => {
                    console.error("Error obteniendo la liga:", error);
                });
        }
    }
});

app.mount("#app");
</script>
@endsection
