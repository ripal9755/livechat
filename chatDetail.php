<?php
$userId = isset($_GET['id']) ? $_GET['id'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Messages</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        #messages {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }

        /* Message style */
        .message {
            background: #e1ffc7;
            border-radius: 15px;
            padding: 12px 18px;
            font-size: 16px;
            color: #333;
            border: 1px solid #b2d8a9;
            box-shadow: 1px 2px 4px rgba(0, 0, 0, 0.05);
            max-width: 80%;
            word-wrap: break-word;
        }

        /* Disabled input styled as message */
        input.message-input {
            background-color: #e1ffc7;
            border: 1px solid #b2d8a9;
            border-radius: 15px;
            padding: 12px 18px;
            font-size: 16px;
            color: #333;
            max-width: 80%;
            cursor: default;
            user-select: text;
            outline: none;
            box-shadow: 1px 2px 4px rgba(0, 0, 0, 0.05);
        }

        input.message-input:disabled {
            background-color: #e1ffc7;
            color: #333;
        }

        /* Scrollbar styling */
        #messages::-webkit-scrollbar {
            width: 8px;
        }

        #messages::-webkit-scrollbar-thumb {
            background-color: #a6c47f;
            border-radius: 4px;
        }

        #messages::-webkit-scrollbar-track {
            background-color: #f0f2f5;
        }

        .no-messages {
            text-align: center;
            color: #777;
            font-style: italic;
            margin-top: 40px;
        }

        .message-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 5px 0;
        }

        .message-input {
            flex: 1;
            padding: 5px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .message-time {
            color: #666;
            font-size: 0.9em;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>User Messages</h2>
        <div id="messages" class="message-container">
            <!-- Messages will appear here -->
        </div>
    </div>

    <script type="module">
        const userId = "<?php echo $userId; ?>";
        console.log('User ID:', userId);

        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/11.8.1/firebase-app.js";
        import {
            getFirestore,
            collection,
            getDocs,
            query,
            where,
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

        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);

        document.addEventListener("DOMContentLoaded", () => {
            displayMessages();
        });

        async function displayMessages() {
            const messagesRef = collection(db, "Chats");
            const q = query(messagesRef,
                where("userID", "==", userId),
                orderBy("time", "desc"));
            const querySnap = await getDocs(q);

            const messages = querySnap.docs.map(doc => ({
                id: doc.id,
                ...doc.data(),
            }));

            const mainDiv = document.getElementById("messages");
            mainDiv.innerHTML = '';

            if (messages.length === 0) {
                const noMsg = document.createElement('p');
                noMsg.textContent = 'No messages found for this user.';
                noMsg.classList.add('no-messages');
                mainDiv.appendChild(noMsg);
                return;
            }

            messages.forEach(msg => {
                const input = document.createElement("input");
                input.type = 'text';
                input.className = 'message-input';
                input.value = msg.message;
                input.className = 'message-input';
                input.disabled = true;

                const span = document.createElement("span");
                span.className = "message-time";
                var msgTime = msg.time;
                msgTime = msgTime.toDate();
                if (isToday(msgTime))
                    span.textContent = msgTime.toLocaleTimeString();
                else
                    span.textContent = msgTime.toLocaleTimeString() + " " + msgTime.toLocaleDateString();

                mainDiv.appendChild(input);
                mainDiv.appendChild(span);
            });
        }


        function isToday(date) {
            const today = new Date();
            return (
                date.getFullYear() == today.getFullYear() &&
                date.getMonth() == today.getMonth() &&
                date.getDate() == today.getDate()
            );
        }
    </script>
</body>

</html>