<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
            <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Figtree', sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: #333;
            }

            .container {
                text-align: center;
                background: white;
                padding: 3rem;
                border-radius: 1rem;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                max-width: 600px;
                width: 90%;
            }

            .logo {
                font-size: 3rem;
                font-weight: 600;
                color: #667eea;
                margin-bottom: 1rem;
            }

            .tagline {
                font-size: 1.25rem;
                color: #666;
                margin-bottom: 2rem;
            }

            .links {
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
            }

            .link {
                display: inline-block;
                padding: 0.75rem 1.5rem;
                background: #667eea;
                color: white;
                text-decoration: none;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
                font-weight: 600;
            }

            .link:hover {
                background: #5568d3;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            }

            .link.secondary {
                background: transparent;
                border: 2px solid #667eea;
                color: #667eea;
            }

            .link.secondary:hover {
                background: #667eea;
                color: white;
            }

            .version {
                margin-top: 2rem;
                color: #999;
                font-size: 0.875rem;
            }
            </style>
    </head>
    <body>
        <div class="container">
            <div class="logo">Laravel</div>
            <div class="tagline">The PHP Framework for Web Artisans</div>
            
            <div class="links">
                    @auth
                    <a href="{{ url('/dashboard') }}" class="link">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="link">Login</a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="link secondary">Register</a>
                        @endif
                    @endauth
                </div>

            <div class="version">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
        </div>
    </body>
</html>

