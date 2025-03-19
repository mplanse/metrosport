@extends('layouts.nav')

@section('content')
<div id="app" class="container mt-4">
    <h3>Lligues disponibles</h3>
    <div class="row">
        <div class="col-md-4 mb-4" v-for="lliga in lligues" :key="lliga.id_lliga">
            <div class="card">
            <img :src="'assets/fotos_lliga/' + lliga.url_imagen" class="card-img-top" alt="Lliga">


                <div class="card-body">
                    <h5 class="card-title">@{{ lliga.nom_lliga }}</h5>
                    <p class="card-text">@{{ lliga.lloc_lliga }}</p>
                    <p class="card-text">Participants: @{{ lliga.nro_equips_participants }}</p>
                    <p class="card-text fw-bold">@{{ lliga.preu_entrada }}€</p>
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
            lligues: []
        };
    },
    mounted() {
        this.fetchLligues();
    },
    methods: {
        fetchLligues() {
            axios.get("{{ route('lligues.api') }}")
                .then(response => {
                    this.lligues = response.data;
                })
                .catch(error => {
                    console.error("Error obteniendo las ligas:", error);
                });
        }
    }
});
app.mount("#app");
</script>
@endsection
