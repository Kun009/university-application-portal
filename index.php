<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniApply</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(135deg, #d9d9d9 25%, transparent 25%, transparent 75%, #d9d9d9 75%, #d9d9d9), linear-gradient(225deg, #d9d9d9 25%, transparent 25%, transparent 75%, #d9d9d9 75%, #d9d9d9);
            background-size: 20px 20px;
            background-color: #f3f4f6; /* fallback color */
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen">

    <div class="text-center bg-white p-10 rounded-lg shadow-lg">
        <h1 class="text-4xl font-bold mb-5">Welcome to UniApply</h1>
        <p class="text-gray-700 mb-10">Choose your role to proceed.</p>

        <div class="grid grid-cols-1 gap-6">
            <a href="/application/admission/index.php" class="block bg-blue-500 text-white py-3 rounded hover:bg-blue-600 transition duration-200">
                <h2 class="text-2xl font-semibold">Admin</h2>
                <p class="text-sm">Manage all university applications and settings.</p>
            </a>
            <a href="/application/officer/index.php" class="block bg-green-500 text-white py-3 rounded hover:bg-green-600 transition duration-200">
                <h2 class="text-2xl font-semibold">Admission Officer</h2>
                <p class="text-sm">Review applications and communicate with applicants.</p>
            </a>
            <a href="/application/applicant/index.php" class="block bg-indigo-500 text-white py-3 rounded hover:bg-indigo-600 transition duration-200">
                <h2 class="text-2xl font-semibold">Applicant</h2>
                <p class="text-sm">Submit applications and track your progress.</p>
            </a>
        </div>
    </div>

</body>
</html>
