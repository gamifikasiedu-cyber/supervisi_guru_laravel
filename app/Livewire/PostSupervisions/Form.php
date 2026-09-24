<?php

namespace App\Livewire\PostSupervisions;

use App\Exports\PostSupervisionFilledExport;
use App\Exports\PostSupervisionTemplateExport;
use App\Http\Controllers\PostSupervisionController;
use App\Models\Period;
use App\Models\PostSupervision;
use App\Models\Setting;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Form extends Component
{
    public ?PostSupervision $postSupervision = null;

    public string $bagian = 'asesmen-formatif';

    public string $teacher_id = '';

    public string $subject_id = '';

    public string $class_name = '';

    public string $observation_date = '';

    public array $items = [];

    public string $rekapTeacher = '';

    public function mount(?PostSupervision $postSupervision = null): void
    {
        $this->observation_date = now()->format('Y-m-d');
        $this->rekapTeacher = (string) request('guru', '');

        if ($postSupervision?->exists) {
            $this->postSupervision = $postSupervision;
            $this->bagian = $postSupervision->bagian;
            $this->teacher_id = (string) ($postSupervision->teacher_id ?? '');
            $this->subject_id = (string) ($postSupervision->subject_id ?? '');
            $this->class_name = $postSupervision->class_name ?? '';
            $this->observation_date = $postSupervision->observation_date?->format('Y-m-d') ?? now()->format('Y-m-d');
            $this->items = array_map(fn ($it) => $it, $postSupervision->items ?? []);
        } else {
            $reqBagian = (string) request('bagian', '');
            if ($reqBagian && PostSupervisionController::config($reqBagian)) {
                $this->bagian = $reqBagian;
            }
            $stashed = session()->pull('pasca_import_'.$this->bagian);
            $this->items = is_array($stashed) && $stashed !== []
                ? $stashed
                : $this->defaultsFor($this->bagian);
        }
    }

    protected function defaultsFor(?string $bagian): array
    {
        $cfg = $bagian ? PostSupervisionController::config($bagian) : null;
        if (! $cfg) {
            return [];
        }

        if (($custom = $this->customRowsFor($bagian)) !== null) {
            return $custom;
        }

        return array_map(function (array $std) use ($cfg) {
            $row = [];
            foreach ($cfg['fields'] as $f) {
                $row[$f['key']] = $std[$f['key']] ?? '';
            }

            return $row;
        }, $cfg['standard']);
    }

    protected function customKey(string $bagian): string
    {
        return 'pasca_supervisi_default_'.$bagian;
    }

    /**
     * Struktur bawaan kustom dari hasil impor (null jika belum ada).
     */
    public function customRowsFor(?string $bagian): ?array
    {
        if (! $bagian) {
            return null;
        }

        $cfg = PostSupervisionController::config($bagian);
        if (! $cfg) {
            return null;
        }

        try {
            $raw = Setting::value($this->customKey($bagian));
        } catch (\Throwable) {
            return null;
        }

        if (! $raw) {
            return null;
        }

        $decoded = json_decode($raw, true);
        if (! is_array($decoded) || $decoded === []) {
            return null;
        }

        $rows = [];
        foreach ($decoded as $row) {
            if (! is_array($row)) {
                continue;
            }
            $filled = [];
            foreach ($cfg['fields'] as $f) {
                $filled[$f['key']] = $row[$f['key']] ?? '';
            }
            if (count(array_filter($filled, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }
            $rows[] = $filled;
        }

        return $rows === [] ? null : $rows;
    }

    public function resetDefault(): void
    {
        Setting::where('key', $this->customKey($this->bagian))->delete();
        $this->items = $this->defaultsFor($this->bagian);
        session()->flash('success', 'Struktur bawaan dikembalikan ke standar.');
    }

    public function addRow(): void
    {
        $cfg = PostSupervisionController::config($this->bagian);
        $blank = [];
        foreach ($cfg['fields'] ?? [] as $f) {
            $blank[$f['key']] = '';
        }
        $this->items[] = $blank;
    }

    public function removeRow(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $cfg = PostSupervisionController::config($this->bagian);
        abort_if(! $cfg, 404);

        $rules = [
            'teacher_id' => 'nullable|exists:users,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'class_name' => 'nullable|string|max:255',
            'observation_date' => 'required|date',
            'items' => 'required|array|min:1',
        ];

        $messages = [
            'observation_date.required' => 'Tanggal wajib diisi.',
            'items.required' => 'Isian tabel wajib ada minimal 1 baris.',
            'items.min' => 'Isian tabel wajib ada minimal 1 baris.',
        ];

        foreach ($cfg['fields'] as $f) {
            $key = 'items.*.'.$f['key'];
            $label = $f['label'] ?? $f['key'];
            if ($f['type'] === 'skor') {
                $rules[$key] = $f['required'] ? 'required|integer|min:1|max:4' : 'nullable|integer|min:1|max:4';
                $messages[$key.'.required'] = "Kolom \"{$label}\" wajib dipilih (1-4) di setiap baris.";
                $messages[$key.'.integer'] = "Kolom \"{$label}\" harus angka 1-4.";
                $messages[$key.'.min'] = "Kolom \"{$label}\" minimal 1.";
                $messages[$key.'.max'] = "Kolom \"{$label}\" maksimal 4.";
            } elseif ($f['type'] === 'select') {
                $in = implode(',', $f['options'] ?? []);
                $rules[$key] = $f['required'] ? "required|string|in:{$in}" : "nullable|string|in:{$in}";
                $messages[$key.'.required'] = "Kolom \"{$label}\" wajib dipilih.";
                $messages[$key.'.in'] = "Kolom \"{$label}\" tidak valid.";
            } else {
                $max = $f['key'] === 'tahap' ? 255 : 2000;
                $rules[$key] = $f['required'] ? "required|string|max:{$max}" : "nullable|string|max:{$max}";
                $messages[$key.'.required'] = "Kolom \"{$label}\" wajib diisi.";
            }
        }

        $this->validate($rules, $messages);

        $items = [];
        foreach (array_values($this->items) as $i => $item) {
            $row = ['no' => $i + 1];
            foreach ($cfg['fields'] as $f) {
                $row[$f['key']] = $item[$f['key']] ?? '';
            }
            if ($cfg['hasSkor']) {
                $row['skor'] = max(0, min(4, (int) ($item['skor'] ?? 0)));
            }
            $items[] = $row;
        }

        $data = [
            'teacher_id' => $this->teacher_id ?: null,
            'subject_id' => $this->subject_id ?: null,
            'class_name' => $this->class_name ?: null,
            'observation_date' => $this->observation_date,
            'items' => $items,
        ];

        if ($this->postSupervision) {
            $this->postSupervision->update($data);
            session()->flash('success', 'Data diperbarui.');

            return $this->redirect(route('post-supervisions.index'), navigate: true);
        }

        $data['period_id'] = Period::fromSession()?->id;
        $data['supervisor_id'] = auth()->id();
        $data['bagian'] = $this->bagian;
        PostSupervision::create($data);
        session()->flash('success', 'Data berhasil disimpan.');

        return $this->redirect(route('post-supervisions.create', ['bagian' => $this->bagian]), navigate: true);
    }

    public function exportTemplate()
    {
        $cfg = PostSupervisionController::config($this->bagian);
        abort_if(! $cfg, 404);

        $rows = $this->postSupervision?->items ?? $this->defaultsFor($this->bagian);

        return Excel::download(new PostSupervisionTemplateExport($this->bagian, $cfg, array_values($rows)), 'template-'.$this->bagian.'.xlsx');
    }

    public function exportExcel()
    {
        $cfg = PostSupervisionController::config($this->bagian);
        abort_if(! $cfg, 404);

        $teachers = \App\Models\User::guru()->get();
        $subjects = \App\Models\Subject::all();

        $score = null;
        if ($cfg['hasSkor']) {
            $total = 0;
            $count = 0;
            foreach ($this->items as $item) {
                $total += max(0, min(4, (int) ($item['skor'] ?? 0)));
                $count++;
            }
            $score = $count > 0 ? round(($total / ($count * 4)) * 100, 2) : 0;
        }

        return Excel::download(new PostSupervisionFilledExport([
            'title' => $cfg['title'],
            'teacher' => $teachers->firstWhere('id', (int) $this->teacher_id)?->name ?? '-',
            'subject' => $subjects->firstWhere('id', (int) $this->subject_id)?->name ?? '-',
            'class_name' => $this->class_name ?: '-',
            'observation_date' => $this->observation_date,
            'supervisor' => auth()->user()->name,
            'fields' => $cfg['fields'],
            'excel_keys' => $cfg['excel_keys'] ?? null,
            'items' => array_values($this->items),
            'score' => $score,
        ]), $this->bagian.'-'.now()->format('Ymd-His').'.xlsx');
    }

    public function render()
    {
        $options = [];
        foreach (PostSupervisionController::BAGIAN as $slug => $meta) {
            $options[$slug] = $meta['label'];
        }

        return view('livewire.post-supervisions.form', [
            'options' => $options,
            'fields' => PostSupervisionController::config($this->bagian)['fields'] ?? [],
            'teachers' => \App\Models\User::guru()->get(),
            'subjects' => \App\Models\Subject::all(),
            'history' => PostSupervision::with(['teacher', 'subject'])
                ->byPeriod(Period::fromSession())
                ->byBagian($this->bagian)
                ->latest()
                ->take(10)
                ->get(),
            'bagianLabel' => PostSupervisionController::BAGIAN[$this->bagian]['label'] ?? $this->bagian,
            'is_custom_default' => $this->customRowsFor($this->bagian) !== null,
            'rekap' => $this->bagian === 'rekap' ? [
                'summary' => $this->rekapSummary(),
                'riwayat' => $this->rekapRiwayat(),
            ] : null,
        ])->layout('layouts.app', ['title' => 'Pasca Supervisi']);
    }

    protected function isGuruOnly($user): bool
    {
        return $user && $user->hasRole('guru')
            && ! $user->hasRole('admin', 'supervisor', 'kepala_sekolah', 'pengawas');
    }

    protected function rekapScope($query, $user)
    {
        if ($this->isGuruOnly($user)) {
            $query->where('teacher_id', $user->id);
        } elseif ($this->rekapTeacher !== '') {
            $query->where('teacher_id', $this->rekapTeacher);
        }

        return $query;
    }

    public function rekapSummary(): array
    {
        $user = auth()->user();
        $period = \App\Models\Period::fromSession();

        $avgByTeacher = function ($records) {
            $group = [];
            foreach ($records as $r) {
                if (! $r->teacher_id || $r->score === null) {
                    continue;
                }
                $tid = (int) $r->teacher_id;
                $group[$tid] ??= ['total' => 0, 'count' => 0];
                $group[$tid]['total'] += (float) $r->score;
                $group[$tid]['count']++;
            }
            $avg = [];
            foreach ($group as $tid => $v) {
                $avg[$tid] = $v['count'] > 0 ? round($v['total'] / $v['count'], 2) : null;
            }

            return [$avg, $group];
        };

        [$praAvg] = $avgByTeacher($this->rekapScope(\App\Models\PreObservation::byPeriod($period), $user)->get(['teacher_id', 'score']));
        [$insAvg] = $avgByTeacher($this->rekapScope(\App\Models\Instrument::byPeriod($period), $user)->get(['teacher_id', 'score']));
        [$forAvg] = $avgByTeacher($this->rekapScope(\App\Models\PostSupervision::byPeriod($period)->byBagian('asesmen-formatif'), $user)->get(['teacher_id', 'score']));
        [$sumAvg] = $avgByTeacher($this->rekapScope(\App\Models\PostSupervision::byPeriod($period)->byBagian('asesmen-sumatif'), $user)->get(['teacher_id', 'score']));
        [$tigaMAvg] = $avgByTeacher($this->rekapScope(\App\Models\PostSupervision::byPeriod($period)->byBagian('observasi-3m'), $user)->get(['teacher_id', 'score']));
        [$bbmAvg] = $avgByTeacher($this->rekapScope(\App\Models\PostSupervision::byPeriod($period)->byBagian('observasi-bbm'), $user)->get(['teacher_id', 'score']));

        if ($this->isGuruOnly($user)) {
            $teachers = \App\Models\User::where('id', $user->id)->get();
        } elseif ($this->rekapTeacher !== '') {
            $teachers = \App\Models\User::where('id', $this->rekapTeacher)->get();
        } else {
            $teachers = \App\Models\User::guru()->orderBy('name')->get();
        }

        $rows = [];
        foreach ($teachers as $guru) {
            $tid = (int) $guru->id;
            $vals = [$praAvg[$tid] ?? null, $insAvg[$tid] ?? null, $forAvg[$tid] ?? null, $sumAvg[$tid] ?? null, $tigaMAvg[$tid] ?? null, $bbmAvg[$tid] ?? null];
            $available = array_values(array_filter($vals, fn ($v) => $v !== null));
            $total = count($available) > 0 ? round(array_sum($available) / count($available), 2) : null;

            $rows[] = [
                'name' => $guru->name,
                'nip' => $guru->nip,
                'pra' => $praAvg[$tid] ?? null,
                'ins' => $insAvg[$tid] ?? null,
                'for' => $forAvg[$tid] ?? null,
                'sum' => $sumAvg[$tid] ?? null,
                'm3' => $tigaMAvg[$tid] ?? null,
                'bbm' => $bbmAvg[$tid] ?? null,
                'total' => $total,
                'kategori' => $total === null ? '-' : \App\Http\Controllers\InstrumentController::predikatRekap((float) $total),
            ];
        }

        return ['rows' => $rows];
    }

    public function rekapRiwayat(): array
    {
        $user = auth()->user();
        $period = \App\Models\Period::fromSession();

        $map = fn ($rec) => [
            'tanggal' => $rec->observation_date ? $rec->observation_date->format('d/m/Y') : '-',
            'guru' => $rec->teacher->name ?? '-',
            'mapel' => $rec->subject->name ?? '-',
            'nilai' => $rec->score !== null ? (float) $rec->score : null,
        ];

        return [
            'pra_observasi' => $this->rekapScope(\App\Models\PreObservation::with(['teacher', 'subject'])->byPeriod($period)->latest(), $user)->get()->map($map)->values()->all(),
            'instrumen' => $this->rekapScope(\App\Models\Instrument::with(['teacher', 'subject'])->byPeriod($period)->latest(), $user)->get()->map($map)->values()->all(),
            'formatif' => $this->rekapScope(\App\Models\PostSupervision::with(['teacher', 'subject'])->byPeriod($period)->byBagian('asesmen-formatif')->latest(), $user)->get()->map($map)->values()->all(),
            'sumatif' => $this->rekapScope(\App\Models\PostSupervision::with(['teacher', 'subject'])->byPeriod($period)->byBagian('asesmen-sumatif')->latest(), $user)->get()->map($map)->values()->all(),
        ];
    }
}
