<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Verify Your Email</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-3">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-2 rounded mb-3">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('verify.submit') }}">
            @csrf

            <div class="mb-4">
                <label for="code" class="block text-sm font-medium">Verification Code</label>
                <input type="text" name="code" id="code" class="mt-1 block w-full border p-2" required>
            </div>

        
            @if ($errors->has('g-recaptcha-response'))
                <span class="text-red-500 text-sm">{{ $errors->first('g-recaptcha-response') }}</span>
            @endif

            {!! NoCaptcha::display() !!}

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-4">
                Verify Email
            </button>
        </form>

   
        <form method="POST" action="{{ route('verify.resend') }}" class="mt-4">
            @csrf
            <button type="submit" class="text-sm text-gray-600 underline">
                Resend Code
            </button>
        </form>

    
        {!! NoCaptcha::renderJs() !!}
    </div>
</x-guest-layout>
