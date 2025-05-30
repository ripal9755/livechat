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
    <h1>Live Chat Application</h1>
    <div id="chat-box"></div>
    <form id="chat-form">
        <input type="text" id="username" placeholder="Enter your username" required>
        <input type="text" id="message" placeholder="Enter your message" required>
        <button type="submit">Send</button>
    </form>
    <div id="user-box">

    </div>
</body>

</html>





<script type="module">
    // Import the functions you need from the SDKs you need
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/11.8.1/firebase-app.js";

    // import {
    //     getDatabase
    // } from "https://www.gstatic.com/firebasejs/11.8.1/firebase-database.js";
    import {
        getFirestore,
        collection,
        addDoc,
        serverTimestamp,
        getDocs,
        query,
        orderBy
    } from "https://www.gstatic.com/firebasejs/11.8.1/firebase-firestore.js";



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
    const db = getFirestore(app);

    // Initialize Realtime Database
    //const db = getDatabase(app);

    async function sendMessage(user, message) {
        const messageRef = collection(db, "Chats");
        // const messageRef = await addDoc(collection(db, "Chats"))
        await addDoc(messageRef, {
            user: user,
            message: message,
            time: serverTimestamp()
        })
    }


    // Function to display messages in real-time
    async function displayMessages() {

        const messagesRef = collection(db, "Chats");
        const q = query(messagesRef, orderBy("time", "desc"));
        const querySnapshot = await getDocs(q);
        const messages = querySnapshot.docs.map(doc => ({
            id: doc.id, // Document ID
            ...doc.data(), // Document data
        }));

        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML = ''; // Clear previous messages
        for (const key in messages) {
            const msg = messages[key];
            const div = document.createElement('div');
            div.textContent =
                `[${msg.time.toDate().toLocaleString
                    ()}] ${msg.user}: ${msg.message}`;
            chatBox.appendChild(div);
        }

        console.log("Fetched messages:", messages);
    }

    // Initialize the message display
    document.addEventListener("DOMContentLoaded", () => {
        displayMessages();
        displayUsers();

    })

    // Handle form submission to send messages
    document.getElementById('chat-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const message = document.getElementById('message').value;
        sendMessage(username, message);
        document.getElementById('message').value = ''; // Clear message input
    });

    async function displayUsers() {
        const userRef = collection(db,
            "Users");
        const q = query(userRef, orderBy("time", "desc"));
        const querySnapshot = await getDocs(q);
        const users = querySnapshot.docs.map(doc => ({
            id: doc.id,
            ...doc.data(), // Document data
        }))
        const userBox = document.getElementById("user-box");
        userBox.innerHTML = "";
        for (const key in users) {
            const user = users[key];
            const div = document.createElement('div');
            const input = document.createElement("input");
            input.type = "text";
            input.id = user.id;
            input.placeholder = "Enter your message here";

            const button = document.createElement("button");
            button.addEventListener("click", () => {
                const message = document.getElementById(user.id).value; // Get the input value
                sendMessage(user, message);
            });
            button.textContent = "Send";
            div.appendChild(input);
            div.appendChild(button);
            //div.textContent = `${user.userName}`;
            userBox.appendChild(div);
        }
    }
</script>