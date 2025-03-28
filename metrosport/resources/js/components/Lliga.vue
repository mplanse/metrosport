<template>
    <div>
        <h3 class="fw-bold title">{{ lliga.nom_lliga }}</h3>
        <div class="card">
            <div class="row p-5">
                <div class="col-md-6 div-imatge">
                    <img :src="`../storage/${lliga.url_imagen}`" class="img-fluid rounded-start w-100" alt="Lliga">
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

        <h4 class=" equips-text">Equips inscrits</h4>
        <div class="row info-lliga-row">
            <div class="col-12 mb-4" v-for="equip in lliga.equips" :key="equip.nom_equip">
                <div class="card">
                    <div class="card-body full-width">
                        <p v-if="equip.nom_equip !== '0'" class="card-title"><strong>{{ equip.nom_equip }}</strong></p>
                        <p v-else class="card-title"><strong>Nom no disponible</strong></p>
                        <p v-if="equip.ubicacio_camp" class="card-text">{{ equip.ubicacio_camp.nom_ubicacio }} </p>
                        <p v-else class="card-text"> Ubicació no disponible </p>
                        <p class="card-text"> Puntuació de l'equip:{{ equip.puntuacio_equip }}</p>
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

    .equips-text {
        margin-bottom: 30px;
    }

    .full-width {
        text-align: center;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 0;
    }

    .card-text, .card-title{
        margin: 0;
    }

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

    .info-lliga-row{
        margin: 0;
    }

    @media (max-width: 999px) {
        .equips-text{
            margin-bottom: 10px;
        }

       .full-width {
            all: unset !important;
        }

        .info-lliga-row .col-12 {
            width: calc(50% - 20px);
        }

        .info-lliga-row{
            padding-left: 30px;
            padding-top: 15px;
        }
    }


    @media (max-width: 768px) {
        .full-width {
            all: unset !important;
        }

        .row{
            padding: 0;
        }

        .info-lliga-row{
            padding-left: 30px;
            padding-top: 15px;
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

        .equips-text{
            margin-bottom: 10px;
        }
    }

    @media (max-width: 550px) {
        .info-lliga-row .col-12 {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .full-width {
            all: unset !important;
        }
        .row{
            padding: 0;
        }

        .info-lliga-row{
            padding-left: 30px;
            padding-top: 15px;
        }

        .info {
            margin-top: 1rem;
            height: 10rem;
            min-width: 20rem;
            max-width: 20rem;
        }

        img {
            height: 10rem;
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

        .equips-text{
            margin-bottom: 10px;
        }

        .info-lliga-row .col-12 {
            width: 100%;
        }
    }

</style>
