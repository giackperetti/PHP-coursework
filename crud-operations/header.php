<head>
    <title>Gestione Persone</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Helvetica;
            line-height: 1.6;
            color: #cdd6f4;
            background-color: #1e1e2e;
        }

        h1 {
            color: #f38ba8;
        }

        .navbar {
            background-color: #11111b;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .container {
            width: 85%;
            margin: 0 auto;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
        }

        .nav-links a {
            color: #b4befe;
            text-decoration: none;
        }

        .nav-group {
            display: flex;
            gap: 1rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #313244;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #cdd6f4;
            background-color: #313244;
        }

        .table th {
            color: #eba0ac;
            background-color: #45475a;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        input:focus {
            width: 100%;
            padding: 8px;
            border: 1px solid #cba6f7;
            border-radius: 5px;
            background-color: #45475a;
            color: #cdd6f4;
        }

        .btn {
            padding: 10px 15px;
            border: 1px solid #cba6f7;
            border-radius: 5px;
            background-color: #45475a;
            color: #cdd6f4;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .success {
            background: #d4edda;
            color: #a6e3a1;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .error {
            background: #f5c2e7;
            color: #f38ba8;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-links">
                <div class="nav-group">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="index.php">Lista Persone</a>
                        <a href="create_update.php">Nuova Persona</a>
                    <?php endif; ?>
                </div>
                <div class="nav-group">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                        <a href="create_update.php">Registrati</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">