<template>
    <div>
        <h3>{{ lliga.nom_lliga }}</h3>
        <h1>{{lliga.url_imagen}}</h1>
        <div class="card">
            <img :src="'../assets/fotos_lliga/' + lliga.url_imagen" class="card-img-top img-fixed" alt="Lliga">

            <div class="card-body">
                <h5 class="card-title">{{ lliga.nom_lliga }}</h5>
                <p class="card-text"><strong>Ubicación:</strong> {{ lliga.lloc_lliga }}</p>
                <p class="card-text"><strong>Participantes:</strong> {{ lliga.participants_actualment }} de {{ lliga.nro_equips_participants }}</p>
                <p class="card-text fw-bold"><strong>Precio de entrada:</strong> {{ lliga.preu_entrada }}€</p>
            </div>
        </div>

        <!-- Equipos inscritos -->
        <h4 class="mt-4">Equips inscrits</h4>
        <div class="row">
            <div class="col-md-4 mb-4" v-for="equipo in lliga.equips" :key="equipo.nom_equip">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ equipo.nom_equip }}</h5>
                        <p class="card-text"><strong>Puntuación en liga:</strong> {{ equipo.puntuacio_lliga }}</p>
                        <p class="card-text"><strong>Puntuación del equipo:</strong> {{ equipo.puntuacio_equip }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Partidos
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
        </div> -->

    </div>

</template>
<script>
export default {
    props: {
        id: Number,
    },
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
            axios.get('lligues/' + this.id)
                .then(response => {
                    console.log("Respuesta de la API:", response.data);
                    this.lliga = response.data;
                })
                .catch(error => {
                    console.error("Error obteniendo la liga:", error);
                });
        }
    }
}
</script>
<style>
</style>
