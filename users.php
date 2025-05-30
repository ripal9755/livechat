<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <style>
        #chat-box {
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>

    <style>
        /* General reset and body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h1 {
            font-size: 2rem;
            color: #555;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Styling the user box */
        #user-box {
            width: 100%;
            max-width: 400px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            margin-bottom: 20px;
            padding: 10px;
        }

        #user-box div {
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s ease;
        }

        #user-box div:hover {
            background-color: #e6f7ff;
        }

        /* Styling the form */
        #add-user {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 400px;
        }

        #add-user input {
            width: 100%;
            max-width: 400px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        #add-user input:focus {
            border-color: #007bff;
            outline: none;
        }

        #add-user button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        #add-user button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        #add-user button:active {
            transform: scale(0.98);
        }

        /* Scroll styling */
        #user-box::-webkit-scrollbar {
            width: 8px;
        }

        #user-box::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }

        #user-box::-webkit-scrollbar-thumb:hover {
            background-color: #aaa;
        }
    </style>

</head>

<body>
    <h1>users list</h1>
    <div id="user-box"></div>
    <form id="add-user">
        <input type="text" id="username" placeholder="Enter user name" required>
        <button type="submit">Add user</button>
    </form>
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


    document.addEventListener("DOMContentLoaded", () => {
        displayUsers();

        document.getElementById('add-user').addEventListener('submit', (e) => {
            e.preventDefault();
            const username = document.getElementById("username").value;
            addUser(username);
            document.getElementById("username").value = "";
        })

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
                div.textContent = `${user.userName}`;
                userBox.appendChild(div);
            }
        }

        async function addUser(username) {
            const userRef = collection(db, 'Users');
            await addDoc(userRef, {
                userName: username,
                time: serverTimestamp()
            })
        }
    });
</script>