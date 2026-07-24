@extends('layouts.admin')

@section('title', 'Permintaan Unduh')

@section('content')
<section class="section reveal">
    <h1 class="reveal">Permintaan Unduh</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Dokumen</th>
                <th>Email Pengaju</th>
                <th>Pesan</th>
                <th>Status</th>
                <th>Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->document->judul ?? '-' }}</td>
                    <td>{{ $r->requester_email }}</td>
                    <td>{{ Str::limit($r->message, 80) }}</td>
                    <td>{{ strtoupper($r->status) }}</td>
                    <td>{{ $r->created_at->toDateTimeString() }}</td>
                    <td>
                        @if($r->status === 'pending')
                            <form method="POST" action="{{ route('admin.download.requests.approve', $r) }}" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn">Setujui</button>
                            </form>
                        @endif
                        @if($r->requester_phone)
                            @php $phone = preg_replace('/[^0-9]/', '', $r->requester_phone); @endphp
                            @php
                                $waMsg = rawurlencode("Halo, regarding your download request for: " . ($r->document->judul ?? '-') . "\nStatus: " . strtoupper($r->status));
                            @endphp
                            <a href="https://wa.me/{{ $phone }}?text={{ $waMsg }}" target="_blank" class="btn">Kirim WA ke Pengaju</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $requests->links() }}
</section>
@endsection
