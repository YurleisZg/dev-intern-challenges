<x-layoutDasboard>
<div class="p-4">
    <div class="max-w-4xl mx-auto">

        <div class="flex justify-end mb-3">
            <button type="button" onclick="exitGame()"
                class="text-black border border-slate-600 px-5 py-2 rounded-md">
                ⬅ Salir
            </button>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-4">
            <div class="p-6 text-center">
                <div class="text-slate-800 text-sm mb-2">Level</div>
                <div class="text-black text-xl font-bold">{{ $currentLevel }} / 5</div>
            </div>
            <div class="p-6 text-center">
                <div class="text-slate-800 text-sm mb-2">Time Left</div>
                <div id="timer" class="text-yellow-600 text-xl font-bold">{{ $timeLeft }}s</div>
            </div>
            <div class="p-6 text-center">
                <div class="text-slate-800 text-sm mb-2">Strikes</div>
                <div class="text-red-600 text-xl font-bold">{{ $strikes }} / 3</div>
            </div>
        </div>

        <div class="p-4 mb-2">
            <div class="text-center text-2xl font-bold text-black mb-6">Your Input</div>
            <div class="flex justify-center gap-4">
                @foreach($inputState as $index => $isOn)
                <div class="w-16 h-16 rounded-ms border-4 {{ $isOn ? ' border-slate-800 ' : 'bg-white/10 border-slate-600' }} flex items-center justify-center"
                     data-col="{{ $index }}"
                     onclick="toggleInput({{ $index }})">
                    @if($isOn)
                        <span class="text-slate-800 text-xl font-bold">✓</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <button onclick="submitPattern()" class="bg-slate-600 text-white font-bold py-2 px-4 rounded-md">
            ✓ Submit Pattern
            </button>
        </div>

        <div id="message" class="hidden mt-6 p-4 rounded-2xl text-center text-white font-semibold text-lg"></div>
    </div>
</div>

<script>
    let timeLeft = {{ $timeLeft }};
    const timerEl = document.getElementById('timer');
    const messageEl = document.getElementById('message');

    let hasExited = false;
    let isSubmitting = false;

    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '{{ csrf_token() }}';
    }

    const countdown = setInterval(() => {
        if (hasExited || isSubmitting) return;

        timeLeft--;
        timerEl.textContent = timeLeft + 's';

        if (timeLeft <= 5) {
            timerEl.classList.add('text-red-500', 'pulse-animation');
            timerEl.classList.remove('text-yellow-600');
        }

        if (timeLeft <= 0) {
            clearInterval(countdown);
            submitPattern();
        }
    }, 1000);

    function toggleInput(col) {
        if (hasExited || isSubmitting) return;

        const cell = document.querySelector(`[data-col="${col}"]`);
        if (!cell) return;

        const isOn = cell.classList.contains('from-pink-500');

        fetch('{{ route("yurleis.challenges.toggle-time-attack.update-stage2") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify({ col: col, value: !isOn })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;

            if (isOn) {
                cell.classList.remove('bg-gradient-to-br', 'from-pink-500', 'to-rose-500', 'border-white', 'shadow-lg', 'shadow-slate-500/50');
                cell.classList.add('bg-white/10', 'border-white/50');
                cell.innerHTML = '';
            } else {
                cell.classList.add('border-slate-800', 'shadow-lg', 'shadow-slate-800');
                cell.classList.remove('bg-white/10', 'border-white/50');
                cell.innerHTML = '<span class="text-slate-800 text-xl font-bold">✓</span>';
            }
        })
        .catch(() => {});
    }

    function submitPattern() {
        if (hasExited || isSubmitting) return;

        isSubmitting = true;
        clearInterval(countdown);

        fetch('{{ route("yurleis.challenges.toggle-time-attack.submit-stage2") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.victory) {
                showMessage(data.message, 'success');

    
                abandonGame(() => window.location.href = '{{ route("yurleis.challenges.toggle-time-attack.index") }}', 2500);

            } else if (data.game_over) {
                showMessage(data.message, 'error');

                abandonGame(() => window.location.href = '{{ route("yurleis.challenges.toggle-time-attack.index") }}', 2500);

            } else if (data.success) {
                showMessage(data.message, 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showMessage(data.message, 'warning');
                setTimeout(() => window.location.reload(), 1800);
            }
        })
        .catch(() => {
            showMessage('Error de red.', 'error');
            setTimeout(() => window.location.reload(), 1500);
        })
        .finally(() => {
            isSubmitting = false;
        });
    }
    function exitGame() {
        if (hasExited) return;

        hasExited = true;
        clearInterval(countdown);

        abandonGame(() => {
            window.location.href = '{{ route("yurleis.challenges.toggle-time-attack.index") }}';
        });
    }

    function abandonGame(onDone, delay = 0) {
        fetch('{{ route("yurleis.challenges.toggle-time-attack.abandon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            }
        })
        .finally(() => {
            if (typeof onDone === 'function') {
                if (delay > 0) setTimeout(onDone, delay);
                else onDone();
            }
        });
    }

    function showMessage(text, type) {
        messageEl.textContent = text;

        if (type === 'success') {
            messageEl.className = 'mt-6 p-4 text-center text-green-500 font-semibold text-lg';
        } else if (type === 'warning') {
            messageEl.className = 'mt-6 p-4 text-center text-orange-500 font-semibold text-lg';
        } else {
            messageEl.className = 'mt-6 p-4 text-center text-red-500 font-semibold text-lg';
        }

        messageEl.classList.remove('hidden');
    }
</script>
</x-layoutDasboard>
