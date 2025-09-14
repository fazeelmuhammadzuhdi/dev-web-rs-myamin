<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Linktree Bio Instagram</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #f0f4f8;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 4px solid #4a90e2;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 1.5rem;
        }

        .link {
            display: block;
            background: #4a90e2;
            color: white;
            text-decoration: none;
            padding: 0.8rem;
            margin: 0.5rem 0;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .link:hover {
            background: #357ABD;
        }

        @media (max-width: 480px) {
            .container {
                padding: 1.5rem;
                border-radius: 0;
                height: 100vh;
            }

            .profile-img {
                width: 80px;
                height: 80px;
            }

            h1 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <img src="https://via.placeholder.com/100" alt="Foto Profil" class="profile-img" />
        <h1>Nama Kamu</h1>
        <p>Deskripsi singkat, misalnya: Mahasiswa Hukum | Blogger</p>

        <a class="link" href="https://link1.com" target="_blank">Website Pribadi</a>
        <a class="link" href="https://link2.com" target="_blank">YouTube Channel</a>
        <a class="link" href="https://link3.com" target="_blank">Instagram</a>
        <a class="link" href="https://link4.com" target="_blank">Kontak via WhatsApp</a>
    </div>

</body>

</html>