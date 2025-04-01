<template>
  <div class="chat-container">
    <h1>Chat</h1>
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

      axios
        .post("/chatMissatges", messageData)
        .then((response) => {
          const newMissatge = response.data.missatge;
          this.missatges.push(newMissatge); // Agregar el mensaje al chat
          this.newMessage = ""; // Limpiar el campo de entrada
          this.$nextTick(() => {
            this.scrollToBottom(); // Desplazar hacia abajo
          });
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
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); // Formato de hora local
    },
  },
};
</script>

<style>
.chat-container {
  max-width: 900px;
  margin: 0 auto;
  font-family: Arial, sans-serif;
}

.chat-box {
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 10px;
  height: 400px;
  overflow-y: auto;
  background-color: #f9f9f9;
  display: flex;
  flex-direction: column;
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

.chat-message-timestamp {
  font-size: 0.8em;
  color: #141414;
  text-align: right;
  margin-top: 5px;
}

.chat-input {
  display: flex;
  margin-top: 10px;
}

.chat-input input {
  flex: 1;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-right: 10px;
}

.chat-input button {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}

.chat-input button:hover {
  background-color: #0056b3;
}
</style>
