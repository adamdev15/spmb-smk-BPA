<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PMBM MAN 1 Tegal | TP 2026-2027</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root{
            --hijau-kemenag:#1b8f3a;
            --hijau-soft:#e8f5ec;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins', sans-serif;
        }

        body{
            min-height:100vh;
            background:linear-gradient(135deg, var(--hijau-soft), #ffffff);
            display:flex;
            justify-content:center;
            align-items:center;
            padding:20px;
        }

        .container{
            background:#ffffff;
            max-width:900px;
            width:100%;
            border-radius:20px;
            padding:40px 30px;
            text-align:center;
            box-shadow:0 20px 40px rgba(0,0,0,.08);
        }

        .logo img{
            width:140px;
            max-width:40vw;
            margin-bottom:20px;
        }

        h1{
            color:var(--hijau-kemenag);
            font-size:2rem;
            font-weight:700;
        }

        h2{
            margin-top:5px;
            font-weight:400;
            color:#555;
        }

        .badge{
            display:inline-block;
            margin:25px 0;
            padding:10px 25px;
            border-radius:50px;
            background:var(--hijau-kemenag);
            color:#fff;
            font-weight:600;
            letter-spacing:1px;
        }

        .countdown{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:15px;
            margin-top:30px;
        }

        .time-box{
            background:var(--hijau-soft);
            border-radius:15px;
            padding:20px 10px;
        }

        .time-box span{
            display:block;
            font-size:2rem;
            font-weight:700;
            color:var(--hijau-kemenag);
        }

        .time-box small{
            font-size:.85rem;
            color:#555;
        }

        footer{
            margin-top:35px;
            font-size:.85rem;
            color:#777;
        }

        @media(max-width:600px){
            h1{
                font-size:1.5rem;
            }
            .countdown{
                grid-template-columns:repeat(2,1fr);
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="logo-MAN-1-Tegal-1.png" alt="Logo MAN 1 Tegal">
    </div>

    <h1>PMBM MAN 1 Tegal</h1>
    <h2>Tahun Pelajaran 2026 / 2027</h2>

    <div class="badge">SEGERA DIBUKA</div>

    <div class="countdown" id="countdown">
        <div class="time-box">
            <span id="days">0</span>
            <small>Hari</small>
        </div>
        <div class="time-box">
            <span id="hours">0</span>
            <small>Jam</small>
        </div>
        <div class="time-box">
            <span id="minutes">0</span>
            <small>Menit</small>
        </div>
        <div class="time-box">
            <span id="seconds">0</span>
            <small>Detik</small>
        </div>
    </div>

    <footer>
        © 2026 MAN 1 Tegal • Penerimaan Murid Baru Madrasah
    </footer>
</div>

<script>
    const targetDate = new Date("2026-02-14T00:00:00").getTime();

    const countdown = setInterval(() => {
        const now = new Date().getTime();
        const distance = targetDate - now;

        if (distance < 0) {
            clearInterval(countdown);
            document.getElementById("countdown").innerHTML = 
                "<strong>Pendaftaran Telah Dibuka</strong>";
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("days").innerText = days;
        document.getElementById("hours").innerText = hours;
        document.getElementById("minutes").innerText = minutes;
        document.getElementById("seconds").innerText = seconds;
    }, 1000);
</script>

</body>
</html>