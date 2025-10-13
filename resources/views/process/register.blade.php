<x-guest-layout>

    <div class="login-c">
        <form action="{{ route('register')}}" class="loginform" method="POST">
            <h1 class="text-2xl font-bold mb-4">Sign Up</h1>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-lg border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gradient-to-b from-white to-gray-400 focus:bg-black">
            </div>
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="mt-1 block w-lg border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gradient-to-b from-white to-gray-400 focus:bg-black">
            </div>
                <div class="mb-4">
                  <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="mt-1 block w-lg border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gradient-to-b from-white to-gray-400 focus:bg-black">
                </div>
                <div class="mb-4">
                 <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}" required class="mt-1 block w-lg border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gradient-to-b from-white to-gray-400 focus:bg-black">
                </div>
            <div class="mb-4">
                <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                <input type="number" name="age" id="age" readonly
                    class="mt-1 block w-lg border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-100">
            </div>
               
           <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}" required class="mt-1 block w-lg border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gradient-to-b from-white to-gray-400 focus:bg-black">
            </div>

            <div class="mb-4">
                <label for="contact" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="number" name="contact" id="contact" value="{{ old('contact') }}" required class="mt-1 block w-lg border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gradient-to-b from-white to-gray-400 focus:bg-black">
            </div>

              <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">User Name</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required class="mt-1 block w-lg border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gradient-to-b from-white to-gray-400 focus:bg-black">
            </div>

             <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required class="mt-1 block w-lg border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gradient-to-b from-white to-gray-400 focus:bg-black">
            </div>


            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-lg border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gradient-to-b from-white to-gray-400 focus:bg-gray-200">
            </div>
            <div class="mb-4">
            <label for="user_type" class="block text-sm font-medium text-gray-700">User Type</label>
                <select name="user_type" id="user_type" required class="mt-1 block w-lg border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gradient-to-b from-white to-gray-400 focus:bg-gray-400">
                    <option value="">Select an option</option>
                    <option value="admin">Admin</option>
                    <option value="hr"> Hr </option>
                    <option value="finance"> Finance </option>
                    <option value="employee">Employee</option>
                </select>
            </div>
            @csrf
            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-black">Sign In</button>
            


        </form>

        @if($errors->any())
            <div class="mt-4">
                <ul class="bg-red-100 text-red-700 p-4 rounded-md">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach 
                </ul>
            </div>

        @endif
        <p class="mt-4 text-center">Already have an account? <a href="{{ route('show.login') }}" class="text-blue-500 hover:underline">Login here</a>.</p>  
            @if(session('success'))
    <script>
        window.alert(@json(session('success')));
    </script>
        @endif  
    </div>




</x-guest-layout>