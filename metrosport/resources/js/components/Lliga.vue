<template>
    <div>
        <h3 class="fw-bold title">{{ lliga.nom_lliga }}</h3>
        <div class="card">
            <div class="row p-5">
                <div class="col-md-6 div-imatge">
                    <img :src="'../assets/fotos_lliga/' + lliga.url_imagen" class="img-fluid rounded-start w-100" alt="Lliga">
                </div>
                <div class="col-md-6 div-info">
                    <div class="card-body p-0">
                        <div class="info p-4 ">
                            <div class="d-flex justify-content-between">
                                <p class="card-text"><strong>Ubicació:</strong></p>
                                <p class="card-text text-right">{{ lliga.lloc_lliga }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="card-text"><strong>Esport:</strong></p>
                                <p class="card-text text-right">{{ lliga.esport }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="card-text"><strong>Participants:</strong></p>
                                <p class="card-text text-right">{{ lliga.participants_actualment }} apuntats de {{ lliga.nro_equips_participants }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="card-text"><strong>Persones necessàries per equip:</strong></p>
                                <p class="card-text text-right">{{ lliga.persones_equip }}</p>
                            </div>
                            <div class="d-flex justify-content-between fw-bold">
                                <p class="card-text"><strong>Preu de l'entrada:</strong></p>
                                <p class="card-text text-right">{{ lliga.preu_entrada }}€</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <h4 class="mt-4">Equips inscrits</h4>
        <div class="row">
            <div class="col-md-4 mb-4" v-for="equip in lliga.equips" :key="equip.nom_equip">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ equip.nom_equip }}</h5>
                        <p class="card-text"><strong>Puntuación en liga:</strong> {{ equip.puntuacio_lliga }}</p>
                        <p class="card-text"><strong>Puntuación del equipo:</strong> {{ equip.puntuacio_equip }}</p>
                    </div>
                </div>
            </div>
        </div>
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
<style scoped>

    .info {
        height: 20rem;
        background-color: #ECF0F1;
        border-radius: 0.5rem;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .card {
        all: unset;
        display: block;
        background-color: transparent;
        text-decoration: none;
    }

    img {
        height: 20rem;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    p {
        font-size: 1.2rem;
    }

    .div-imatge, .div-info {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .div-info .text-right {
        text-align: right;
    }

    .title {
        font-size: 2rem;
    }

    @media (max-width: 768px) {
        .row{
            padding: 0;
        }

        .info {
            margin-top: 1rem;
            height: 13rem;
            min-width: 27rem;
            max-width: 27rem;
        }

        img {
            height: 13rem;
            min-width: 27rem;
            max-width: 27rem;
            object-fit: cover;
            padding: 0;
        }

        p {
            font-size: 1rem;
        }

        .title {
            font-size: 1.4rem;
        }
    }

    @media (max-width: 480px) {
        .row{
            padding: 0;
        }

        .info {
            margin-top: 1rem;
            height: 13rem;
            min-width: 20rem;
            max-width: 20rem;
        }

        img {
            height: 13rem;
            min-width: 20rem;
            max-width: 20rem;
            object-fit: cover;
            padding: 0;
        }

        p {
            font-size: 0.8rem;
        }

        .title {
            font-size: 1.2rem;
        }
    }

</style>
