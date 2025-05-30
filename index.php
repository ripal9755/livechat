<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat Application</title>
    <style>
    #chat-box {
        height: 300px;
        overflow-y: scroll;
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>
    <h1>Live Chat</h1>
    <div id="chat-box"></div>
    <form id="chat-form">
        <input type="text" id="username" placeholder="Enter your username" required>
        <input type="text" id="message" placeholder="Enter your message" required>
        <button type="submit">Send</button>
    </form>
</body>

</html>





<script type="module">
// Import the functions you need from the SDKs you need
import {
    initializeApp
} from "https://www.gstatic.com/firebasejs/11.8.1/firebase-app.js";
import {
    getAnalytics
} from "https://www.gstatic.com/firebasejs/11.8.1/firebase-analytics.js";
import {
    getDatabase,
    ref,
    push,
    onValue
} from "https://www.gstatic.com/firebasejs/11.8.1/firebase-database.js";

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyDMWiR5xExOtWPzxmGUM2bB8ij-RVEnAu0",
    authDomain: "livechat-9d0af.firebaseapp.com",
    projectId: "livechat-9d0af",
    storageBucket: "livechat-9d0af.firebasestorage.app",
    messagingSenderId: "37182427149",
    appId: "1:37182427149:web:ced67dce519f242ca7c544",
    measurementId: "G-0CTJ0KSKW6",
    databaseURL: "https://livechat-9d0af-default-rtdb.firebaseio.com/"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

// Initialize Realtime Database
const db = getDatabase(app);

// Function to send a chat message
function sendMessage(username, message) {
    const chatRef = ref(db, 'chat');
    push(chatRef, {
        username: username,
        message: message,
        timestamp: Date.now(),
    });
}

// Function to display messages in real-time
function displayMessages() {
    const chatRef = ref(db, 'chat');
    onValue(chatRef, (snapshot) => {
        const messages = snapshot.val();
        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML = ''; // Clear previous messages
        for (const key in messages) {
            const msg = messages[key];
            const div = document.createElement('div');
            div.textContent =
                `[${new Date(msg.timestamp).toLocaleTimeString()}] ${msg.username}: ${msg.message}`;
            chatBox.appendChild(div);
        }
    });
}

// Initialize the message display
displayMessages();

// Handle form submission to send messages
document.getElementById('chat-form').addEventListener('submit', (e) => {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const message = document.getElementById('message').value;
    sendMessage(username, message);
    document.getElementById('message').value = ''; // Clear message input
});
</script>