<template>
    <div>
        <!-- Mensaje de compatibilidad -->
        <div v-if="compatibilidadProp && !lliga.es_mi_lliga" class="alert"
            :class="compatibilidadProp.compatible ? 'alert-success' : 'alert-warning'">
            {{ compatibilidadProp.mensaje }}
        </div>

        <div v-if="mensaje" :class="['alert', tipoMensaje === 'success' ? 'alert-success' : 'alert-danger']"
            class="mt-3">
            {{ mensaje }}
        </div>

        <h3 class="fw-bold title">{{ lliga.nom_lliga }}</h3>
        <div class="card" v-if="lliga.nom_lliga">
            <div class="row p-5">
                <div class="col-md-6 div-imatge">
                    <img v-if="lliga.url_imagen" :src="`../storage/${lliga.url_imagen}`"
                        class="img-fluid rounded-start w-100" alt="Lliga">
                    <div v-else
                        style="height: 20rem; background: #f2f2f2; display: flex; align-items: center; justify-content: center;">
                        Sin imagen
                    </div>
                </div>
                <div class="col-md-6 div-info">
                    <div class="card-body p-0">
                        <div class="info p-4">
                            <div class="d-flex justify-content-between">
                                <p class="card-text"><strong>Ubicació:</strong></p>
                                <p class="card-text text-right">{{ lliga.lloc_lliga || 'No disponible' }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="card-text"><strong>Esport:</strong></p>
                                <p class="card-text text-right">{{ lliga.esport || 'No disponible' }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="card-text"><strong>Participants:</strong></p>
                                <p class="card-text text-right">{{ lliga.participants_actualment || 0 }} apuntats de {{
                                    lliga.nro_equips_participants || 0 }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="card-text"><strong>Persones necessàries per equip:</strong></p>
                                <p class="card-text text-right">{{ lliga.persones_equip || 0 }}</p>
                            </div>
                            <div class="d-flex justify-content-between fw-bold">
                                <p class="card-text"><strong>Preu de l'entrada:</strong></p>
                                <p class="card-text text-right">{{ lliga.preu_entrada || 0 }}€</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="equips-text">Equips inscrits</h4>
        <div class="row info-lliga-row" v-if="lliga.equips">
            <div v-if="lliga.equips && lliga.equips.length > 0">
                <div class="col-12 mb-4" v-for="equip in lliga.equips" :key="equip.nom_equip">
                    <div class="card">
                        <div class="card-body full-width">
                            <p v-if="equip.nom_equip !== '0'" class="card-title"><strong>{{ equip.nom_equip }}</strong>
                            </p>
                            <p v-else class="card-title"><strong>Nom no disponible</strong></p>
                            <p v-if="equip.ubicacio_camp" class="card-text">{{ equip.ubicacio_camp.nom_ubicacio }}</p>
                            <p v-else class="card-text">Ubicació no disponible</p>
                            <p class="card-text">Puntuació de l'equip: {{ equip.puntuacio_equip }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <p>Encara no hi ha cap equip inscrit</p>
            </div>
        </div>

        <!-- Ocultar botones y mensajes si es mi liga -->
        <div v-if="!lliga.es_mi_lliga">
            <h4 class="disponibilitat-text mt-4">Disponibilitat horària</h4>
            <div class="card disponibilitat-card p-3">
                <div v-if="typeof disponibilitat === 'string' && disponibilitat.length > 0">
                    <div v-for="(line, index) in disponibilitat.split('\n')" :key="index">
                        {{ line }}
                    </div>
                </div>

                <div v-else>
                    Carregant disponibilitat horària...
                </div>
            </div>

            <div class="justify-content-center d-flex align-items-center flex-column text-center">

                <div v-if="isLligaCompleta" class="alert alert-warning mb-3 w-100">
                    Aquesta lliga ja està completa. No es poden inscriure més equips.
                </div>

                <div v-if="lliga.ya_en_otra_liga" class="alert alert-warning mb-3 w-100">
                    Ja estàs inscrit en una altra lliga. No pots participar en múltiples lligues simultàniament.
                </div>

                <form
                    v-if="!lliga.usuario_inscrito && !lliga.ya_en_otra_liga && compatibilidadProp && compatibilidadProp.compatible && !isLligaCompleta"
                    @submit.prevent="submitForm" class="w-100 d-flex justify-content-center">
                    <button type="submit" class="btn btn-inscriuret">Inscriure'm</button>
                </form>

                <div v-else-if="!lliga.usuario_inscrito && (lliga.ya_en_otra_liga || (compatibilidadProp && !compatibilidadProp.compatible) || isLligaCompleta)"
                    class="w-100 d-flex justify-content-center">
                    <button class="btn btn-inscriuret-disabled" disabled>
                        {{
                            isLligaCompleta ? 'Lliga completa' :
                                lliga.ya_en_otra_liga ? 'Ja estàs en una altra lliga' :
                                    'No et pots inscriure'
                        }}
                    </button>
                </div>

                <p v-else-if="lliga.usuario_inscrito" class="ya-inscrito mt-3">Ja estàs inscrit en aquesta lliga</p>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: Number,
        compatibilidadProp: Object
    },
    data() {
        return {
            lliga: {},
            disponibilitat: "",
            mensaje: "",       // Texto del mensaje
            tipoMensaje: "",   // 'success' o 'error'
        };
    },

    computed: {
        isLligaCompleta() {
            return this.lliga.participants_actualment >= this.lliga.nro_equips_participants;
        }
    },
    mounted() {
        this.fetchLliga();
        this.fetchDisponibilidad();
    },
    methods: {
        fetchLliga() {
            axios.get(`api/lligues/${this.id}`)
                .then(response => {
                    this.lliga = response.data;
                })
                .catch(error => {
                    console.error("Error obteniendo la liga:", error);
                });
        },
        fetchDisponibilidad() {
            axios.get(`api/lliga/${this.id}/disponibilitat-ia`)
                .then(response => {
                    console.log("Disponibilitat horària:", response.data); // Verifica el contenido aquí
                    this.disponibilitat = response.data;
                })
                .catch(error => {
                    console.error("Error obteniendo la disponibilidad:", error);
                });
        },
        getCsrfToken() {
            // Obtener el token CSRF de la meta tag
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        },
        submitForm() {
            if (this.isLligaCompleta) {
                this.mensaje = "Aquesta lliga ja està completa. No es poden acceptar més inscripcions.";
                this.tipoMensaje = "error";
                return;
            }

            if (this.lliga.ya_en_otra_liga) {
                this.mensaje = "Ja estàs inscrit en una altra lliga. No pots participar en múltiples lligues simultàniament.";
                this.tipoMensaje = "error";
                return;
            }

            axios.post(`lligues/${this.id}/inscribirse`, {}, {
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    this.fetchLliga();
                    if (response.data && response.data.success) {
                        this.mensaje = response.data.message || "T'has inscrit correctament a la lliga!";
                        this.tipoMensaje = "success";

                        window.scrollTo({ top: 0, behavior: 'smooth' }); º

                        setTimeout(() => {
                            this.mensaje = "";
                            this.tipoMensaje = "";
                        }, 5000);
                    }

                })
                .catch(error => {
                    let mensaje = "Hi ha hagut un error en la inscripció. Torna a intentar-ho més tard.";
                    if (error.response?.data?.message) {
                        mensaje = error.response.data.message;
                    }
                    this.mensaje = mensaje;
                    this.tipoMensaje = "error";

                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    setTimeout(() => {
                        this.mensaje = "";
                        this.tipoMensaje = "";
                    }, 5000);
                });

        }
    }
};
</script>


<style scoped>
/* Los estilos se mantienen igual */
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

.card-text,
.card-title {
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

.div-imatge,
.div-info {
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

.info-lliga-row {
    margin: 0;
}

.disponibilitat-card {
    font-size: 18px;
}

.btn-inscriuret {
    background-color: #E67E22;
    border: 1px solid black;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 40px;
    margin-top: 20px;
}

.btn-inscriuret:hover {
    background-color: #E67E22;
    border: 1px solid black;
    transform: scale(1.1);
}

.btn-inscriuret-disabled {
    background-color: #ccc;
    border: 1px solid #999;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: not-allowed;
    margin-bottom: 40px;
    margin-top: 20px;
    opacity: 0.7;
}

.ya-inscrito {
    color: #27AE60;
    font-weight: bold;
    margin-bottom: 40px;
    margin-top: 20px;
}

@media (max-width: 999px) {
    .equips-text {
        margin-bottom: 10px;
    }

    .full-width {
        all: unset !important;
    }

    .info-lliga-row .col-12 {
        width: calc(50% - 20px);
    }

    .info-lliga-row {
        padding-left: 30px;
        padding-top: 15px;
    }

    .disponibilitat-card {
        font-size: 18px;
        margin-left: 30px;
    }
}


@media (max-width: 768px) {
    .full-width {
        all: unset !important;
    }

    .row {
        padding: 0;
    }

    .info-lliga-row {
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

    .equips-text {
        margin-bottom: 10px;
    }

    .disponibilitat-card {
        font-size: 17px;
        margin-left: 30px;
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

    .row {
        padding: 0;
    }

    .info-lliga-row {
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

    .equips-text {
        margin-bottom: 10px;
    }

    .info-lliga-row .col-12 {
        width: 100%;
    }

    .disponibilitat-card {
        font-size: 13px;
        margin-left: 30px;
    }
}
</style>
