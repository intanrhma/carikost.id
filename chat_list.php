<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$uid = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Masuk - Carikost.id</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- TEMA WARNA (TEAL / TOSCA) --- */
        :root {
            --primary-gradient: linear-gradient(135deg, #00695c 0%, #4db6ac 100%);
            --main-color: #00796B; 
            --hover-color: #004D40; 
            --bg-light: #f5f7fa;
            --text-dark: #2f3542;
            --text-muted: #888;
        }

        /* Base Styles */
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--bg-light); 
            margin: 0; 
            padding: 0; 
            color: var(--text-dark);
        }
        
        a { text-decoration: none; }

        /* Container Utama (Mirip Tampilan Aplikasi HP) */
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            min-height: 100vh; 
            background: white; 
            box-shadow: 0 0 30px rgba(0,0,0,0.08); /* Shadow sedikit lebih tegas */
            display: flex; 
            flex-direction: column; 
            position: relative;
        }

        /* Header Teal */
        .header { 
            background: var(--primary-gradient); 
            color: white; 
            padding: 20px 25px; 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            position: sticky; 
            top: 0; 
            z-index: 100; 
            box-shadow: 0 4px 10px rgba(0, 105, 92, 0.25); /* Shadow lebih jelas */
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }
        
        .header h2 { 
            margin: 0; 
            font-size: 1.3em; 
            flex: 1; 
            font-weight: 600;
        }
        
        .btn-back { 
            color: white; 
            font-size: 1.2em; 
            transition: 0.3s; 
            background: rgba(255,255,255,0.2);
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .btn-back:hover { 
            background: rgba(255,255,255,0.3); 
            transform: scale(1.05); 
        }

        /* Area List Chat */
        #chat-list { 
            padding: 0; /* Menghilangkan padding agar item rapat ke atas */
            flex: 1; 
            overflow-y: auto;
        }

        /* Kartu Chat Item */
        .chat-item { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            padding: 15px 25px; 
            border-bottom: 1px solid #f0f0f0; 
            cursor: pointer; 
            transition: 0.2s; 
            background: white;
            position: relative;
        }
        
        .chat-item:hover { 
            background-color: #f5fcfb; /* Hover yang lebih halus */
        }

        /* Status Belum Dibaca (Opsional, perlu logika Firebase tambahan) */
        .chat-item.unread .chat-name {
            font-weight: 700;
            color: var(--main-color);
        }
        .chat-item.unread .chat-preview {
            color: var(--text-dark);
            font-weight: 500;
        }

        /* Avatar Inisial */
        .avatar { 
            width: 50px; 
            height: 50px; 
            background: var(--main-color); 
            color: white; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 1.2em; 
            font-weight: 600;
            flex-shrink: 0;
            text-transform: uppercase;
        }

        /* Info Text */
        .chat-content { flex: 1; min-width: 0; }
        
        .chat-name { 
            font-weight: 600; 
            font-size: 1em; 
            color: #333; 
            margin-bottom: 4px; 
            display: block; 
        }
        
        .chat-preview { 
            font-size: 0.9em; 
            color: var(--text-muted); 
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis; 
            display: block; 
        }
        
        /* Waktu */
        .chat-meta { 
            font-size: 0.75em; 
            color: #aaa; 
            flex-shrink: 0; 
            text-align: right; 
            font-weight: 500;
            align-self: flex-start; /* Agar waktu tidak ikut center dengan avatar */
        }

        /* Empty State */
        .empty-state { 
            text-align: center; 
            padding: 60px 20px; 
            color: #aaa; 
            margin-top: 20px;
        }
        .empty-state i { 
            font-size: 5em; 
            margin-bottom: 20px; 
            color: #e0e0e0; 
        }
        .empty-state h3 { margin: 0 0 10px 0; color: #555; font-weight: 600; }
        .empty-state p { margin: 0; font-size: 0.9em; color: var(--text-muted); }
        
        /* Loading Spinner */
        .loading { 
            text-align: center; 
            padding: 40px; 
            color: var(--main-color); 
            font-weight: 500;
            font-size: 1.1em;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <a href="dashboard.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i></a>
            <h2>Pesan Masuk</h2>
        </div>

        <div id="chat-list">
            <div id="loading-spinner" class="loading">
                <i class="fa-solid fa-circle-notch fa-spin"></i> Memuat percakapan...
            </div>
            </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-database.js";

        // 💡 PERBAIKAN: Menggunakan databaseURL yang benar sesuai warning Firebase
        const firebaseConfig = {
            apiKey: "AIzaSyC_DglM3bl4NiamVIbs2lj9IgrUpCF1oIg",
            authDomain: "carikost-id-f46dc.firebaseapp.com",
            projectId: "carikost-id-f46dc",
            storageBucket: "carikost-id-f46dc.firebasestorage.app",
            messagingSenderId: "396631809884",
            appId: "1:396631809884:web:093fc32ee8e348ccceab99",
            measurementId: "G-LVCRCVCXLB",
            // Tambahkan databaseURL yang benar di region Asia-Southeast1
            databaseURL: "https://carikost-id-f46dc-default-rtdb.asia-southeast1.firebasedatabase.app"
        };

        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);
        const myId = "<?= $uid ?>";

        const chatListRef = ref(db, 'user_chats/' + myId);
        const listDiv = document.getElementById('chat-list');
        const loadingSpinner = document.getElementById('loading-spinner');

        // Fungsi Warna Avatar Acak
        function getAvatarColor(name) {
            const colors = ['#00796B', '#009688', '#26A69A', '#4DB6AC', '#80CBC4'];
            let hash = 0;
            for (let i = 0; i < name.length; i++) {
                hash = name.charCodeAt(i) + ((hash << 5) - hash);
            }
            return colors[Math.abs(hash) % colors.length];
        }

        // Fungsi Format Waktu
        function formatTime(timestamp) {
            if(!timestamp) return '';
            const date = new Date(timestamp);
            const now = new Date();
            const oneDay = 24 * 60 * 60 * 1000;
            
            const diffDays = Math.round(Math.abs((now - date) / oneDay));
            
            if (diffDays === 0) { // Hari Ini
                return date.getHours().toString().padStart(2, '0') + ":" + date.getMinutes().toString().padStart(2, '0');
            } else if (diffDays === 1) { // Kemarin
                return 'Kemarin';
            } else if (diffDays < 7) { // Dalam Seminggu
                const options = { weekday: 'short' };
                return date.toLocaleDateString('id-ID', options);
            } else { // Tanggal
                return date.getDate() + "/" + (date.getMonth()+1) + "/" + date.getFullYear().toString().slice(-2);
            }
        }

        // Listener Realtime
        onValue(chatListRef, (snapshot) => {
            const data = snapshot.val();
            listDiv.innerHTML = ""; // Bersihkan list setelah loading

            if (data) {
                // Konversi objek ke array dan sorting berdasarkan timestamp terbaru
                const chats = Object.entries(data).sort((a, b) => b[1].timestamp - a[1].timestamp);

                chats.forEach(([key, chat]) => {
                    const timeStr = formatTime(chat.timestamp);
                    const initial = chat.lawan_nama ? chat.lawan_nama.charAt(0).toUpperCase() : '?';
                    const avatarColor = getAvatarColor(chat.lawan_nama || 'User');
                    
                    const item = document.createElement('div');
                    // Tambahkan class 'unread' jika ada flag unread (Asumsi: Anda punya logic unread)
                    // item.className = 'chat-item' + (chat.unread_count > 0 ? ' unread' : '');
                    item.className = 'chat-item';
                    item.onclick = function() { window.location.href = "chat.php?lawan_id=" + chat.lawan_id; };
                    
                    item.innerHTML = `
                        <div class="avatar" style="background-color: ${avatarColor}">${initial}</div>
                        <div class="chat-content">
                            <span class="chat-name">${chat.lawan_nama || 'Pengguna Tidak Dikenal'}</span>
                            <span class="chat-preview">${chat.last_msg || 'Ketik pesan pertama...'}</span>
                        </div>
                        <div class="chat-meta">${timeStr}</div>
                    `;
                    listDiv.appendChild(item);
                });
            } else {
                // Tampilkan Empty State
                listDiv.innerHTML = `
                    <div class="empty-state">
                        <i class="fa-regular fa-comments"></i>
                        <h3>Belum ada pesan</h3>
                        <p>Percakapan dengan pemilik atau penyewa akan muncul di sini.</p>
                    </div>
                `;
            }
        });
    </script>
</body>
</html>