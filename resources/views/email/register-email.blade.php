<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de E-mail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f0f0f0;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
        }
        .confirmation-link {
            display: block;
            margin-top: 20px;
            background-color: #131416;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Confirmação de E-mail</h1>
        <p>Obrigado por se cadastrar na <strong>DomesTK</strong>. Por favor, informe o código abaixo para confirmar o seu e-mail:</p>
        <p class="confirmation-link"><strong>{{$code}}</strong></p>
        <div class="footer">
            <p>Não é necessário responder a este e-mail. Este é um e-mail automático.</p>
        </div>
    </div>
</body>
</html>
