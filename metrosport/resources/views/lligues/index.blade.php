@extends('layouts.nav')

@section('content')
<div id="app" class="container mt-4">
    <p class="fs-5 ">Creiem que aquestes lligues podrien interessar-te:</p>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4" v-for="lliga in lligues" :key="lliga.id_lliga">
            <a class="a-lligues" :href="'{{ route('lliga.show', '') }}' + '/' + lliga.id_lliga">
                <div class="card h-100 shadow-sm border-0">
                    <img :src="'assets/fotos_lliga/' + lliga.url_imagen" class="card-img-top img-fixed" alt="Lliga">

                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title flex-grow-1 text-truncate">@{{ lliga.nom_lliga }}</h5>
                            <p class="card-text fw-bold">@{{ lliga.preu_entrada }}€</p>
                        </div>
                        <p class="card-text text-muted">@{{ lliga.lloc_lliga }}</p>
                        <!-- <p class="card-text">
                        Participants: @{{ lliga.participants_actualment }} de @{{ lliga.nro_equips_participants }} equips inscrits
                        </p> -->
                    </div>
                </div>
            </a>
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
    axios.get("{{ route('lligues.api') }}", { withCredentials: true })
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
