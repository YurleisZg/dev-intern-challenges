<x-layoutDasboard>
  <div class="w-full max-w-md bg-white border rounded-2xl p-6 shadow">
    <h1 class="text-2xl font-bold">Iniciar sesión</h1>
    <p class="text-sm text-gray-600 mt-1">Accede a tu cuenta.</p>

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
        <input
          name="email"
          type="email"
          value="{{ old('email') }}"
          class="mt-1 w-full rounded-lg border p-2"
          required
          autofocus
        />
      </div>

      <div>
        <label class="text-sm font-medium">Contraseña</label>
        <input
          name="password"
          type="password"
          class="mt-1 w-full rounded-lg border p-2"
          required
        />
      </div>

      <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="remember" class="rounded border">
        Recordarme
      </label>

      <button class="w-full rounded-lg bg-black text-white py-2 font-semibold">
        Entrar
      </button>
    </form>

    <p class="text-sm text-gray-600 mt-4">
      ¿No tienes cuenta?
      <a class="underline" href="{{ route('yurleis.challenges.auth.register') }}">Regístrate</a>
    </p>
  </div>
</x-layoutDasboard>