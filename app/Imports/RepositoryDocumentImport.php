<?php

namespace App\Imports;

use App\Models\ProgramStudi;
use App\Models\RepositoryDocument;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RepositoryDocumentImport
{
    public int $successCount = 0;
    public array $errorRows = [];

    private int $defaultYear;
    private array $dosenCache = [];
    private array $prodiCache = [];

    public function __construct(
        public string $kategori,
        ?string $originalName = null
    ) {
        $this->defaultYear = $this->yearFromText($originalName ?? '') ?? (int) date('Y');
    }

    public function importFromFile(string $filePath, string $extension): void
    {
        $rows = match (strtolower($extension)) {
            'csv' => $this->parseCsv($filePath),
            'xlsx' => $this->parseXlsx($filePath),
            'xls' => throw new \RuntimeException('Format .xls lama belum didukung di PHP 8. Silakan simpan ulang file sebagai .xlsx atau .csv.'),
            default => throw new \RuntimeException("Format file .$extension tidak didukung."),
        };

        $rows = $this->dropEmptyRows($rows);

        if ($rows === []) {
            throw new \RuntimeException('File tidak mengandung data.');
        }

        [$fieldMap, $dataRows, $startRow] = $this->prepareRows($rows);

        foreach ($dataRows as $index => $row) {
            if ($this->isEmptyRow($row)) {
                continue;
            }

            $this->processRow($this->mapRowToData($row, $fieldMap), $startRow + $index);
        }
    }

    private function prepareRows(array $rows): array
    {
        $headerIndex = $this->findHeaderRow($rows);

        if ($headerIndex !== null) {
            return [
                $this->buildFieldMap($rows[$headerIndex]),
                array_slice($rows, $headerIndex + 1),
                $headerIndex + 2,
            ];
        }

        return [
            $this->defaultFieldMapForHeaderlessRows($rows[0]),
            $rows,
            1,
        ];
    }

    private function findHeaderRow(array $rows): ?int
    {
        foreach (array_slice($rows, 0, 10, true) as $index => $row) {
            $fields = array_filter(array_map(fn ($cell) => $this->normalizeHeader((string) $cell), $row));
            $hasName = in_array('nama', $fields, true) || in_array('dosen_name', $fields, true);
            $hasTitle = in_array('judul', $fields, true);
            $hasYear = in_array('tahun', $fields, true);
            $hasDosenType = in_array('pkm_flag', $fields, true) || in_array('penelitian_flag', $fields, true);

            if (($hasName && $hasTitle) || ($hasDosenType && $hasTitle && $hasYear)) {
                return $index;
            }
        }

        return null;
    }

    private function buildFieldMap(array $headerRow): array
    {
        $map = [];

        foreach ($headerRow as $colIdx => $rawHeader) {
            $field = $this->normalizeHeader((string) $rawHeader);

            if ($field !== '_skip') {
                $map[$colIdx] = $field;
            }
        }

        return $map;
    }

    private function defaultFieldMapForHeaderlessRows(array $firstRow): array
    {
        if ($this->kategori === 'magang' && count($firstRow) >= 9) {
            return [
                1 => 'nim',
                2 => 'nama',
                3 => 'dosen',
                5 => 'tempat_magang',
                6 => 'alamat',
                7 => 'telepon',
                8 => 'judul',
            ];
        }

        if (in_array($this->kategori, ['pkm', 'penelitian'], true) && count($firstRow) >= 6) {
            return [
                1 => 'nama',
                2 => 'pkm_flag',
                3 => 'penelitian_flag',
                4 => 'judul',
                5 => 'tahun',
            ];
        }

        return match ($this->kategori) {
            'skripsi' => [0 => 'nim', 1 => 'nama', 2 => 'judul', 3 => 'tahun', 4 => 'program_studi', 5 => 'dosen'],
            'magang' => [0 => 'nim', 1 => 'nama', 2 => 'judul', 3 => 'tahun', 4 => 'program_studi', 5 => 'tempat_magang', 6 => 'dosen'],
            default => [0 => 'nidn', 1 => 'nama', 2 => 'judul', 3 => 'tahun', 4 => 'program_studi', 5 => 'detail'],
        };
    }

    public ?string $zipExtractPath = null;
    private array $pdfFileIndex = [];

    public function setZipExtractPath(?string $path): self
    {
        $this->zipExtractPath = $path;
        $this->indexPdfFilesFromZip();
        return $this;
    }

    private function indexPdfFilesFromZip(): void
    {
        if (!$this->zipExtractPath || !is_dir($this->zipExtractPath)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->zipExtractPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $filename = $file->getFilename();
            $pathname = $file->getPathname();

            if (str_starts_with($filename, '._') || str_contains($pathname, '__MACOSX')) {
                continue;
            }

            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $lowerName = strtolower($filename);
                $this->pdfFileIndex[$lowerName] = $file->getRealPath();
            }
        }
    }

    private function findPdfForData(array $data): ?string
    {
        if (empty($this->pdfFileIndex)) {
            return null;
        }

        // 1. Match explicit 'file_dokumen' column value
        $explicitName = strtolower(trim($data['file_dokumen'] ?? ''));
        if ($explicitName !== '') {
            if (!str_ends_with($explicitName, '.pdf')) {
                $explicitName .= '.pdf';
            }
            if (isset($this->pdfFileIndex[$explicitName])) {
                return $this->pdfFileIndex[$explicitName];
            }
        }

        // 2. Match by NIM or NIDN
        $identity = strtolower(trim($data['nim'] ?? $data['nidn'] ?? ''));
        if ($identity !== '') {
            foreach ($this->pdfFileIndex as $filename => $fullPath) {
                if (str_contains($filename, $identity)) {
                    return $fullPath;
                }
            }
        }

        // 3. Match by Name
        $nama = strtolower(trim($data['nama'] ?? ''));
        if ($nama !== '') {
            $slugNama = str_replace(' ', '', $nama);
            foreach ($this->pdfFileIndex as $filename => $fullPath) {
                $cleanFilename = str_replace(['_', '-'], '', pathinfo($filename, PATHINFO_FILENAME));
                if (str_contains($cleanFilename, $slugNama) || str_contains($slugNama, $cleanFilename)) {
                    return $fullPath;
                }
            }
        }

        return null;
    }

    private function storePdfFromZip(string $pdfPath): ?array
    {
        try {
            if (!file_exists($pdfPath)) {
                return null;
            }

            $originalName = basename($pdfPath);
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $pdfPath,
                $originalName,
                'application/pdf',
                null,
                true
            );

            // Store to storage disk
            $storedPath = null;
            foreach (['local', 'public'] as $disk) {
                try {
                    $path = $uploadedFile->store('repository-documents', $disk);
                    if ($path) {
                        $storedPath = $path;
                        break;
                    }
                } catch (\Throwable $e) {
                    // try next disk
                }
            }

            if (!$storedPath) {
                return null;
            }

            // Count PDF pages
            $pageCount = null;
            try {
                $handle = @fopen($pdfPath, 'rb');
                if ($handle) {
                    $chunk = fread($handle, 204800);
                    fclose($handle);
                    if ($chunk !== false) {
                        preg_match_all('/\/Type\s*\/Page[^s]/', $chunk, $matches);
                        $pageCount = count($matches[0]) ?: null;
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }

            return [
                'file_dokumen' => $storedPath,
                'pdf_page_count' => $pageCount,
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeHeader(string $raw): string
    {
        $h = strtolower(trim(preg_replace('/\s+/', ' ', $raw)));
        $h = trim($h, ".:; \t\n\r\0\x0B");

        $rules = [
            '_skip' => ['no', 'nomor', 'no urut', 'urut'],
            'nim' => ['nim', 'no bp', 'no.bp', 'nobp', 'nrp', 'no mahasiswa', 'nomor mahasiswa', 'id mahasiswa'],
            'nidn' => ['nidn', 'nip', 'nidn dosen', 'nip dosen'],
            'dosen_nidn' => ['nidn pembimbing', 'nip pembimbing'],
            'nama' => ['nama', 'nama mahasiswa', 'nama lengkap', 'nama penulis', 'nama peneliti', 'penulis'],
            'dosen_name' => ['nama dosen', 'dosen peneliti'],
            'email' => ['email', 'email mahasiswa', 'email dosen', 'e-mail', 'e mail'],
            'judul' => ['judul', 'judul laporan', 'judul skripsi', 'judul penelitian', 'judul pkm', 'judul magang', 'judul tugas akhir', 'judul ta', 'title', 'topik'],
            'tahun' => ['tahun', 'tahun lulus', 'tahun terbit', 'tahun akademik', 'year'],
            'abstrak' => ['abstrak', 'abstract', 'ringkasan', 'sinopsis', 'deskripsi'],
            'program_studi' => ['program studi', 'program_studi', 'prodi', 'jurusan', 'departemen', 'fakultas', 'bidang studi'],
            'tempat_magang' => ['tempat magang', 'tempat_magang', 'nama perusahaan', 'perusahaan', 'instansi', 'lembaga', 'organisasi', 'nama instansi', 'tempat praktik', 'tempat pkl', 'tempat kerja'],
            'dosen' => ['dosen pembimbing', 'dosen_pembimbing', 'pembimbing', 'pembimbing i', 'pembimbing 1', 'pembimbing ii', 'pembimbing 2', 'supervisor', 'nama pembimbing'],
            'file_dokumen' => ['file dokumen', 'file_dokumen', 'file pdf', 'pdf', 'nama file', 'berkas', 'file', 'file_pdf', 'dokumen', 'file_name'],
            'alamat' => ['alamat perusahaan', 'alamat instansi', 'alamat'],
            'telepon' => ['no telp', 'no telepon', 'no hp', 'telepon', 'telpon', 'telephone', 'phone', 'handphone', 'hp'],
            'detail' => ['detail', 'keterangan', 'catatan', 'note', 'notes'],
            'pkm_flag' => ['pkm dosen', 'pkm'],
            'penelitian_flag' => ['penelitian dosen', 'penelitian'],
        ];

        foreach ($rules as $field => $keywords) {
            if (in_array($h, $keywords, true)) {
                return $field;
            }
        }

        return str_replace(' ', '_', $h);
    }

    private function mapRowToData(array $row, array $fieldMap): array
    {
        $data = [];

        foreach ($fieldMap as $colIdx => $field) {
            $value = $this->cleanCell($row[$colIdx] ?? '');

            if ($value === '') {
                continue;
            }

            if ($field === 'dosen_name') {
                $field = 'nama';
            }

            if (in_array($field, ['alamat', 'telepon'], true)) {
                $field = 'detail';
            }

            $data[$field] = isset($data[$field]) && $data[$field] !== ''
                ? $data[$field].' | '.$value
                : $value;
        }

        return $data;
    }

    private function processRow(array $data, int $rowNum): void
    {
        $nama = $data['nama'] ?? '';
        $judul = $data['judul'] ?? '';
        $errors = [];

        if ($this->shouldSkipMappedData($data)) {
            return;
        }

        if ($nama === '') {
            $errors[] = 'Kolom Nama kosong';
        }

        if ($judul === '') {
            $errors[] = 'Kolom Judul kosong';
        }

        if ($errors !== []) {
            $this->addError($rowNum, $nama, $judul, $errors);
            return;
        }

        $kategori = $this->resolveKategori($data);
        $tahun = $this->resolveYear($data['tahun'] ?? null);

        // Process PDF attachment from ZIP if available
        $pdfData = [];
        if ($this->zipExtractPath) {
            $matchedPdf = $this->findPdfForData($data);
            if ($matchedPdf) {
                $storedPdfInfo = $this->storePdfFromZip($matchedPdf);
                if ($storedPdfInfo) {
                    $pdfData = $storedPdfInfo;
                }
            }
        }

        try {
            $recordData = array_merge([
                'kategori' => $kategori,
                'jenis_input' => 'admin_import',
                'input_by' => Auth::id(),
                'program_studi_id' => $this->resolveProgramStudiId($data['program_studi'] ?? ''),
                'dosen_pembimbing_id' => $this->resolveDosenId($data['dosen'] ?? ''),
                'nama' => $nama,
                'nim' => ($data['nim'] ?? '') ?: null,
                'nidn' => ($data['nidn'] ?? '') ?: null,
                'email' => ($data['email'] ?? '') ?: null,
                'judul' => $judul,
                'tahun' => $tahun,
                'abstrak' => ($data['abstrak'] ?? '') ?: null,
                'detail' => ($data['detail'] ?? '') ?: null,
                'tempat_magang' => ($data['tempat_magang'] ?? '') ?: null,
                'status' => 'terverifikasi',
                'tanggal_upload' => now(),
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ], $pdfData);

            RepositoryDocument::updateOrCreate(
                $this->uniqueKey($kategori, $data, $judul),
                $recordData
            );

            $this->successCount++;
        } catch (\Throwable $e) {
            $this->addError($rowNum, $nama, $judul, ['Gagal disimpan: '.$e->getMessage()]);
        }
    }

    private function uniqueKey(string $kategori, array $data, string $judul): array
    {
        $identity = $data['nim'] ?? $data['nidn'] ?? null;

        if ($identity) {
            return [
                'kategori' => $kategori,
                $this->isStudentCategory($kategori) ? 'nim' : 'nidn' => $identity,
                'judul' => $judul,
            ];
        }

        return [
            'kategori' => $kategori,
            'nama' => $data['nama'],
            'judul' => $judul,
            'tahun' => $this->resolveYear($data['tahun'] ?? null),
        ];
    }

    private function resolveKategori(array $data): string
    {
        if ($this->hasTruthyMarker($data['pkm_flag'] ?? '')) {
            return 'pkm';
        }

        if ($this->hasTruthyMarker($data['penelitian_flag'] ?? '')) {
            return 'penelitian';
        }

        $kategori = strtolower($data['kategori'] ?? $this->kategori);

        return in_array($kategori, ['skripsi', 'magang', 'pkm', 'penelitian'], true)
            ? $kategori
            : $this->kategori;
    }

    private function hasTruthyMarker(string $value): bool
    {
        $value = strtolower(trim($value));

        return $value !== '' && ! in_array($value, ['0', '-', 'tidak', 'no', 'false'], true);
    }

    private function resolveYear(?string $value): int
    {
        $year = $this->yearFromText((string) $value);

        return $year ?? $this->defaultYear;
    }

    private function yearFromText(string $text): ?int
    {
        if (preg_match('/\b(19|20)\d{2}\b/', $text, $matches)) {
            $year = (int) $matches[0];

            if ($year >= 1990 && $year <= ((int) date('Y') + 1)) {
                return $year;
            }
        }

        return null;
    }

    private function resolveProgramStudiId(string $name): ?int
    {
        $name = trim($name);

        if ($name === '') {
            return null;
        }

        if (! array_key_exists($name, $this->prodiCache)) {
            $this->prodiCache[$name] = ProgramStudi::where('nama', 'like', '%'.$name.'%')->first()?->id;
        }

        return $this->prodiCache[$name];
    }

    private function resolveDosenId(string $name): ?int
    {
        $name = trim($name);

        if ($name === '') {
            return null;
        }

        if (! array_key_exists($name, $this->dosenCache)) {
            $this->dosenCache[$name] = User::where('role', 'dosen')
                ->where('name', 'like', '%'.$name.'%')
                ->first()?->id;
        }

        return $this->dosenCache[$name];
    }

    private function parseXlsx(string $filePath): array
    {
        if (! class_exists('ZipArchive')) {
            throw new \RuntimeException('Ekstensi ZipArchive diperlukan untuk membaca file .xlsx.');
        }

        $zip = new \ZipArchive();

        if ($zip->open($filePath) !== true) {
            throw new \RuntimeException('Gagal membuka file .xlsx. Pastikan file tidak rusak.');
        }

        $sharedStrings = $this->readSharedStrings($zip);
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            throw new \RuntimeException('Tidak ditemukan worksheet pertama di dalam file .xlsx.');
        }

        $xml = @simplexml_load_string($sheetXml);

        if (! $xml) {
            throw new \RuntimeException('Gagal membaca worksheet Excel.');
        }

        $rows = [];

        foreach ($xml->sheetData->row ?? [] as $row) {
            $rowData = [];

            foreach ($row->c as $cell) {
                $colIndex = $this->colLetterToIndex(preg_replace('/[0-9]/', '', (string) ($cell['r'] ?? '')));
                $type = (string) ($cell['t'] ?? '');
                $value = (string) ($cell->v ?? '');

                if ($type === 's') {
                    $value = $sharedStrings[(int) $value] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = (string) ($cell->is->t ?? '');
                }

                $rowData[$colIndex] = $this->cleanCell($value);
            }

            if ($rowData !== []) {
                $complete = [];
                for ($i = 0; $i <= max(array_keys($rowData)); $i++) {
                    $complete[] = $rowData[$i] ?? '';
                }
                $rows[] = $complete;
            }
        }

        return $rows;
    }

    private function readSharedStrings(\ZipArchive $zip): array
    {
        $content = $zip->getFromName('xl/sharedStrings.xml');

        if ($content === false) {
            return [];
        }

        $xml = @simplexml_load_string($content);

        if (! $xml) {
            return [];
        }

        $strings = [];

        foreach ($xml->si as $si) {
            if (isset($si->t)) {
                $strings[] = (string) $si->t;
                continue;
            }

            $text = '';
            foreach ($si->r as $run) {
                $text .= (string) ($run->t ?? '');
            }
            $strings[] = $text;
        }

        return $strings;
    }

    private function parseCsv(string $filePath): array
    {
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            throw new \RuntimeException('Gagal membuka file CSV.');
        }

        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $sample = fgets($handle) ?: '';
        rewind($handle);
        if ($bom === "\xEF\xBB\xBF") {
            fread($handle, 3);
        }

        $delimiter = substr_count($sample, ';') > substr_count($sample, ',') ? ';' : ',';
        $rows = [];

        while (($line = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rows[] = array_map(fn ($value) => $this->cleanCell($value), $line);
        }

        fclose($handle);

        return $rows;
    }

    private function dropEmptyRows(array $rows): array
    {
        return array_values(array_filter($rows, fn ($row) => ! $this->isEmptyRow($row)));
    }

    private function isEmptyRow(array $row): bool
    {
        return empty(array_filter($row, fn ($value) => $this->cleanCell($value) !== ''));
    }

    private function cleanCell(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return trim(preg_replace('/\s+/', ' ', (string) $value));
    }

    private function colLetterToIndex(string $col): int
    {
        $result = 0;

        foreach (str_split(strtoupper($col)) as $char) {
            $result = ($result * 26) + (ord($char) - 64);
        }

        return max(0, $result - 1);
    }

    private function addError(int $row, string $nama, string $judul, array $errors): void
    {
        $this->errorRows[] = [
            'row' => $row,
            'nama' => $nama ?: '-',
            'judul' => $judul ?: '-',
            'errors' => $errors,
        ];
    }

    private function shouldSkipMappedData(array $data): bool
    {
        $nama = strtolower($data['nama'] ?? '');
        $judul = strtolower($data['judul'] ?? '');
        $combined = trim($nama.' '.$judul.' '.strtolower($data['detail'] ?? ''));

        if ($combined === '') {
            return true;
        }

        foreach (['rekap', 'jumlah', 'total', 'final pkl', 'mahasiswa pkl'] as $keyword) {
            if (str_contains($combined, $keyword) && (($data['nim'] ?? '') === '')) {
                return true;
            }
        }

        return false;
    }

    private function isStudentCategory(string $kategori): bool
    {
        return in_array($kategori, ['skripsi', 'magang'], true);
    }
}
