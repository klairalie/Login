<x-nav-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 via-gray-200 to-gray-300">
        <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-xl border border-gray-200 w-full max-w-sm transition-transform duration-300 hover:scale-[1.02]">

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <h1 class="text-3xl font-extrabold text-center text-gray-800 mb-6 tracking-wide">Login to Admin Accounts</h1>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all duration-200 placeholder-gray-400"
                        placeholder="you@example.com"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all duration-200 placeholder-gray-400"
                        placeholder="••••••••"
                    >
                </div>

                {{-- Submit Button --}}
                <div class="pt-3">
                    <button 
                        type="submit" 
                        class="w-full py-2 bg-gradient-to-r from-gray-700 to-black text-white font-semibold rounded-md shadow-md hover:from-black hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                    >
                        Sign in
                    </button>
                </div>
            </form>

            {{-- Error Messages --}}
            @if($errors->any())
                <div class="mt-5">
                    <ul class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach 
                    </ul>
                </div>
            @endif

            {{-- Register Link
            <p class="mt-6 text-center text-gray-700 text-sm font-medium">
                Don't have an account?
                <a href="{{ route('show.register') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-150">
                    Register here
                </a>.
            </p> --}}
        </div>
    </div>
</x-nav-layout>
