<!-- resources/views/nama_view_pdf.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penilaian</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Laporan Penilaian</h1>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Nama Karyawan</th>
                <th>Data V</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penilaian as $hasil)
                <tr>
                    <td>{{ $hasil['rank'] }}</td>
                    <td>{{ $hasil['nama_karyawan'] }}</td>
                    <td>{{ $hasil['data_v'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
