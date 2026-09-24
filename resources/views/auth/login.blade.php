<x-guest-layout>
    <style>
        /* Modern Background & Animations */
        body {
            background: linear-gradient(-45deg, #0d6efd, #6610f2, #6f42c1, #0dcaf0);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Animated Login Card (Fade In & Slide Up) */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.8s ease-out forwards;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Floating Inputs & Hover Effects */
        .form-control-custom {
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .form-control-custom:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.25);
            transform: translateY(-2px);
        }

        /* Animated Button */
        .btn-animate {
            background: linear-gradient(45deg, #0d6efd, #0d47a1);
            border: none;
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn-animate:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
            background: linear-gradient(45deg, #0b5ed7, #0a3880);
        }

        .btn-animate:active {
            transform: translateY(0);
        }

        /* Branding Icon pulse */
        .brand-icon {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>

    <div class="login-card">
        <!-- Logo & Header -->
        <div class="text-center mb-4">
            <img src="{{ asset('images/alhuda-logo.svg') }}" alt="Alhuda Primary and Intermediate School" class="img-fluid mb-2" style="max-height: 150px;">
            <p class="text-muted small mt-1">Geli xogtaada si aad u gasho nidaamka</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" class="fw-semibold text-secondary" />
                <x-text-input id="email" class="block mt-1 w-full form-control-custom" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" class="fw-semibold text-secondary" />
                <x-text-input id="password" class="block mt-1 w-full form-control-custom"
                                type="password"
                                name="password"
                                required autocomplete="current-password" 
                                placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-4">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="underline text-sm text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full text-white btn-animate">
                <i class="fa-solid fa-right-to-bracket me-2"></i> {{ __('Log in') }}
            </button>

            <!-- Register Link -->
            @if (Route::has('register'))
                <div class="text-center mt-4">
                    <span class="text-sm text-gray-600">Akaunti ma lehid?</span>
                    <a href="{{ route('register') }}" class="text-sm text-indigo-600 font-semibold hover:underline ms-1">
                        Register Hada
                    </a>
                </div>
            @endif
        </form>
    </div>
</x-guest-layout>