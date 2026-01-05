<x-layoutDasboard>
 <div class="w-full max-w-md bg-white border rounded-2xl p-6 shadow">
    <h1 class="text-2xl font-bold">Crear cuenta</h1>
    <p class="text-sm text-gray-600 mt-1">Regístrate para continuar.</p>

    @if ($errors->any())
      <div class="mt-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
        <ul class="list-disc ml-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form class="mt-6 space-y-4" method="POST" action="{{ route('yurleis.challenges.auth.register.store') }}">
      @csrf

      <div>
        <label class="text-sm font-medium">Nombre</label>
        <input name="name" type="text" value="{{ old('name') }}" class="mt-1 w-full rounded-lg border p-2" required>
      </div>

      <div>
        <label class="text-sm font-medium">Email</label>
        <input name="email" type="email" value="{{ old('email') }}" class="mt-1 w-full rounded-lg border p-2" required>
      </div>

      <div>
        <label class="text-sm font-medium">Contraseña</label>
        <input name="password" type="password" class="mt-1 w-full rounded-lg border p-2" required>
      </div>

      <div>
        <label class="text-sm font-medium">Confirmar contraseña</label>
        <input name="password_confirmation" type="password" class="mt-1 w-full rounded-lg border p-2" required>
      </div>

      <button class="w-full rounded-lg bg-black text-white py-2 font-semibold">
        Registrarme
      </button>
    </form>

    <p class="text-sm text-gray-600 mt-4">
      ¿Ya tienes cuenta?
      <a class="underline" href="{{ route('yurleis.challenges.auth.login') }}">Inicia sesión</a>
    </p>
  </div>
</x-layoutDasboard>