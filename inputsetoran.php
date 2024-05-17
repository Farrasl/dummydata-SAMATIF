<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "dummy_data2";

// Membuat koneksi
$koneksi = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Inisialisasi variabel untuk pesan kesalahan
$error = "";

// Memproses input setoran jika formulir dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Id_Riwayat = $_POST["Id_Riwayat"];
    $Tanggal = $_POST["Tanggal"];
    $Kelancaran = $_POST["Kelancaran"];
    $Tajwid = $_POST["Tajwid"];
    $Makhrajul_Huruf = $_POST["Makhrajul_Huruf"];
    $NIP = $_POST["NIP"];
    $Id_Surah = $_POST["Id_Surah"];
    
    // Query untuk menyimpan setoran ke dalam database
    $query_input_setoran = "INSERT INTO input_setoran (Id_Riwayat, Tanggal, Kelancaran, Tajwid, Makhrajul_Huruf, NIP, Id_Surah) VALUES ('$Id_Riwayat', '$Tanggal', '$Kelancaran', '$Tajwid', '$Makhrajul_Huruf', '$NIP', '$Id_Surah')";
    
    if ($koneksi->query($query_input_setoran) === TRUE) {
        $error = "Setoran berhasil ditambahkan.";
    } else {
        $error = "Error: " . $query_input_setoran . "<br>" . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Setoran Mahasiswa</title>
    <style>
        :root {
            --primary: #4ade80;
            --primary-alt: #22c55e;
            --grey: #64748b;
            --dark: #1e293b;
            --dark-alt: #334155;
            --light: #f1f5f9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Fira sans', sans-serif;
        }

        body {
            background: var(--light);
        }

        button {
            cursor: pointer;
            appearance: none;
            border: none;
            outline: none;
            background: none;
        }

        .container {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .dropbtn {
            background-color: var(--primary-alt);
            color: var(--light);
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            text-align: left;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 100%;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: #3e8e41;
        }

        .submit-button {
            background-color: var(--primary-alt);
            color: var(--light);
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            border: none;
            cursor: pointer;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.dropdown');

            dropdowns.forEach(dropdown => {
                const btn = dropdown.querySelector('.dropbtn');
                const content = dropdown.querySelector('.dropdown-content');
                const links = content.querySelectorAll('a');
                const hiddenInput = dropdown.querySelector('input[type="hidden"]');

                links.forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        btn.innerText = link.innerText;
                        hiddenInput.value = link.innerText;
                        content.style.display = 'none';
                    });
                });

                btn.addEventListener('click', function() {
                    content.style.display = content.style.display === 'block' ? 'none' : 'block';
                });

                document.addEventListener('click', function(event) {
                    if (!dropdown.contains(event.target)) {
                        content.style.display = 'none';
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Input Setoran Mahasiswa</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="Id_Riwayat">Id_Riwayat:</label>
                <input type="text" id="Id_Riwayat" name="Id_Riwayat" required>
            </div>
            <div class="form-group">
                <label for="Tanggal">Tanggal:</label>
                <input type="date" id="Tanggal" name="Tanggal" required>
            </div>
            <div class="form-group dropdown">
                <label for="Kelancaran">Kelancaran:</label>
                <button class="dropbtn">Pilih Kelancaran</button>
                <div class="dropdown-content">
                    <a href="#">Sangat Baik</a>
                    <a href="#">Baik</a>
                    <a href="#">Cukup</a>
                    <a href="#">Kurang</a>
                </div>
                <input type="hidden" id="Kelancaran" name="Kelancaran" required>
            </div>
            <div class="form-group dropdown">
                <label for="Tajwid">Tajwid:</label>
                <button class="dropbtn">Pilih Tajwid</button>
                <div class="dropdown-content">
                    <a href="#">Sangat Baik</a>
                    <a href="#">Baik</a>
                    <a href="#">Cukup</a>
                    <a href="#">Kurang</a>
                </div>
                <input type="hidden" id="Tajwid" name="Tajwid" required>
            </div>
            <div class="form-group dropdown">
                <label for="Makhrajul_Huruf">Makhrajul Huruf:</label>
                <button class="dropbtn">Pilih Makhrajul Huruf</button>
                <div class="dropdown-content">
                    <a href="#">Sangat Baik</a>
                    <a href="#">Baik</a>
                    <a href="#">Cukup</a>
                    <a href="#">Kurang</a>
                </div>
                <input type="hidden" id="Makhrajul_Huruf" name="Makhrajul_Huruf" required>
            </div>

            <div class="form-group dropdown">
                <label for="Surah">Surah:</label>
                <button class="dropbtn">Pilih Surah</button>
                <div class="dropdown-content">
                    <a href="#">An-Naba’</a>
                    <a href="#">AN-Naazi’at</a>
                    <a href="#">‘Abasa</a>
                    <a href="#">At-Takwir</a>
                    <a href="#">Al-Infithar</a>
                    <a href="#">Al-Muthaffifin</a>
                    <a href="#">Al-Insyiqaaq</a>
                    <a href="#">Al-Buruj</a>
                    <a href="#">Ath-Thaariq</a>
                    <a href="#">Al-A’la</a>
                    <a href="#">Al-Ghaasyiyah
                    <a href="#">Al-Ghaasyiyah</a>
                    <a href="#">Al-Fajr</a>
                    <a href="#">Al-Balad</a>
                    <a href="#">Asy-Syams</a>
                    <a href="#">Al-Lail</a>
                    <a href="#">Adh-Dhuha</a>
                    <a href="#">Al-Insyirah</a>
                    <a href="#">At-Tiin</a>
                    <a href="#">Al-‘Alaq</a>
                    <a href="#">Al-Qadr</a>
                    <a href="#">Al-Bayyinah</a>
                    <a href="#">Az-Zalzalah</a>
                    <a href="#">Al-‘Aadiyaat</a>
                    <a href="#">Al-Qaari’ah</a>
                    <a href="#">At-Takaatsur</a>
                    <a href="#">Al-‘Ashr</a>
                    <a href="#">Al-Humazah</a>
                    <a href="#">Al-Fiil</a>
                    <a href="#">Quraisy</a>
                    <a href="#">Al-Maa’un</a>
                    <a href="#">Al-Kautsar</a>
                    <a href="#">Al-Kaafirun</a>
                    <a href="#">An-Nashr</a>
                    <a href="#">Al-Lahab</a>
                    <a href="#">Al-Ikhlash</a>
                    <a href="#">Al-Falaq</a>
                    <a href="#">An-Naas</a>
                </div>
                <input type="hidden" id="Surah" name="Surah" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Submit" class="submit-button">
            </div>
        </form>
        <?php
        if ($error) {
            echo "<p>$error</p>";
        }
        ?>
    </div>
</body>
</html>
