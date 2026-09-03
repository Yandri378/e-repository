<?php

return [
    'accepted' => ':attribute harus diterima.',
    'active_url' => ':attribute bukan URL yang valid.',
    'after' => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'array' => ':attribute harus berupa sebuah array.',
    'before' => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'file' => ':attribute harus berukuran antara :min sampai :max kilobita.',
        'string' => ':attribute harus berisi antara :min sampai :max karakter.',
        'array' => ':attribute harus memiliki :min sampai :max anggota.',
    ],
    'boolean' => ':attribute harus bernilai true atau false',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'date' => ':attribute bukan tanggal yang valid.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'max' => [
        'numeric' => ':attribute maskimal bernilai :max.',
        'file' => ':attribute maksimal berukuran :max kilobita.',
        'string' => ':attribute maksimal berisi :max karakter.',
        'array' => ':attribute maksimal memiliki :max anggota.',
    ],
    'min' => [
        'numeric' => ':attribute minimal bernilai :min.',
        'file' => ':attribute minimal berukuran :min kilobita.',
        'string' => ':attribute minimal berisi :max karakter.',
        'array' => ':attribute minimal memiliki :max anggota.',
    ],
    'required' => ':attribute wajib diisi.',
    'unique' => ':attribute sudah terdaftar / sudah digunakan.',
];
