<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            margin-top: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        #chat-box {
            height: 300px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }

        .user-input {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .user-input div {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-input input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .user-input button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .user-input button:hover {
            background-color: #45a049;
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #e9f7e9;
        }

        .message-time {
            font-size: 12px;
            color: #777;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        a {
            color: black;
        }
    </style>
</head>

<body>
    <h1>Live Chat Application</h1>
    <div class="container">
        <div id="user-box" class="user-input"></div>
    </div>
    <footer>&copy; 2025 Live Chat Application</footer>
</body>




<script type="module">
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/11.8.1/firebase-app.js";
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


    async function sendMessage(userName, userId, message) {
        const messageRef = collection(db, "Chats");
        document.getElementById(userId).value = "";
        // const messageRef = await addDoc(collection(db, "Chats"))
        await addDoc(messageRef, {
            userName: userName,
            userID: userId,
            message: message,
            time: serverTimestamp()
        })
    }


    async function displayMessages() {

        const messagesRef = collection(db, "Chats");
        const q = query(messagesRef, orderBy("time", "desc"));
        const querySnapshot = await getDocs(q);
        const messages = querySnapshot.docs.map(doc => ({
            id: doc.id,
            ...doc.data(),
        }));

        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML = '';
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

    document.addEventListener("DOMContentLoaded", () => {
        displayUsers();

    })

    async function displayUsers() {
        const userRef = collection(db,
            "Users");
        const q = query(userRef, orderBy("time", "desc"));
        const querySnapshot = await getDocs(q);
        const users = querySnapshot.docs.map(doc => ({
            id: doc.id,
            ...doc.data(),
        }))
        const userBox = document.getElementById("user-box");
        const currentDomain = window.location.href;
        userBox.innerHTML = "";
        for (const key in users) {
            const user = users[key];
            const div = document.createElement('div');
            const input = document.createElement("input");
            const inputUser = document.createElement("a");
            inputUser.href = currentDomain + "chatDetail.php?id=" + user.id;
            inputUser.textContent = user.userName;
            input.type = "text";
            input.id = user.id;
            input.placeholder = "Enter your message here";

            const button = document.createElement("button");
            button.addEventListener("click", () => {
                const message = document.getElementById(user.id).value;
                console.log(message);
                sendMessage(user.userName, user.id, message);
            });
            button.textContent = "Send";
            div.appendChild(inputUser);
            div.appendChild(input);
            div.appendChild(button);
            userBox.appendChild(div);
        }
    }
</script>