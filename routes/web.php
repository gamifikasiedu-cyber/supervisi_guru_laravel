<?php

use App\Http\Controllers\PostSupervisionTransferController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\UserController;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Documents\Form as DocumentForm;
use App\Livewire\Documents\Index as DocumentsIndex;
use App\Livewire\Documents\Review as DocumentReview;
use App\Livewire\Instruments\Form as InstrumentForm;
use App\Livewire\Instruments\Index as InstrumentsIndex;
use App\Livewire\Instruments\Rekap as InstrumentsRekap;
use App\Livewire\Konferensis\Form as KonferensiForm;
use App\Livewire\Konferensis\Index as KonferensisIndex;
use App\Livewire\Periods\Form as PeriodForm;
use App\Livewire\Periods\Index as PeriodsIndex;
use App\Livewire\PostSupervisions\Form as PostSupervisionForm;
use App\Livewire\PostSupervisions\Index as PostSupervisionsIndex;
use App\Livewire\PreObservations\Form as PreObservationForm;
use App\Livewire\PreObservations\Index as PreObservationsIndex;
use App\Livewire\Profile\Edit as ProfileEdit;
use App\Livewire\Settings\Manage as SettingsManage;
use App\Livewire\Subjects\Form as SubjectForm;
use App\Livewire\Subjects\Index as SubjectsIndex;
use App\Livewire\Supervisions\Form as SupervisionForm;
use App\Livewire\Supervisions\Index as SupervisionsIndex;
use App\Livewire\Users\Form as UserForm;
use App\Livewire\Users\Index as UsersIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::delete('/records/{type}/{id}', [RecordController::class, 'destroy'])->name('records.destroy');

    // Master Data (admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', UsersIndex::class)->name('users.index');
        Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
        Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
        Route::delete('/users/delete-all', [UserController::class, 'deleteAll'])->name('users.delete-all');
        Route::get('/users/create', UserForm::class)->name('users.create');
        Route::get('/users/{user}/edit', UserForm::class)->name('users.edit');

        Route::get('/subjects', SubjectsIndex::class)->name('subjects.index');
        Route::get('/subjects/create', SubjectForm::class)->name('subjects.create');
        Route::get('/subjects/{subject}/edit', SubjectForm::class)->name('subjects.edit');

        Route::get('/periods', PeriodsIndex::class)->name('periods.index');
        Route::get('/periods/create', PeriodForm::class)->name('periods.create');
        Route::get('/periods/{period}/edit', PeriodForm::class)->name('periods.edit');
        Route::post('/periods/{period}/activate', function (App\Models\Period $period) {
            session()->put('active_period_id', $period->id);

            return back()->with('success', 'Periode aktif: '.$period->label().'.');
        })->name('periods.activate');

        Route::get('/settings', SettingsManage::class)->name('settings.index');
    });

    // Supervisi
    Route::get('/supervisions', SupervisionsIndex::class)->name('supervisions.index');
    Route::get('/supervisions/create', SupervisionForm::class)->name('supervisions.create');
    Route::get('/supervisions/{supervision}/edit', SupervisionForm::class)->name('supervisions.edit');

    Route::get('/documents', DocumentsIndex::class)->name('documents.index');
    Route::get('/documents/create', DocumentForm::class)->name('documents.create');
    Route::get('/documents/{document}/edit', DocumentForm::class)->name('documents.edit');
    Route::get('/documents/{document}/review', DocumentReview::class)->name('documents.review');
    Route::get('/documents/{document}/download', function (App\Models\TeachingDocument $document) {
        abort_unless(
            auth()->user()->isAdmin() || auth()->user()->isSupervisor() || auth()->user()->isPengawas() || auth()->user()->isKepalaSekolah() || $document->user_id === auth()->id(),
            403
        );

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    })->name('documents.download');

    // Penilaian (area supervisor: admin, supervisor, kepala sekolah, pengawas)
    Route::middleware('role:admin,supervisor,kepala_sekolah,pengawas')->group(function () {
        Route::get('/pemantauan', InstrumentsIndex::class)->name('pemantauan.index');
        Route::get('/pemantauan/create', InstrumentForm::class)->name('pemantauan.create');
        Route::get('/pemantauan/{instrument}/edit', InstrumentForm::class)->name('pemantauan.edit');
        Route::get('/pemantauan/rekapitulasi', InstrumentsRekap::class)->name('pemantauan.rekapitulasi');

        Route::get('/pre-observations', PreObservationsIndex::class)->name('pre-observations.index');
        Route::get('/pre-observations/create', PreObservationForm::class)->name('pre-observations.create');
        Route::get('/pre-observations/{preObservation}/edit', PreObservationForm::class)->name('pre-observations.edit');

        Route::get('/konferensis', KonferensisIndex::class)->name('konferensis.index');
        Route::get('/konferensis/create', KonferensiForm::class)->name('konferensis.create');
        Route::get('/konferensis/{konferensi}/edit', KonferensiForm::class)->name('konferensis.edit');
        Route::get('/konferensis/{konferensi}/cetak', \App\Livewire\Konferensis\Cetak::class)->name('konferensis.cetak');
        Route::get('/konferensis/{konferensi}/foto', \App\Livewire\Konferensis\Photos::class)->name('konferensis.photos');
        Route::get('/konferensis/{konferensi}/foto/{index}/download', function (App\Models\PreObservationKonferensi $konferensi, int $index) {
            $photos = array_values(array_filter((array) $konferensi->dokumentasi_foto));

            abort_unless(isset($photos[$index]), 404);
            abort_unless(\Illuminate\Support\Facades\Storage::disk('public')->exists($photos[$index]), 404);

            return \Illuminate\Support\Facades\Storage::disk('public')->download($photos[$index]);
        })->name('konferensis.photo-download');
        Route::get('/konferensis/{konferensi}/foto/unduh-semua', function (App\Models\PreObservationKonferensi $konferensi) {
            $photos = array_values(array_filter((array) $konferensi->dokumentasi_foto));
            abort_if(empty($photos), 404);

            $zipName = 'dokumentasi-konferensi-'.$konferensi->id.'-'.now()->format('Ymd-His').'.zip';
            $tmpPath = storage_path('app/'.$zipName);
            $zip = new \ZipArchive;
            $zip->open($tmpPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            foreach ($photos as $i => $path) {
                $full = \Illuminate\Support\Facades\Storage::disk('public')->path($path);
                if (is_file($full)) {
                    $zip->addFile($full, ($i + 1).'-'.basename($path));
                }
            }
            $zip->close();

            return response()->download($tmpPath)->deleteFileAfterSend();
        })->name('konferensis.photos-download');

        Route::get('/post-supervisions', PostSupervisionsIndex::class)->name('post-supervisions.index');
        Route::post('/post-supervisions/import/{bagian}', [PostSupervisionTransferController::class, 'import'])->name('post-supervisions.import');
        Route::get('/post-supervisions/create', PostSupervisionForm::class)->name('post-supervisions.create');
        Route::get('/post-supervisions/cetak', \App\Livewire\PostSupervisions\Cetak::class)->name('post-supervisions.cetak');
        Route::get('/post-supervisions/{postSupervision}/edit', PostSupervisionForm::class)->name('post-supervisions.edit');
    }); // <-- Penutup group middleware role penilai yang sebelumnya kurang

    Route::get('/profile', ProfileEdit::class)->name('profile.edit');
});