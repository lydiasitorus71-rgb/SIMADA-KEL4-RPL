<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara {{ ucfirst($jenis) }} Pemenang</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 2cm;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h1 {
            font-size: 16pt;
            margin: 0;
            text-transform: uppercase;
        }
        .kop-surat h2 {
            font-size: 18pt;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-surat p {
            font-size: 12pt;
            margin: 5px 0 0 0;
        }
        .judul-dokumen {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .info-paket th {
            background-color: #f0f0f0;
            width: 30%;
        }
        .ttd-container {
            margin-top: 50px;
            width: 100%;
        }
        .ttd-box {
            float: right;
            width: 300px;
            text-align: center;
        }
        .ttd-nama {
            margin-top: 80px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h1>PEMERINTAH KABUPATEN KEBUMEN</h1>
        <h2>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG</h2>
        <p>Jl. Jend. A. Yani No. 50 Kebumen</p>
    </div>

    <div class="judul-dokumen">
        @if($jenis === 'penetapan')
            BERITA ACARA PENETAPAN PEMENANG TENDER
        @else
            BERITA ACARA PENGUMUMAN PEMENANG TENDER
        @endif
    </div>

    <p>Pada hari ini, telah diterbitkan dokumen berita acara untuk paket pengadaan berikut:</p>

    <table class="info-paket">
        <tr>
            <th>Nama Paket</th>
            <td>{{ $paket->nama_paket }}</td>
        </tr>
        <tr>
            <th>Pagu Anggaran</th>
            <td>Rp {{ number_format($paket->pagu_anggaran, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Target Timeline</th>
            <td>{{ $paket->waktu_pelaksanaan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Pemenang Tender</th>
            <td>{{ $paket->pemenang_tender ?? '-' }}</td>
        </tr>
        <tr>
            <th>Realisasi Kontrak</th>
            <td>Rp {{ number_format($paket->realisasi_kontrak, 2, ',', '.') }}</td>
        </tr>
    </table>

    <p>Berikut adalah susunan panitia (Pokja) yang bertugas:</p>

    <table>
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">No.</th>
                <th>Nama Lengkap</th>
                <th>NIP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paket->personils as $index => $personil)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $personil->nama_lengkap }}</td>
                <td>{{ $personil->nip ?? '-' }}</td>
            </tr>
            @endforeach
            @if($paket->personils->isEmpty())
            <tr>
                <td colspan="3" style="text-align: center;">Belum ada panitia yang ditugaskan.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <p>Demikian Berita Acara ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagaimana mestinya.</p>

    <div class="ttd-container">
        <div class="ttd-box">
            <p>Kebumen, {{ date('d F Y') }}</p>
            <p>Pejabat Pembuat Komitmen (PPK)</p>
            <div class="ttd-nama">( ........................................ )</div>
            <p>NIP. ....................................</p>
        </div>
    </div>

</body>
</html>
