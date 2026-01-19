<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>List Project Siswa Jurusan Tata Busana</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        @page {
            margin: 80px 40px 60px 40px;
        }

        .pdf-header {
            position: fixed;
            top: -60px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #000;
        }

        th {
            background: #f2f2f2;
            width: fit-content;
        }
    </style>
</head>

<body>

    <header>
        <div class="pdf-header">
            <strong>List Project Siswa Jurusan Tata Busana</strong>
            <br>
            <strong>SMKN 8 Surabaya</strong>
        </div>
    </header>

    <main>
        <table>

            <thead>
                <tr>
                    <th>
                        No
                    </th>
                    <th>
                        Nama
                    </th>
                    <th>
                        Kelas
                    </th>
                    <th>
                        Angkatan
                    </th>
                    <th>
                        Nama Project
                    </th>
                    <th>
                        Nilai
                    </th>
                </tr>
            </thead>
            @foreach ($projects as $project => $item)
                <tbody>
                    <tr>
                        <td>{{ $project + 1 }}</td>
                        <td>{{ $item->user->nama }}</td>
                        <td>{{ $item->user->kelas ? $item->user->kelas : '-' }}</td>
                        <td>{{ $item->user->angkatan ? $item->user->angkatan : '-' }}</td>
                        <td>{{ $item->nama_project }}</td>
                        <td>{{ $item->nilai ? $item->nilai : '0' }}</td>
                    </tr>
                </tbody>
            @endforeach
        </table>
    </main>

</body>

</html>
