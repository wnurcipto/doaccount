<x-guest-layout>
    <style>
        .form-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .form-title h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-title p {
            color: #666;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 25px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 15px 50px 15px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .input-wrapper.password-wrapper input {
            padding-right: 50px;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            transition: color 0.3s ease;
        }

        .input-wrapper input:focus + .input-icon {
            color: #667eea;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #999;
            font-size: 18px;
            padding: 5px;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .password-toggle:focus {
            outline: none;
        }

        .input-label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .error-text {
            color: #ef4444;
            font-size: 13px;
            margin-top: 5px;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }

        .login-link p {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>

    <div class="form-title">
        <h2>Buat Akun Baru</h2>
        <p>Daftar untuk memulai menggunakan sistem</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="input-wrapper">
            <label for="name" class="input-label">Nama Lengkap</label>
            <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="Masukkan nama lengkap"
            />
            <span class="input-icon">👤</span>
            @error('name')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="input-wrapper">
            <label for="email" class="input-label">Email</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autocomplete="username"
                placeholder="Masukkan email Anda"
            />
            <span class="input-icon">📧</span>
            @error('email')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="input-wrapper password-wrapper">
            <label for="password" class="input-label">Password</label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="Masukkan password"
            />
            <span class="input-icon">🔒</span>
            <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                <span id="password-toggle-icon">👁️</span>
            </button>
            @error('password')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="input-wrapper password-wrapper">
            <label for="password_confirmation" class="input-label">Konfirmasi Password</label>
            <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="Konfirmasi password"
            />
            <span class="input-icon">🔐</span>
            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')" aria-label="Toggle password visibility">
                <span id="password_confirmation-toggle-icon">👁️</span>
            </button>
            @error('password_confirmation')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="submit-btn">
            Daftar
        </button>
    </form>

    <!-- Login Link -->
    <div class="login-link">
        <p>Sudah punya akun?</p>
        <a href="{{ route('login') }}">Masuk sekarang</a>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-toggle-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '🙈';
            } else {
                input.type = 'password';
                icon.textContent = '👁️';
            }
        }
    </script>
</x-guest-layout>
