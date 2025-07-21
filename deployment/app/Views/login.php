<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex items-center justify-center font-sans">
    <div class="max-w-2xl w-full bg-white p-16 rounded-xl shadow-lg border border-gray-300">
        <h1 class="text-4xl font-extrabold mb-10 text-center text-blue-700 tracking-wide drop-shadow-lg">Login</h1>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-red-700 bg-opacity-70 border border-red-500 text-red-200 px-4 py-3 rounded mb-6 shadow-lg" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if(isset($validation)): ?>
            <div class="bg-red-700 bg-opacity-70 border border-red-500 text-red-200 px-4 py-3 rounded mb-6 shadow-lg" role="alert">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="post" class="space-y-8">
            <?= csrf_field() ?>            <div>
                <label for="username" class="block text-xl font-bold text-blue-700 mb-4 tracking-wide">Username or Email</label>
                <input type="text" name="username" id="username" value="<?= set_value('username') ?>" required
                    class="w-full h-16 px-6 py-4 text-lg rounded-lg border-2 border-blue-400 bg-white text-blue-900 placeholder-blue-500 shadow-lg focus:border-blue-500 focus:ring-blue-500 focus:ring-2 transition duration-300" />
            </div>            <div>
                <label for="password" class="block text-xl font-bold text-blue-700 mb-4 tracking-wide">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="w-full h-16 px-6 py-4 text-lg rounded-lg border-2 border-blue-400 bg-white text-blue-900 placeholder-blue-500 shadow-lg focus:border-blue-500 focus:ring-blue-500 focus:ring-2 transition duration-300 pr-20" />
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <div class="h-10 w-px bg-blue-300 mx-3"></div>
                        <button type="button" id="togglePassword" aria-label="Toggle password visibility"
                            class="flex items-center pr-5 text-blue-500 hover:text-blue-700 focus:outline-none">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 hidden" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.434M6.18 6.18A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-1.167 2.36M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>            <div>
                <button type="submit"
                    class="w-full py-5 rounded-lg bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 text-white text-xl font-bold shadow-lg hover:from-blue-700 hover:via-blue-800 hover:to-blue-900 transition duration-300 tracking-wide">
                    Login
                </button>
            </div>
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('hidden');
            eyeOffIcon.classList.toggle('hidden');
        });
    </script>
</body>
</html>
