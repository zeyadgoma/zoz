<?php
session_start();

// Check if 'idnumber' is set and not empty
if (!isset($_POST['idnumber']) || empty($_POST['idnumber'])) {
    header("Location: index.html");
    exit();
}

// Assuming your database credentials
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "sharkawi_muc";

// Create a connection to the database
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 'idnumber' from the form
$username = $_POST['idnumber'];

// Fetch email from the users table where id equals $username
$sql = "SELECT email FROM users WHERE id = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the first row
// Fetch the first row
$row = $result->fetch_assoc();

// Get the email
$email = $row['email'];

// Remove the last 20 characters
$emailWithoutLast20 = substr($email, 0, -20);

// Get the remaining characters
$name = $emailWithoutLast20;


} else {
    echo "";
}

// Close the database connection when you're done
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        #container {
            display: flex;
            height: 100vh;
        }

        #sidebar {
            width: 250px;
            padding: 30px;
            background-color: #333;
            color: #fff;
            border-radius: 10px;
            margin: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #sidebar h3 {
            margin-bottom: 20px;
        }

        #welcome-message {
            background-color: #4285f4;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            display: none;
            margin-bottom: 10px;
        }

        .conversation-link {
            display: flex;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #555;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .conversation-link:hover {
            background-color: #777;
        }

        #chat-container {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        #chat-messages {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .message-container {
            margin-bottom: 10px;
        }

        .user-message,
        .other-message {
            border-radius: 8px;
            padding: 10px;
            max-width: 70%;
        }

        .user-message {
            align-self: flex-end;
            background-color: #4285f4;
            color: #fff;
        }

        .other-message {
            align-self: flex-start;
            background-color: #fff;
            border: 1px solid #ccc;
        }

        .id-channel-container {
            color: black;
            margin-right: 5px;
        }

        .message-text {
            color: blue;
        }

        #message-input,
        #category-select,
        #send-button {
            display: flex;
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
        }

        #message-input {
            width: 80%;
            border: 1px solid #ccc;
        }

        #category-select {
            display:  none;
            border: 1px solid #ccc;
        }

        #send-button {
            cursor: pointer;
            background-color: #4285f4;
            color: #fff;
            border: none;
            transition: background-color 0.3s ease;
        }

        #send-button:hover {
            background-color: #0066cc;
        }

  #online-users-container {
    width: 250px;
    padding: 20px;
    background-color: rgb(23, 19, 19);
    color: #fff;
    border-radius: 10px;
    margin: 10px;
    padding-bottom: 50px;

}
/* Add this in your style section or in a separate CSS file */
.logout-button {
    display: block;
    padding: 10px;
    background-color: #e74c3c; /* Red color */
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 10px;
}

.logout-button:hover {
    background-color: #c0392b; /* Darker red color on hover */
}


#online-users h3 {
    margin-bottom: 10px;
}

#online-users-list {
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 200px; /* Adjust the maximum height as needed */
    overflow-y: auto;
}

#online-users-list::-webkit-scrollbar {
    width: 0   ; /* Remove scrollbar space */
}

#online-users-list:hover {
    scrollbar-color: #555 transparent; /* Change track color on hover */
}

/* Optional: Style for a smooth transition */
#online-users-list {
    transition: overflow-y 0.5s ease;
}

.online-user {
    margin-bottom: 5px;
    border: 3px solid #555;
    padding: 8px;
    border-radius: 5px;
    background-color: rgb(159, 157, 157);
    position: relative; /* Add position property for positioning the dot */
}

.online-user::before {
    content: "";
    display: inline-block;
    width: 10px;
    height: 10px;
    background-color: green;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    right: 15px; /* Adjust the right property for the dot's position */
    transform: translate(0%, -50%);
}


    </style>
</head>

<body>
    <div id="container">
        <div id="sidebar">
            <div id="welcome-message">Welcome,
                <?php echo    $name; ?>!
            </div>
            <h3>Conversations</h3>
            <?php
              if (substr($username, 0, 4) === "2211"){
                echo '<a href="#" class="conversation-link" data-channel="General">General</a>' ;
                echo '<a href="#" class="conversation-link" data-channel="PhysicalTherapy">Physical Therapy</a>';
              }
              elseif (substr($username, 0, 4) === "2212"){
                echo '<a href="#" class="conversation-link" data-channel="General">General</a>';
                echo '<a href="#" class="conversation-link" data-channel="Engineering">Engineering</a>';
              }
              elseif (substr($username, 0, 4) === "2213"){
                echo '<a href="#" class="conversation-link" data-channel="General">General</a>';
                echo '<a href="#" class="conversation-link" data-channel="Business">Business</a>';
              }
            ?>     
            <div id="online-users-container">
                <h3>Online Users</h3>
                <div id="online-users"></div>
            </div>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
        <div id="chat-container">
            <div id="chat-messages"></div>
            <div>
                <input type="text" id="message-input" placeholder="Type your message..." class="input">
                <select id="category-select">
                    <option value="General">General</option>
                    <option value="Engineering">Engineering</option>
                    <option value="PhysicalTherapy">Physical Therapy</option>
                    <option value="Business">Business</option>
                </select>
                     <!-- Add input field for shift value -->
            <label for="shift-input">Shift Value:</label>
            <input type="number" id="shift-input" min="1" value="3" class="input">

                <button id="send-button" class="button">Send</button>
                
            </div>
        </div>
    </div>

    <script>
               const ws = new WebSocket('ws://localhost:8080?username=<?php echo $name; ?>');
        const userName = "<?php echo $name; ?>";
        let currentChannel = "General";
    function sendMessage() {
        const messageInput = document.getElementById('message-input');
        const categorySelect = document.getElementById('category-select');
        const shiftInput = document.getElementById('shift-input'); // Get the shift input
        const message = messageInput.value.trim();
        const category = categorySelect.value || "General";
        shiftValue = parseInt(shiftInput.value) || 3; // Get the shift value or default to 3

        // Function to encrypt a message using Caesar cipher with the specified shift value
 function encryptMessage(text, shift) {
    return [...text]
        .map(char => {
            const charCode = char.charCodeAt(0);
            if (charCode >= 65 && charCode <= 90) {
                return String.fromCharCode((charCode - 65 + shift) % 26 + 65); // Uppercase letters
            } else if (charCode >= 97 && charCode <= 122) {
                return String.fromCharCode((charCode - 97 + shift) % 26 + 97); // Lowercase letters
            } else {
                return char; // Non-alphabetic characters
            }
        })
        .join('');
}

        if (message !== '') {
            // Encrypt the message using Caesar cipher with the specified shift value
            const encryptedMessage = encryptMessage(message, shiftValue);

            const fullMessage = `${userName}:${category}:${encryptedMessage}`;
            ws.send(fullMessage);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'insert_message.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send(`sender=${encodeURIComponent(userName)}&message=${encodeURIComponent(encryptedMessage)}&category=${encodeURIComponent(category)}`);

            messageInput.value = '';
        }
    }

        

        function updateOnlineUsers(onlineUsers) {
            const onlineUsersElement = document.getElementById('online-users');
            onlineUsersElement.textContent = `Online Users: ${onlineUsers}`;
        }

        function clearChatMessages() {
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.innerHTML = '';
        }

        function updateOnlineUsers(onlineUsers) {
            const onlineUsersElement = document.getElementById('online-users');
            onlineUsersElement.innerHTML = '<ul id="online-users-list"></ul>';

            const onlineUsersList = document.getElementById('online-users-list');

            onlineUsers.split(',').forEach(username => {
                const listItem = document.createElement('li');
                listItem.className = 'online-user';
                listItem.textContent = username;
                onlineUsersList.appendChild(listItem);
            });
        }


        function appendMessage(message, userClass) {
    const chatMessages = document.getElementById('chat-messages');
    const messageContainer = document.createElement('div');
    messageContainer.className = `message-container ${userClass}`;

    const idChannelContainer = document.createElement('div');
    idChannelContainer.className = 'id-channel-container';

    const idChannelText = document.createElement('span');
    idChannelText.className = 'id-channel-text';
    idChannelText.textContent = message.split(': ')[0];

    const messageText = document.createElement('span');
    messageText.className = 'message-text';
    const encryptedMessage = message.split(': ').slice(1).join(':');
    messageText.textContent = encryptedMessage;
    
messageContainer.addEventListener('click', toggleMessage);

function toggleMessage() {

    if (messageText.textContent === encryptedMessage) {
       let decrypt_shift = +prompt('Enter shaift value');
        // If the current content is the encrypted message, decrypt it
        messageText.textContent = decryptMessage(encryptedMessage,decrypt_shift);
    } else {
        // If the current content is decrypted, revert to the encrypted message
        messageText.textContent = encryptedMessage;
    }
}
    idChannelContainer.appendChild(idChannelText);
    idChannelContainer.appendChild(document.createTextNode(':'));
    messageContainer.appendChild(idChannelContainer);
    messageContainer.appendChild(messageText);
    chatMessages.appendChild(messageContainer);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}
function decryptMessage(text, shift) {
    return [...text]
        .map(char => {
            const charCode = char.charCodeAt(0);
            if (charCode >= 65 && charCode <= 90) {
                return String.fromCharCode(((charCode - 65 - shift + 26 * 999999999) % 26) + 65); // Uppercase letters
            } else if (charCode >= 97 && charCode <= 122) {
                return String.fromCharCode(((charCode - 97 - shift + 26 * 999999999) % 26) + 97); // Lowercase letters
            } else {
                return char; // Non-alphabetic characters
            }
        })
        .join('');
}


        document.getElementById('send-button').addEventListener('click', function () {
            sendMessage();
        });

        document.getElementById('message-input').addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        });

        ws.onmessage = function (event) {
            const parts = event.data.split(':');
            const sender = parts[0];
            const category = parts[1] || "General";
            const message = parts.slice(2).join(':');
            const isCurrentUser = sender === userName;
            const userClass = isCurrentUser ? 'user-message' : 'other-message';
            appendMessage(`${sender} (${category}): ${message}`, userClass);

            if (event.data.startsWith('Online Users:')) {
                const onlineUsers = event.data.replace('Online Users: ', '');
                updateOnlineUsers(onlineUsers);
            }
        };

        window.onload = function () {
            loadMessages(currentChannel);
            document.getElementById('welcome-message').style.display = 'block';
            loadOnlineUsers();
        };

        function loadMessages(channel) {
            clearChatMessages();
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const messages = JSON.parse(xhr.responseText);
                    messages.reverse();

                    messages.forEach(function (message) {
                        const isCurrentUser = message.sender === userName;
                        const userClass = isCurrentUser ? 'user-message' : 'other-message';
                        appendMessage(`${message.sender} (${message.category}): ${message.message}`, userClass);
                    });
                }
            };
            xhr.open('GET', `get_messages.php?category=${encodeURIComponent(channel)}`, true);
            xhr.send();

            document.getElementById('category-select').value = channel;
        }

        document.querySelectorAll('.conversation-link').forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const channel = this.getAttribute('data-channel');
                currentChannel = channel;
                loadMessages(currentChannel);
            });
        });

        function loadOnlineUsers() {
            ws.send("Get Online Users");
        }
        
    </script>
</body>

</html>