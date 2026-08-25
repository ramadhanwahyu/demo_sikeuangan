<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Dashboard Admin</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-semibold">Total Santri</h2>
                <p class="text-3xl">{{ $totalSantri }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-semibold">Total Bendahara</h2>
                <p class="text-3xl">{{ $totalBendahara }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-semibold">Tahun Ajaran Aktif</h2>
                <p class="text-3xl">{{ $totalTahunAjaranAktif }}</p>
            </div>
        </div>
    </div>
</body>
</html>