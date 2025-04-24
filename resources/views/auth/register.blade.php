<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat px-4" style="background-image: url('{{ asset('images/login-bg.jpg') }}');">
        <!-- Background overlay to match the login page -->
        <div class="absolute inset-0 bg-indigo-100/75 backdrop-blur-sm"></div>
        
        <!-- Card Container - Width increased -->
        <div class="w-full max-w-2xl relative z-10">
            <!-- Card with white background -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Colorful accent bar at top -->
                <div class="h-1.5 bg-gradient-to-r from-blue-500 via-teal-400 to-green-400"></div>
                
                <div class="px-10 py-6">
                    <!-- Logo using the provided image - Decreased height -->
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('images/tripvibe-logo.png') }}" alt="TripVibe Logo" class="h-12">
                    </div>
                    
                    <!-- Title - Reduced spacing -->
                    <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">Create Account</h2>
                    <p class="text-center text-gray-500 mb-4">Join TripVibe and start your journey</p>
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <!-- Name - Vertical arrangement preserved -->
                        <div class="mb-3">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-500" />
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="your-email@example.com"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </div>
                                <input id="password" type="password" name="password" required placeholder="•••••••"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500" />
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </div>
                                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="•••••••"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-500" />
                        </div>
                        
                        <!-- Button - Same blue as in login page with arrow -->
                        <div class="mt-4">
                            <button type="submit"
                                class="w-full bg-blue-600 text-white font-medium py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex justify-center items-center">
                                <span>Create Account</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Login Link - Styled like register link on login page -->
                        <div class="mt-4 text-center text-sm text-gray-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                Log in →
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Footer with travel inspiration - Same as login page -->
                <div class="p-3 text-center text-xs text-gray-500">
                    "The journey of a thousand miles begins with a single step" — Lao Tzu
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>