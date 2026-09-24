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

        /* Animated Register Card (Fade In & Slide Up) */
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.8s ease-out forwards;
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
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
            padding: 0.65rem 1rem;
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

    <div class="register-card my-4">
        <!-- Logo & Header -->
        <div class="text-center mb-3">
            <img src="{{ asset('images/alhuda-logo.svg') }}" alt="Alhuda Primary and Intermediate School" class="img-fluid mb-1" style="max-height: 150px;">
            <h3 class="fw-bold text-dark m-0">Register</h3>
            <p class="text-muted small mt-1">Samee akoon cusub oo maamul dugsiga</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-3" :status="session('status')" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <x-input-label for="name" :value="__('Name')" class="fw-semibold text-secondary" />
                <x-text-input id="name" class="block mt-1 w-full form-control-custom" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Magacaaga oo buuxa" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" class="fw-semibold text-secondary" />
                <x-text-input id="email" class="block mt-1 w-full form-control-custom" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Select Role -->
            <div class="mb-3">
                <x-input-label for="role" :value="__('Select Role')" class="fw-semibold text-secondary" />
                <select name="role" id="role" class="block mt-1 w-full form-control-custom bg-white" required>
                    <option value="teacher" selected>Teacher</option>
                    <option value="manager">Manager</option>
                    <option value="admin">Admin</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" class="fw-semibold text-secondary" />
                <x-text-input id="password" class="block mt-1 w-full form-control-custom"
                                type="password"
                                name="password"
                                required autocomplete="new-password" 
                                placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="fw-semibold text-secondary" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full form-control-custom"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" 
                                placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Actions & Submit -->
            <div class="flex items-center justify-between mt-2">
                <a class="underline text-sm text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <button type="submit" class="text-white btn-animate px-4 py-2">
                    <i class="fa-solid fa-user-plus me-1"></i> {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>