<template>
  <div class="chat-container">
    <h1>Chat: {{ lliga.nom_lliga }}</h1>
    <div class="chat-box" ref="chatBox">
      <template v-for="(missatge, index) in missatges" :key="missatge.id_missatge">
        <!-- Mostrar el día si es diferente al del mensaje anterior -->
        <div v-if="shouldShowDate(index)" class="chat-date">
          {{ formatDate(missatge.timestamp) }}
        </div>
        <div
          class="chat-message"
          :class="{
            'chat-message-sent': missatge.origen.usuari_id_usuari === userId,
            'chat-message-received': missatge.origen.usuari_id_usuari !== userId
          }"
        >
          <div class="chat-message-header">
            <strong>{{ missatge.origen.nom_equip }}</strong>
          </div>
          <div class="chat-message-body">
            <p>{{ missatge.text }}</p>
          </div>
          <div class="chat-message-timestamp">
            <small>{{ formatTime(missatge.timestamp) }}</small>
          </div>
        </div>
      </template>
    </div>
    <div class="chat-input">
      <input
        type="text"
        v-model="newMessage"
        placeholder="Escriu un missatge..."
        @keyup.enter="sendMessage"
      />
      <button @click="sendMessage">Enviar</button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      missatges: [],
      userId: null,
      lliga: {},
      newMessage: "",
    };
  },
  mounted() {
    this.fetchMissatges();
  },
  methods: {
    fetchMissatges() {
      axios
        .get("/chatMissatges")
        .then((response) => {
          this.missatges = response.data.missatges;
          this.userId = response.data.userId;
          this.lliga = response.data.lliga;
          this.$nextTick(() => {
            this.scrollToBottom();
          });
        })
        .catch((error) => {
          console.error("Error obteniendo los mensajes:", error);
        });
    },
    sendMessage() {
      if (this.newMessage.trim() === "") return;

      const messageData = {
        text: this.newMessage,
      };

      this.newMessage = "";

      axios
        .post("/chatMissatges", messageData)
        .then(() => {
          this.fetchMissatges(); // Volver a llamar a la API para obtener los nuevos mensajes
        })
        .catch((error) => {
          console.error("Error enviando el mensaje:", error);
        });
    },
    scrollToBottom() {
      const chatBox = this.$refs.chatBox;
      if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
      }
    },
    shouldShowDate(index) {
      if (index === 0) return true; // Mostrar la fecha para el primer mensaje
      const currentDate = this.formatDate(this.missatges[index].timestamp);
      const previousDate = this.formatDate(this.missatges[index - 1].timestamp);
      return currentDate !== previousDate; // Mostrar la fecha si es diferente a la anterior
    },
    formatDate(timestamp) {
      const date = new Date(timestamp);
      return date.toLocaleDateString(); // Formato de fecha local
    },
    formatTime(timestamp) {
      const date = new Date(timestamp);
      return date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" }); // Formato de hora local
    },
  },
};
</script>

<style>
/* Contenedor principal */
.chat-container {
  max-width: 900px;
  margin: 0 auto;
  font-family: Arial, sans-serif;
  height: 80vh; /* Ocupa el 90% de la altura de la pantalla */
  display: flex;
  flex-direction: column;
}

/* Caja del chat */
.chat-box {
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 10px;
  flex: 1; /* Ocupa todo el espacio disponible */
  overflow-y: auto;
  background-color: #f9f9f9;
  display: flex;
  flex-direction: column;
}

/* Entrada del chat */
.chat-input {
  display: flex;
  margin-top: 10px;
  flex-direction: row; /* Mantener el input y el botón en una fila */
  align-items: center;
}

/* Campo de texto */
.chat-input input {
  flex: 1;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-right: 10px; /* Espaciado entre el input y el botón */
}

/* Botón de enviar */
.chat-input button {
  padding: 10px 20px;
  background-color: #E67E22;
  border: 1px solid black;
  border-radius: 5px;
  cursor: pointer;
}

.chat-input button:hover {
  background-color: #D35400;
  transform: scale(1.1);
}

/* Responsividad para pantallas pequeñas */
@media (max-width: 768px) {
  .chat-container {
    max-width: 100%;
    padding: 0 10px;
  }

  .chat-box {
    height: auto; /* Ajustar dinámicamente */
  }

  .chat-message {
    max-width: 90%; /* Aumentar el ancho de los mensajes */
  }

  .chat-input {
    flex-direction: row; /* Mantener el input y el botón en una fila */
  }

  .chat-input input {
    margin-bottom: 0; /* Eliminar margen inferior */
    margin-right: 10px; /* Espaciado entre el input y el botón */
  }

  .chat-input button {
    width: auto; /* Ajustar el ancho del botón */
    padding: 10px; /* Reducir el padding */
  }
}

/* Responsividad para pantallas muy pequeñas (teléfonos) */
@media (max-width: 480px) {
  .chat-box {
    height: auto; /* Ajustar dinámicamente */
  }

  .chat-message {
    font-size: 0.9em; /* Reducir el tamaño de fuente de los mensajes */
  }

  .chat-message-header {
    font-size: 0.8em; /* Reducir el tamaño de fuente del encabezado */
  }

  .chat-message-body {
    font-size: 0.9em; /* Reducir el tamaño de fuente del cuerpo */
  }

  .chat-message-timestamp {
    font-size: 0.7em; /* Reducir el tamaño de fuente del timestamp */
  }

  .chat-input {
    flex-direction: row; /* Mantener el input y el botón en una fila */
  }

  .chat-input input {
    margin-right: 10px; /* Espaciado entre el input y el botón */
  }

  .chat-input button {
    width: auto; /* Ajustar el ancho del botón */
    padding: 10px; /* Reducir el padding */
  }
}

.chat-date {
  text-align: center;
  font-size: 0.9em;
  color: #555;
  margin: 10px 0;
  font-weight: bold;
}

.chat-message {
  margin-bottom: 10px;
  padding: 10px;
  border-radius: 8px;
  max-width: 70%;
  display: inline-block;
}

.chat-message-sent {
  background-color: #3498DB;
  align-self: flex-end;
  text-align: right;
  color: rgba(255, 255, 255, 0.863);
}

.chat-message-received {
  background-color: #ECF0F1;
  align-self: flex-start;
  text-align: left;
}

.chat-message-header {
  font-size: 0.9em;
  margin-bottom: 5px;
  font-weight: bold;
}

.chat-message-body {
  font-size: 1em;
  color: #000000;
}

.chat-message-sent .chat-message-body {
  color: white;
}

.chat-message-sent .chat-message-timestamp {
  color: rgb(225, 225, 225);
}

.chat-message-timestamp {
  font-size: 0.8em;
  color: #141414;
  text-align: right;
  margin-top: 5px;
}
</style>
