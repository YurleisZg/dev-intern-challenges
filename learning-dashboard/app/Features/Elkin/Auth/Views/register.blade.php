<x-layoutDasboard>
  <div class="flex items-center justify-center mt-8">
    <div class="max-w-md bg-white border rounded-2xl p-6 shadow">
      <h1 class="text-2xl font-bold">Create Account</h1>
      <p class="text-sm text-gray-600 mt-1">Register to continue.</p>

      @if ($errors->any())
        <div class="mt-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
          <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="mt-6 space-y-4" method="POST" action="{{ route('elkin.challenges.auth.register.store') }}">
        @csrf

        <div>
          <label class="text-sm font-medium">Name</label>
          <input name="name" placeholder="Jhon Doe"
          type="text" value="{{ old('name') }}" class="mt-1 w-full rounded-lg border p-2" required>
        </div>

        <div>
          <label class="text-sm font-medium">Email</label>
          <input name="email" placeholder="youremail@example.com"
           type="email" value="{{ old('email') }}" class="mt-1 w-full rounded-lg border p-2"
            required>
        </div>

        <div>
          <label class="text-sm font-medium">Password</label>
          <input name="password" placeholder="............"
           type="password" class="mt-1 w-full rounded-lg border p-2" required>
           <span class="text-xs text-gray-500">Must be at least 8 characters.</span>
        </div>

        <div>
          <label class="text-sm font-medium">Confirm Password</label>
          <input name="password_confirmation" placeholder="............" type="password" class="mt-1 w-full rounded-lg border p-2" required>
          <span class="text-xs text-gray-500">Must be at least 8 characters.</span>
        </div>

        <button class="w-full rounded-lg bg-black text-white py-2 font-semibold">
          Register
        </button>
      </form>

      <p class="text-sm text-gray-600 mt-4">
        Already have an account?
        <a class="underline" href="{{ route('elkin.challenges.auth.login') }}">Sign in</a>
      </p>
    </div>
  </div>

</x-layoutDasboard>
