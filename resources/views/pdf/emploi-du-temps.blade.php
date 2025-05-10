<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Emploi du temps - {{ $equipe->nom }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        h1 {
            color: #2563eb;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
        }
        .content {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #e5e7eb;
        }
        th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Emploi du temps</h1>
        <p>{{ $equipe->nom }}</p>
    </div>

    <div class="info">
        <p><strong>Date de génération:</strong> {{ date('d/m/Y') }}</p>
        @if($equipe->responsable)
        <p><strong>Responsable:</strong> {{ $equipe->responsable->first_name }} {{ $equipe->responsable->last_name }}</p>
        @endif
    </div>

    <div class="content">
        <p>Cet emploi du temps a été généré automatiquement par le système de gestion des équipes.</p>
        
        @if($equipe->description)
        <div style="margin: 20px 0; padding: 10px; background-color: #f3f4f6; border-radius: 5px;">
            <p><strong>Description de l'équipe:</strong></p>
            <p>{{ $equipe->description }}</p>
        </div>
        @endif

        <h3>Membres de l'équipe</h3>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Poste</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipe->employes as $employe)
                <tr>
                    <td>{{ $employe->first_name }} {{ $employe->last_name }}</td>
                    <td>{{ $employe->position }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} - Système de gestion des équipes</p>
    </div>
</body>
</html>
