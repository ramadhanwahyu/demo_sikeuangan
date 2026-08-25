<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Orang Tua</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Dashboard Orang Tua</h1>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Santri</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tingkat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Saldo Uang Jajan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($santriList as $santri)
                    <tr>
                        <td class="px-6 py-4">{{ $santri->nis }}</td>
                        <td class="px-6 py-4">{{ $santri->nama_lengkap }}</td>
                        <td class="px-6 py-4">{{ $santri->tingkat->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $santri->kelas->nama ?? '-' }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($santri->saldo_uang_jajan, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>