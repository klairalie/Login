<x-nav-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 via-gray-200 to-gray-300">
        <div class="bg-white/80 backdrop-blur-sm p-8 rounded-md shadow-xl border border-gray-200 w-full max-w-sm transition-transform duration-300 hover:scale-[1.02] relative">

            {{-- LOGIN FORM --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <h1 class="text-3xl font-extrabold text-center text-gray-800 mb-6 tracking-wide">
                    Login to Admin Accounts
                </h1>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email', session('temp_email')) }}" 
                        required 
                        {{ session('otp_phase') ? 'readonly' : '' }}
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
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
                        value="{{ session('temp_password') }}" 
                        required 
                        {{ session('otp_phase') ? 'readonly' : '' }}
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                        placeholder="••••••••"
                    >
                </div>

                {{-- Forgot Password link --}}
                <div class="text-right">
                    <button type="button" onclick="openForgotModal()" class="text-sm text-blue-600 hover:underline">
                        Forgot Password?
                    </button>
                </div>

                {{-- OTP Field (for login) --}}
                @if(session('otp_phase'))
                    <div>
                        <label for="otp" class="block text-sm font-semibold text-gray-700 mb-1">Enter OTP</label>
                        <input 
                            type="text" 
                            name="otp" 
                            id="otp" 
                            required 
                            maxlength="6"
                            class="w-full px-4 py-2 border border-blue-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                            placeholder="6-digit code"
                        >
                    </div>
                @endif

                {{-- Submit --}}
                <div class="pt-3">
                    <button 
                        type="submit" 
                        class="w-full py-2 bg-gradient-to-r from-gray-700 to-black text-white font-semibold rounded-md shadow-md hover:from-black hover:to-gray-800 transition-all"
                    >
                        {{ session('otp_phase') ? 'Verify OTP' : 'Sign in' }}
                    </button>
                </div>

                {{-- Status --}}
                @if(session('status'))
                    <div class="text-green-600 text-sm mt-3 text-center font-semibold">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Errors --}}
                @if($errors->any())
                    <div class="mt-5">
                        <ul class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>

            {{-- FORGOT PASSWORD MODAL --}}
            <div id="forgotModal" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden z-50">
                <div class="bg-white p-6  shadow-lg w-full max-w-md space-y-4 relative">
                    <h2 class="text-xl font-semibold text-center text-gray-800">Forgot Password</h2>

                    <form id="forgotForm" action="{{ route('forgot.password') }}" method="POST">
                        @csrf
                        <div id="stepEmail">
                            <label for="forgotEmail" class="block text-sm font-semibold text-gray-700 mb-1">Enter your email</label>
                            <input 
                                type="email" 
                                name="forgot_email" 
                                id="forgotEmail" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                                placeholder="you@example.com"
                            >
                            <button 
                                type="button"
                                onclick="sendResetOTP()"
                                class="w-full mt-3 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition"
                            >
                                Send OTP
                            </button>
                        </div>

                        <div id="stepVerify" class="hidden">
                            <label for="resetOtp" class="block text-sm font-semibold text-gray-700 mb-1">Enter OTP</label>
                            <input 
                                type="text" 
                                id="resetOtp" 
                                maxlength="6"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md mb-3"
                                placeholder="6-digit OTP"
                            >

                            <label for="newPassword" class="block text-sm font-semibold text-gray-700 mb-1">New Password</label>
                            <input 
                                type="password" 
                                id="newPassword" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md mb-3"
                                placeholder="New password"
                            >

                            <label for="confirmPassword" class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                            <input 
                                type="password" 
                                id="confirmPassword" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md mb-3"
                                placeholder="Confirm password"
                            >

                            <button 
                                type="button"
                                onclick="verifyResetOTP()"
                                class="w-full py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition"
                            >
                                Reset Password
                            </button>
                        </div>
                    </form>

                    <button onclick="closeForgotModal()" class="absolute top-3 right-3 text-gray-500 hover:text-black">✕</button>
                </div>
            </div>

        </div>
    </div>

    {{-- JS Logic for Modal --}}
    <script>
        function openForgotModal() {
            document.getElementById('forgotModal').classList.remove('hidden');
        }

        function closeForgotModal() {
            document.getElementById('forgotModal').classList.add('hidden');
        }

        function sendResetOTP() {
            const email = document.getElementById('forgotEmail').value;
            if (!email) {
                alert('Please enter your email first.');
                return;
            }

            fetch("{{ route('forgot.password') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ email }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("OTP sent to your email!");
                    document.getElementById('stepEmail').classList.add('hidden');
                    document.getElementById('stepVerify').classList.remove('hidden');
                } else {
                    alert(data.message || "Email not found.");
                }
            });
        }

        function verifyResetOTP() {
            const otp = document.getElementById('resetOtp').value;
            const password = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmPassword').value;

            if (!otp || !password || !confirm) {
                alert('Please fill all fields.');
                return;
            }

            if (password !== confirm) {
                alert('Passwords do not match.');
                return;
            }

            fetch("{{ route('forgot.verify.ajax') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ otp, password }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Password successfully reset! You can now log in.");
                    closeForgotModal();
                } else {
                    alert(data.message || "Invalid OTP.");
                }
            });
        }
    </script>
</x-nav-layout>
