<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$my_id    = $_SESSION['user_id'];
$my_role  = $_SESSION['role'];
$lawan_id = $_GET['lawan_id'] ?? '';

if (!$lawan_id) {
    die("Error: Tidak tahu mau chat dengan siapa.");
}

// Ambil data lawan bicara untuk header
$lawanData  = $database->getReference('users/' . $lawan_id)->getValue();
$namaLawan  = $lawanData['nama_lengkap'] ?? 'Pengguna';

// Buat Room ID Unik (Digabung & diurut)
$ids = [$my_id, $lawan_id];
sort($ids);
$room_id = "room_" . $ids[0] . "_" . $ids[1];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Chat dengan <?= htmlspecialchars($namaLawan) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #e5ddd5;
            margin: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .chat-header {
            background: #008069;
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .back-btn {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
        }

        .user-info h3 {
            margin: 0;
            font-size: 1.1em;
        }

        .user-info p {
            margin: 0;
            font-size: 0.8em;
            opacity: 0.8;
        }

        .chat-container {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .message {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 10px;
            position: relative;
            word-wrap: break-word;
            font-size: 0.95em;
        }

        .msg-me {
            align-self: flex-end;
            background: #dcf8c6;
            border-top-right-radius: 0;
        }

        .msg-other {
            align-self: flex-start;
            background: white;
            border-top-left-radius: 0;
        }

        .time {
            font-size: 0.7em;
            color: #999;
            display: block;
            text-align: right;
            margin-top: 5px;
        }

        .input-area {
            background: #f0f0f0;
            padding: 10px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .input-area input {
            flex: 1;
            padding: 12px;
            border-radius: 20px;
            border: none;
            outline: none;
        }

        .btn-send {
            background: #008069;
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

<div class="chat-header">
    <a href="dashboard.php" class="back-btn">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div class="user-info">
        <h3><?= htmlspecialchars($namaLawan) ?></h3>
        <p><?= ($my_role == 'penyewa') ? 'Pemilik Kost' : 'Penyewa' ?></p>
    </div>
</div>

<div class="chat-container" id="chat-box">
    <p style="text-align:center; color:#888; font-size:0.8em;">
        Mulai percakapan...
    </p>
</div>

<form class="input-area" id="chat-form">
    <input type="text" id="msg-input" placeholder="Ketik pesan..." autocomplete="off" required>
    <button type="submit" class="btn-send">
        <i class="fa-solid fa-paper-plane"></i>
    </button>
</form>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import {
    getDatabase,
    ref,
    push,
    onChildAdded,
    set,
    serverTimestamp
} from "https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js";

// CONFIG FIREBASE - SUDAH FIX
const firebaseConfig = {
    apiKey: "AIzaSyC_DglM3bl4NiamVIbs2lj9IgrUpCF1oIg",
    authDomain: "carikost-id-f46dc.firebaseapp.com",
    databaseURL: "https://carikost-id-f46dc-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "carikost-id-f46dc",
    storageBucket: "carikost-id-f46dc.appspot.com",
    messagingSenderId: "396631809884",
    appId: "1:396631809884:web:093fc32ee8e348ccceab99",
    measurementId: "G-LVCRCVCXLB"
};

const app = initializeApp(firebaseConfig);
const db  = getDatabase(app);

// DATA DARI PHP
const roomId     = "<?= $room_id ?>";
const myId       = "<?= $my_id ?>";
const lawanId    = "<?= $lawan_id ?>";
const myName     = "<?= $_SESSION['nama'] ?>";
const lawanName  = "<?= $namaLawan ?>";

// REFERENSI PESAN
const messagesRef = ref(db, 'chats/' + roomId + '/messages');

// =======================
// KIRIM PESAN
// =======================
document.getElementById('chat-form').addEventListener('submit', (e) => {
    e.preventDefault();

    const input = document.getElementById('msg-input');
    const text = input.value.trim();

    if (text === "") return;

    // Kirim pesan ke room
    push(messagesRef, {
        sender: myId,
        text: text,
        timestamp: serverTimestamp()
    });

    // User Saya → Lawan
    set(ref(db, 'user_chats/' + myId + '/' + roomId), {
        lawan_id: lawanId,
        lawan_nama: lawanName,
        last_msg: text,
        timestamp: serverTimestamp()
    });

    // User Lawan → Saya
    set(ref(db, 'user_chats/' + lawanId + '/' + roomId), {
        lawan_id: myId,
        lawan_nama: myName,
        last_msg: text,
        timestamp: serverTimestamp()
    });

    input.value = "";
});

// =======================
// TERIMA PESAN REALTIME
// =======================
const chatBox = document.getElementById('chat-box');

onChildAdded(messagesRef, (data) => {
    const msg = data.val();
    if (!msg) return;

    const isMe = (msg.sender === myId);

    // FIX: Kalau timestamp belum jadi number
    let timeStr = '';
    if (msg.timestamp) {
        const date = new Date(msg.timestamp);
        timeStr = date.getHours() + ":" + String(date.getMinutes()).padStart(2, '0');
    }

    const div = document.createElement('div');
    div.className = "message " + (isMe ? "msg-me" : "msg-other");
    div.innerHTML = `
        ${msg.text}
        <span class="time">${timeStr}</span>
    `;

    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
});
</script>

</body>
</html>
