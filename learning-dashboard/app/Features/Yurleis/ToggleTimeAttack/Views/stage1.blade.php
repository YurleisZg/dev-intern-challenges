<x-layoutDasboard>
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold text-black mb-2">Stage 1: The Setup</h1>
            <div id="timer" class="text-3xl font-bold text-yellow-300">{{ $timeLeft }}s</div>
        </div>

        <div class="p-2 mb-2 text-center">
            <p class="text-lg text-black"><strong>Mission:</strong> Turn ON at least 1 toggle in EACH of the 5 rows before time runs out!</p>
        </div>

        <div class="p-4 mb-2">
            @foreach($rows as $rowIndex => $row)
            <div class="flex items-center justify-center gap-4 mb-2">
                <div class="text-slate-900 font-bold text-xl w-18 text-right">Row {{ $rowIndex + 1 }}:</div>
                <div class="flex gap-4">
                    @foreach($row as $colIndex => $isOn)
                    <div class="w-16 h-16 rounded-xl border-4 bg-slate-900 {{ $isOn ? ' bg-slate-400 border-slate-500 shadow-lg shadow-slate-500/50' : 'bg-slate-900/10 border-slate-500/50' }} cursor-pointer transition-all transform hover:scale-110 flex items-center justify-center"
                         data-row="{{ $rowIndex }}"
                         data-col="{{ $colIndex }}"
                         onclick="toggleCell({{ $rowIndex }}, {{ $colIndex }})">
                        @if($isOn)
                        <span class="text-slate-900 text-xl font-bold">✓</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-end mb-2 gap-2">
            <button type="button" onclick="exitGame()"
                class=" text-black border border-slate-600 px-5 rounded-md ">
                ⬅ Salir
            </button>

            <button onclick="submitStage1()"
                class="bg-slate-900 text-white text-lg py-3 px-8 rounded-md shadow-lg transform transition hover:scale-105">
                ✓ Complete Stage 1
            </button>
        </div>

        <div id="message" class="text-center text-slate-900 font-semibold text-lg"></div>
    </div>
</div>

<script>
    let timeLeft = {{ $timeLeft }};
    const timerEl = document.getElementById('timer');
    const messageEl = document.getElementById('message');
    let hasExited = false;

    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '{{ csrf_token() }}';
    }

    const countdown = setInterval(() => {
        if (hasExited) return;

        timeLeft--;
        timerEl.textContent = timeLeft + 's';

        if (timeLeft <= 5) {
            timerEl.classList.add('text-red-500', 'pulse-animation');
            timerEl.classList.remove('text-yellow-300');
        }

        if (timeLeft <= 0) {
            clearInterval(countdown);
            showMessage('Time expired! Game Over.', 'error');

            abandonGame(() => {
                window.location.href = '/Yurleis';
            });
        }
    }, 1000);

    function toggleCell(row, col) {
        if (hasExited) return;

        const cell = document.querySelector(`[data-row="${row}"][data-col="${col}"]`);
        if (!cell) return;

        const isOn = cell.classList.contains('bg-slate-400');

        fetch('{{ route("yurleis.challenges.toggle-time-attack.update-stage1") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify({ row: row, col: col, value: !isOn })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;

            if (isOn) {
                cell.classList.remove('bg-slate-400', 'border-slate-500', 'shadow-lg', 'shadow-slate-500/50');
                cell.classList.add('bg-slate-900/10', 'border-slate-500/50');
                cell.innerHTML = '';
            } else {
                cell.classList.add('bg-slate-400', 'border-slate-500', 'shadow-lg', 'shadow-slate-500/50');
                cell.classList.remove('bg-slate-900/10', 'border-slate-500/50');
                cell.innerHTML = '<span class="text-slate-900 text-xl font-bold">✓</span>';
            }
        })
        .catch(() => {});
    }

    function submitStage1() {
        if (hasExited) return;

        fetch('{{ route("yurleis.challenges.toggle-time-attack.submit-stage1") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showMessage('✓ Stage 1 Complete! Moving to Stage 2...', 'success');
                setTimeout(() => window.location.href = data.redirect, 1500);
            } else {
                showMessage('✗ ' + (data.message || 'Error'), 'error');

                abandonGame(() => {
                    window.location.href = '{{ route("yurleis.challenges.toggle-time-attack.index") }}';
                });
            }
        })
        .catch(() => {
            showMessage('✗ Error de red.', 'error');
        });
    }

    function exitGame() {
        hasExited = true;
        clearInterval(countdown);

        abandonGame(() => {
            window.location.href = '/Yurleis';
        });
    }

    function abandonGame(onDone) {
        fetch('{{ route("yurleis.challenges.toggle-time-attack.abandon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            }
        })
        .finally(() => {
            if (typeof onDone === 'function') onDone();
        });
    }

    function showMessage(text, type) {
        messageEl.textContent = text;
        messageEl.className = type === 'error'
            ? 'text-center text-red-500 font-semibold text-lg'
            : 'text-center text-green-500 font-semibold text-lg';
    }
</script>
</x-layoutDasboard>
