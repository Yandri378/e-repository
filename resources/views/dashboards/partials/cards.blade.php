<section class="section grid stats-grid">
    @foreach (($cardKeys ?? ['skripsi' => 'Skripsi/TA', 'magang' => 'Magang', 'pkm' => 'PKM', 'penelitian' => 'Penelitian']) as $key => $label)
        <a class="stat-card tilt-card" href="{{ route('repository.index', $key) }}">
            <span>{{ $label }}</span>
            <strong>{{ $cards[$key]['bulan_ini'] ?? 0 }}</strong>
            <small>Bulan ini | Total {{ $cards[$key]['total'] ?? 0 }}</small>
        </a>
    @endforeach
</section>
