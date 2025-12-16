<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>@yield('title', 'Dashboard')</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">


<div class="flex min-h-screen">
<!-- Sidebar -->
<aside class="w-64 bg-blue-700 text-white p-6">
<h2 class="text-xl font-bold mb-6">Puskesmas</h2>
<ul class="space-y-3">
<li class="font-semibold">Dashboard</li>
</ul>
</aside>


<!-- Content -->
<main class="flex-1 p-8">
@yield('content')
</main>
</div>


</body>
</html>