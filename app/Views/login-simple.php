<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="h-full flex items-center justify-center font-sans">
    <div class="max-w-2xl w-full bg-white p-16 rounded-xl shadow-lg border border-gray-300">
        <h1 class="text-4xl font-extrabold mb-10 text-center text-blue-700 tracking-wide drop-shadow-lg">Login</h1>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-red-700 bg-opacity-70 border border-red-500 text-red-200 px-4 py-3 rounded mb-6 shadow-lg" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="bg-green-700 bg-opacity-70 border border-green-500 text-green-200 px-4 py-3 rounded mb-6 shadow-lg" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="post" class="space-y-8">
            <div>
                <label for="username" class="block text-xl font-bold text-blue-700 mb-4 tracking-wide">
                    <i class="fas fa-user mr-2"></i>Username or Email
                </label>
                <input type="text" name="username" id="username" required
                    value="<?= old('username') ?>"
                    class="w-full h-16 px-6 py-4 text-lg rounded-lg border-2 border-blue-400 bg-white text-blue-900 placeholder-blue-500 shadow-lg focus:border-blue-500 focus:ring-blue-500 focus:ring-2 transition duration-300" 
                    placeholder="Masukkan username atau email" />
            </div>
            
            <div>
                <label for="password" class="block text-xl font-bold text-blue-700 mb-4 tracking-wide">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="w-full h-16 px-6 py-4 pr-16 text-lg rounded-lg border-2 border-blue-400 bg-white text-blue-900 placeholder-blue-500 shadow-lg focus:border-blue-500 focus:ring-blue-500 focus:ring-2 transition duration-300" 
                        placeholder="Masukkan password" />
                    <button type="button" id="togglePassword" 
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        <i class="fas fa-eye text-xl" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" name="remember_me" id="remember_me" 
                        class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <label for="remember_me" class="ml-3 text-lg text-blue-700 font-semibold">
                        <i class="fas fa-user-check mr-2"></i>Ingat Saya
                    </label>
                </div>
                <a href="#" class="text-lg text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
                    Lupa Password?
                </a>
            </div>
            
            <div>
                <button type="submit"
                    class="w-full py-5 rounded-lg bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 text-white text-xl font-bold shadow-lg hover:from-blue-700 hover:via-blue-800 hover:to-blue-900 transition duration-300 tracking-wide transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-3"></i>Login
                </button>
            </div>
        </form>
    </div>

    <script>
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Remember Me functionality
        document.addEventListener('DOMContentLoaded', function() {
            const usernameField = document.getElementById('username');
            const rememberCheckbox = document.getElementById('remember_me');
            
            // Load saved username if exists
            const savedUsername = localStorage.getItem('remembered_username');
            if (savedUsername) {
                usernameField.value = savedUsername;
                rememberCheckbox.checked = true;
            }
            
            // Save username when form is submitted
            document.querySelector('form').addEventListener('submit', function() {
                if (rememberCheckbox.checked) {
                    localStorage.setItem('remembered_username', usernameField.value);
                } else {
                    localStorage.removeItem('remembered_username');
                }
            });
        });
    </script>
</body>
</html>
