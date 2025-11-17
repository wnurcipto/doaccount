@php
    use App\Models\CompanyInfo;
    use Illuminate\Support\Facades\Storage;
    $company = CompanyInfo::getInfo(auth()->id() ?? null);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Do-ACCOUNT</title>

        <!-- Favicon -->
        @if($company->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($company->logo))
            <link rel="icon" type="image/png" href="{{ Storage::url($company->logo) }}">
            <link rel="shortcut icon" type="image/png" href="{{ Storage::url($company->logo) }}">
        @else
            <link rel="icon" type="image/png" href="{{ route('favicon') }}">
            <link rel="shortcut icon" type="image/png" href="{{ route('favicon') }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Figtree', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            /* Animated Background */
            .bg-animation {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
                overflow: hidden;
            }

            .bg-animation::before,
            .bg-animation::after {
                content: '';
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                animation: float 20s infinite ease-in-out;
            }

            .bg-animation::before {
                width: 500px;
                height: 500px;
                top: -250px;
                left: -250px;
                animation-delay: 0s;
            }

            .bg-animation::after {
                width: 400px;
                height: 400px;
                bottom: -200px;
                right: -200px;
                animation-delay: 5s;
            }

            @keyframes float {
                0%, 100% {
                    transform: translate(0, 0) scale(1);
                }
                33% {
                    transform: translate(50px, 50px) scale(1.1);
                }
                66% {
                    transform: translate(-50px, -50px) scale(0.9);
                }
            }

            /* Floating Particles */
            .particles {
                position: absolute;
                width: 100%;
                height: 100%;
                z-index: 1;
            }

            .particle {
                position: absolute;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                animation: float-particle 15s infinite ease-in-out;
            }

            @keyframes float-particle {
                0%, 100% {
                    transform: translateY(0) translateX(0);
                    opacity: 0.3;
                }
                50% {
                    transform: translateY(-100px) translateX(50px);
                    opacity: 0.6;
                }
            }

            /* Main Container */
            .auth-container {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 450px;
                padding: 20px;
            }

            /* Logo Animation */
            .logo-container {
                text-align: center;
                margin-bottom: 30px;
                animation: fadeInDown 0.8s ease-out;
            }

            .logo-container a {
                display: inline-block;
                transition: transform 0.3s ease;
            }

            .logo-container a:hover {
                transform: scale(1.1) rotate(5deg);
            }

            .company-logo {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                object-fit: cover;
                border: 3px solid white;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                background: white;
                display: inline-block;
            }

            .company-logo-placeholder {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: white;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                font-weight: bold;
                color: #667eea;
                border: 3px solid white;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }

            .company-name {
                margin-top: 15px;
                color: white;
                font-size: 20px;
                font-weight: 600;
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            }

            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Card Animation */
            .auth-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                padding: 40px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                animation: slideUp 0.8s ease-out;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .auth-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Input Animations */
            .input-group {
                position: relative;
                margin-bottom: 25px;
            }

            .input-group input {
                width: 100%;
                padding: 15px 20px;
                border: 2px solid #e0e0e0;
                border-radius: 12px;
                font-size: 16px;
                transition: all 0.3s ease;
                background: #f8f9fa;
            }

            .input-group input:focus {
                outline: none;
                border-color: #667eea;
                background: white;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
            }

            .input-group label {
                display: block;
                margin-bottom: 8px;
                color: #333;
                font-weight: 600;
                font-size: 14px;
                transition: color 0.3s ease;
            }

            .input-group input:focus + label,
            .input-group input:not(:placeholder-shown) + label {
                color: #667eea;
            }

            /* Button Animation */
            .btn-primary {
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
            }

            .btn-primary::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: translate(-50%, -50%);
                transition: width 0.6s, height 0.6s;
            }

            .btn-primary:hover::before {
                width: 300px;
                height: 300px;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            }

            .btn-primary:active {
                transform: translateY(0);
            }

            /* Error Messages */
            .error-message {
                color: #ef4444;
                font-size: 14px;
                margin-top: 5px;
                animation: shake 0.5s ease;
            }

            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-10px); }
                75% { transform: translateX(10px); }
            }

            /* Success Message */
            .success-message {
                background: #10b981;
                color: white;
                padding: 12px 20px;
                border-radius: 10px;
                margin-bottom: 20px;
                animation: slideDown 0.5s ease;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Link Animation */
            .auth-link {
                color: #667eea;
                text-decoration: none;
                transition: all 0.3s ease;
                position: relative;
            }

            .auth-link::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                width: 0;
                height: 2px;
                background: #667eea;
                transition: width 0.3s ease;
            }

            .auth-link:hover::after {
                width: 100%;
            }

            .auth-link:hover {
                color: #764ba2;
            }

            /* Checkbox Animation */
            .checkbox-group {
                display: flex;
                align-items: center;
                margin: 20px 0;
            }

            .checkbox-group input[type="checkbox"] {
                width: 20px;
                height: 20px;
                margin-right: 10px;
                cursor: pointer;
                accent-color: #667eea;
            }

            /* Responsive */
            @media (max-width: 640px) {
                .auth-card {
                    padding: 30px 20px;
                }
            }
        </style>
    </head>
    <body>
        <!-- Animated Background -->
        <div class="bg-animation"></div>
        
        <!-- Floating Particles -->
        <div class="particles" id="particles"></div>

        <!-- Main Container -->
        <div class="auth-container">
            <!-- App Name -->
            <div class="logo-container">
                <div class="company-name" style="font-size: 32px; font-weight: 700; letter-spacing: 2px;">
                    Do-ACCOUNT
                </div>
            </div>

            <!-- Auth Card -->
            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>

        <script>
            // Create floating particles
            function createParticles() {
                const particlesContainer = document.getElementById('particles');
                const particleCount = 20;

                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    const size = Math.random() * 5 + 2;
                    particle.style.width = size + 'px';
                    particle.style.height = size + 'px';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.top = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 15 + 's';
                    particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                    particlesContainer.appendChild(particle);
                }
            }

            // Initialize particles on load
            document.addEventListener('DOMContentLoaded', createParticles);

            // Add ripple effect to buttons
            document.querySelectorAll('.btn-primary').forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(255, 255, 255, 0.5)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s ease-out';
                    ripple.style.pointerEvents = 'none';
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            });

            // Add CSS for ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        </script>
    </body>
</html>
