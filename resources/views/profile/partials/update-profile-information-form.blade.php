<section>
    <style>
        .profile-section {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-header {
            margin-bottom: 25px;
        }

        .section-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-header p {
            color: #666;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 25px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 15px 20px 15px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
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

        .button-group {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 30px;
        }

        .save-btn {
            padding: 12px 30px;
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
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .save-btn:active {
            transform: translateY(0);
        }

        .success-message {
            color: #10b981;
            font-size: 14px;
            font-weight: 500;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .verification-notice {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .verification-notice p {
            color: #92400e;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .verification-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .verification-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .verification-sent {
            color: #10b981;
            font-size: 14px;
            font-weight: 500;
            margin-top: 10px;
            animation: slideIn 0.5s ease;
        }
    </style>

    <div class="profile-section">
        <header class="section-header">
            <h2>Informasi Profil</h2>
            <p>Perbarui informasi profil dan alamat email akun Anda.</p>
        </header>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <!-- Name -->
            <div class="input-wrapper">
                <label for="name" class="input-label">Nama</label>
                <input 
                    id="name" 
                    name="name" 
                    type="text" 
                    value="{{ old('name', $user->name) }}" 
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

            <!-- Email -->
            <div class="input-wrapper">
                <label for="email" class="input-label">Email</label>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    value="{{ old('email', $user->email) }}" 
                    required 
                    autocomplete="username"
                    placeholder="Masukkan email"
                />
                <span class="input-icon">📧</span>
                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="verification-notice">
                        <p>
                            Alamat email Anda belum diverifikasi.
                            <button form="send-verification" class="verification-link">
                                Klik di sini untuk mengirim ulang email verifikasi.
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <div class="verification-sent">
                                Link verifikasi baru telah dikirim ke alamat email Anda.
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Button Group -->
            <div class="button-group">
                <button type="submit" class="save-btn">
                    Simpan Perubahan
                </button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="success-message"
                    >✓ Tersimpan!</p>
                @endif
            </div>
        </form>
    </div>
</section>
