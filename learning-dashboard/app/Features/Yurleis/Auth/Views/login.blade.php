<x-layoutDasboard>
  <div class="flex items-center justify-center mt-8">
    <div class="max-w-md bg-white border rounded-2xl p-6 shadow">
      <h1 class="text-2xl font-bold">Sign In</h1>
      <p class="text-sm text-gray-600 mt-1">Access your account.</p>

      @if ($errors->any())
        <div class="mt-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
          <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="mt-6 space-y-4" method="POST" action="{{ route('yurleis.challenges.auth.login.store') }}">
        @csrf

        <div>
          <label class="text-sm font-medium">Email</label>
          <input name="email" placeholder="youremail@example.com"
           type="email" value="{{ old('email') }}" class="mt-1 w-full rounded-lg border p-2" required
            autofocus />
        </div>

        <div>
          <label class="text-sm font-medium">Password</label>
          <input name="password" placeholder="............"
          type="password" class="mt-1 w-full rounded-lg border p-2" required />
        </div>

        <button class="w-full rounded-lg bg-black text-white py-2 font-semibold">
          Sign In
        </button>
      </form>

      <p class="text-sm text-gray-600 mt-4">
        Don't have an account?
        <a class="underline" href="{{ route('yurleis.challenges.auth.register') }}">Sign Up</a>
      </p>
    </div>
  </div>
</x-layoutDasboard>