import { useState } from 'react';
import { Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Card, CardHeader, Table, tdClass } from '../../Components/UI';

const input = 'block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border focus:border-indigo-500 focus:ring-indigo-500';

export default function Konferensi({ auth, rows = [], teachers = [], subjects = [], records = [], editing = null }) {
    const isEdit = !!editing;
    const startItems = isEdit && editing.items
        ? editing.items.map((it) => ({ ...it }))
        : (rows ?? []).map((r) => ({
            pertanyaan: r.pertanyaan ?? '', catatan_guru: '', catatan_supervisor: '', kesepakatan: '', tindak_lanjut: '',
        }));
    const existingPhotos = isEdit ? (editing.dokumentasi_foto ?? []) : [];
    const photoUrls = isEdit ? (editing.dokumentasi_urls ?? []) : [];

    const { data, setData, post, processing, errors } = useForm({
        teacher_id: editing?.teacher_id ?? '',
        subject_id: editing?.subject_id ?? '',
        class_name: editing?.class_name ?? '',
        observation_date: editing?.observation_date ?? new Date().toISOString().slice(0, 10),
        items: startItems,
        dokumentasi: [],
        keep_photos: existingPhotos,
    });
    const [showImport, setShowImport] = useState(false);
    const imp = useForm({ file: null });

    const setItem = (idx, key, val) => {
        const items = [...data.items];
        items[idx] = { ...items[idx], [key]: val };
        setData('items', items);
    };

    const submit = (e) => {
        e.preventDefault();
        if (isEdit) {
            post(route('konferensi.update', editing.id), { data: { ...data, _method: 'put' } });
        } else {
            post(route('konferensi.store'));
        }
    };

    const deletePhoto = (path) => {
        if (!confirm('Hapus foto ini?')) return;
        router.delete(route('konferensi.destroy-foto', editing.id), { data: { path } });
    };

    return (
        <AuthenticatedLayout title={isEdit ? 'Edit Konferensi' : 'Konferensi-Wawancara'}>
            <Card>
                <CardHeader
                    title={isEdit ? `Edit #${editing.id}` : 'Konferensi-Wawancara Pra Observasi'}
                    action={
                        <div className="flex flex-wrap gap-2">
                            <a href={route('konferensi.template')} className="text-sm px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">⬇ Template</a>
                            <button onClick={() => setShowImport((v) => !v)} className="text-sm px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700">⬆ Impor</button>
                            {isEdit && <Link href={route('konferensi.index')} className="text-sm px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">Batal Edit</Link>}
                        </div>
                    }
                />
                {showImport && (
                    <form onSubmit={(e) => { e.preventDefault(); imp.post(route('konferensi.import'), { onSuccess: () => { imp.reset(); setShowImport(false); } }); }} className="px-5 py-3 border-b border-slate-100 flex items-center gap-2 text-sm">
                        <input type="file" accept=".xlsx,.xls,.csv" onChange={(e) => imp.setData('file', e.target.files[0])} />
                        <button disabled={imp.processing} className="px-3 py-1.5 rounded-lg bg-sky-600 text-white disabled:opacity-50">Upload</button>
                    </form>
                )}
                <form onSubmit={submit}>
                    <div className="px-5 py-4 grid grid-cols-1 md:grid-cols-4 gap-3 border-b border-slate-100">
                        <div>
                            <label className="text-sm font-medium">Guru</label>
                            <select value={data.teacher_id} onChange={(e) => setData('teacher_id', e.target.value)} className={input}>
                                <option value="">-- Pilih --</option>
                                {teachers.map((t) => <option key={t.id} value={t.id}>{t.name}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="text-sm font-medium">Mapel</label>
                            <select value={data.subject_id} onChange={(e) => setData('subject_id', e.target.value)} className={input}>
                                <option value="">-- Pilih --</option>
                                {subjects.map((s) => <option key={s.id} value={s.id}>{s.name}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="text-sm font-medium">Kelas</label>
                            <input value={data.class_name} onChange={(e) => setData('class_name', e.target.value)} className={input} />
                        </div>
                        <div>
                            <label className="text-sm font-medium">Tanggal</label>
                            <input type="date" value={data.observation_date} onChange={(e) => setData('observation_date', e.target.value)} className={input} />
                        </div>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm min-w-[1000px]">
                            <thead>
                                <tr className="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                                    <th className="px-4 py-2.5 w-10">No</th>
                                    <th className="px-4 py-2.5">Pertanyaan / Fokus</th>
                                    <th className="px-4 py-2.5 w-44">Catatan Guru</th>
                                    <th className="px-4 py-2.5 w-44">Catatan Supervisor</th>
                                    <th className="px-4 py-2.5 w-44">Kesepakatan</th>
                                    <th className="px-4 py-2.5 w-44">Tindak Lanjut</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                {data.items.map((it, i) => (
                                    <tr key={i}>
                                        <td className="px-4 py-2 text-slate-400">{i + 1}</td>
                                        <td className="px-4 py-2 text-slate-700">{it.pertanyaan}</td>
                                        <td className="px-4 py-2"><textarea value={it.catatan_guru ?? ''} onChange={(e) => setItem(i, 'catatan_guru', e.target.value)} className={input} rows={2} /></td>
                                        <td className="px-4 py-2"><textarea value={it.catatan_supervisor ?? ''} onChange={(e) => setItem(i, 'catatan_supervisor', e.target.value)} className={input} rows={2} /></td>
                                        <td className="px-4 py-2"><textarea value={it.kesepakatan ?? ''} onChange={(e) => setItem(i, 'kesepakatan', e.target.value)} className={input} rows={2} /></td>
                                        <td className="px-4 py-2"><textarea value={it.tindak_lanjut ?? ''} onChange={(e) => setItem(i, 'tindak_lanjut', e.target.value)} className={input} rows={2} /></td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <div className="px-5 py-4 border-t border-slate-100">
                        <label className="text-sm font-medium">Foto Dokumentasi (maks 5, jpg/png/webp)</label>
                        <input type="file" accept="image/*" multiple onChange={(e) => setData('dokumentasi', Array.from(e.target.files))} className="mt-1 block w-full text-sm" />
                        {errors.dokumentasi && <p className="text-xs text-rose-600 mt-1">{errors.dokumentasi}</p>}
                        {isEdit && photoUrls.length > 0 && (
                            <div className="flex flex-wrap gap-2 mt-3">
                                {photoUrls.map((url, i) => (
                                    <div key={i} className="relative">
                                        <img src={url} alt="dokumentasi" className="w-24 h-24 object-cover rounded-lg border" />
                                        <button type="button" onClick={() => deletePhoto(existingPhotos[i])} className="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-rose-600 text-white text-xs">✕</button>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                    {errors.items && <p className="text-xs text-rose-600 px-5 pt-2">{errors.items}</p>}
                    <div className="px-5 py-4">
                        <button disabled={processing} className="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50">
                            {isEdit ? 'Perbarui' : 'Simpan'}
                        </button>
                    </div>
                </form>
            </Card>

            <Card className="mt-4">
                <CardHeader title={`Riwayat (${records.length})`} />
                <Table
                    columns={['Tanggal', 'Guru', 'Mapel', 'Kelas', 'Foto', 'Aksi']}
                    rows={records}
                    renderRow={(r) => (
                        <tr key={r.id}>
                            <td className={tdClass()}>{r.observation_date ?? '-'}</td>
                            <td className={tdClass('font-medium text-slate-800')}>{r.teacher?.name ?? '-'}</td>
                            <td className={tdClass()}>{r.subject?.name ?? '-'}</td>
                            <td className={tdClass()}>{r.class_name ?? '-'}</td>
                            <td className={tdClass()}>{(r.dokumentasi_foto ?? []).length} foto</td>
                            <td className={tdClass('whitespace-nowrap')}>
                                <Link href={route('konferensi.index', { edit: r.id })} className="text-indigo-600 hover:underline mr-2">Edit</Link>
                                <a href={route('konferensi.export', r.id)} className="text-emerald-600 hover:underline mr-2">Excel</a>
                                <button onClick={() => confirm('Hapus?') && router.delete(route('konferensi.destroy', r.id))} className="text-rose-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    )}
                />
            </Card>
        </AuthenticatedLayout>
    );
}
