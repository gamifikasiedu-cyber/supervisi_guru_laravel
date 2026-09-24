import { useState } from 'react';
import { Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Card, CardHeader, Table, tdClass } from '../../Components/UI';

const input = 'block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border focus:border-indigo-500 focus:ring-indigo-500';

function blankItems(rows) {
    return (rows ?? []).map((r) => ({
        tahap: r.tahap ?? '', dimensi: r.dimensi ?? '', indikator: r.indikator ?? '',
        skor: '', bukti: '', catatan: '',
    }));
}

export default function Index({
    auth, rows = [], teachers = [], subjects = [], records = [], editing = null, is_custom_default = false,
}) {
    const isEdit = !!editing;
    const startItems = isEdit && editing.items ? editing.items.map((it) => ({ ...it })) : blankItems(rows);

    const { data, setData, post, put, processing, errors } = useForm({
        teacher_id: editing?.teacher_id ?? '',
        subject_id: editing?.subject_id ?? '',
        class_name: editing?.class_name ?? '',
        observation_date: editing?.observation_date ?? new Date().toISOString().slice(0, 10),
        items: startItems,
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
        if (isEdit) put(route('instruments.update', editing.id));
        else post(route('instruments.store'));
    };

    const submitImport = (e) => {
        e.preventDefault();
        imp.post(route('instruments.import'), { onSuccess: () => { imp.reset(); setShowImport(false); } });
    };

    return (
        <AuthenticatedLayout title={isEdit ? 'Edit Instrumen' : 'Instrumen Pemantauan'}>
            <Card>
                <CardHeader
                    title={isEdit ? `Edit #${editing.id}` : 'Isi Instrumen'}
                    subtitle={is_custom_default ? 'Struktur kustom (hasil impor)' : 'Struktur bawaan standar'}
                    action={
                        <div className="flex flex-wrap gap-2">
                            <a href={route('instruments.template')} className="text-sm px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">⬇ Template</a>
                            <button onClick={() => setShowImport((v) => !v)} className="text-sm px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700">⬆ Impor Struktur</button>
                            {is_custom_default && (
                                <button onClick={() => confirm('Kembalikan ke struktur standar?') && router.post(route('instruments.reset-default'))} className="text-sm px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">Reset Standar</button>
                            )}
                            <Link href={route('instruments.petunjuk')} className="text-sm px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200">Petunjuk</Link>
                            <Link href={route('instruments.rekapitulasi')} className="text-sm px-3 py-1.5 rounded-lg bg-violet-100 text-violet-700 hover:bg-violet-200">Rekapitulasi</Link>
                            {isEdit && <Link href={route('instruments.index')} className="text-sm px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">Batal Edit</Link>}
                        </div>
                    }
                />
                {showImport && (
                    <form onSubmit={submitImport} className="px-5 py-3 border-b border-slate-100 flex items-center gap-2 text-sm">
                        <input type="file" accept=".xlsx,.xls,.csv" onChange={(e) => imp.setData('file', e.target.files[0])} />
                        <button disabled={imp.processing} className="px-3 py-1.5 rounded-lg bg-sky-600 text-white disabled:opacity-50">Upload</button>
                        {imp.errors.file && <span className="text-rose-600 text-xs">{imp.errors.file}</span>}
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
                            <input value={data.class_name} onChange={(e) => setData('class_name', e.target.value)} className={input} placeholder="cth: XII RPL 1" />
                        </div>
                        <div>
                            <label className="text-sm font-medium">Tanggal</label>
                            <input type="date" value={data.observation_date} onChange={(e) => setData('observation_date', e.target.value)} className={input} />
                            {errors.observation_date && <p className="text-xs text-rose-600 mt-1">{errors.observation_date}</p>}
                        </div>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm min-w-[900px]">
                            <thead>
                                <tr className="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                                    <th className="px-4 py-2.5 w-10">No</th>
                                    <th className="px-4 py-2.5">Tahap / Dimensi / Indikator</th>
                                    <th className="px-4 py-2.5 w-24">Skor 1-4</th>
                                    <th className="px-4 py-2.5 w-48">Bukti</th>
                                    <th className="px-4 py-2.5 w-48">Catatan</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                {data.items.map((it, i) => (
                                    <tr key={i}>
                                        <td className="px-4 py-2 text-slate-400">{i + 1}</td>
                                        <td className="px-4 py-2">
                                            <p className="font-medium text-slate-700 text-xs">{it.tahap}{it.dimensi ? ` · ${it.dimensi}` : ''}</p>
                                            <p className="text-slate-600">{it.indikator}</p>
                                        </td>
                                        <td className="px-4 py-2">
                                            <select value={it.skor} onChange={(e) => setItem(i, 'skor', e.target.value)} className={input}>
                                                <option value="">-</option>
                                                {[1, 2, 3, 4].map((n) => <option key={n} value={n}>{n}</option>)}
                                            </select>
                                        </td>
                                        <td className="px-4 py-2"><input value={it.bukti ?? ''} onChange={(e) => setItem(i, 'bukti', e.target.value)} className={input} /></td>
                                        <td className="px-4 py-2"><input value={it.catatan ?? ''} onChange={(e) => setItem(i, 'catatan', e.target.value)} className={input} /></td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    {errors.items && <p className="text-xs text-rose-600 px-5 pt-2">{errors.items}</p>}
                    <div className="px-5 py-4">
                        <button disabled={processing} className="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50">
                            {isEdit ? 'Perbarui' : 'Simpan Instrumen'}
                        </button>
                    </div>
                </form>
            </Card>

            <Card className="mt-4">
                <CardHeader title={`Riwayat (${records.length})`} />
                <Table
                    columns={['Tanggal', 'Guru', 'Mapel', 'Kelas', 'Skor', 'Nilai', 'Aksi']}
                    rows={records}
                    renderRow={(r) => (
                        <tr key={r.id}>
                            <td className={tdClass()}>{r.observation_date ?? '-'}</td>
                            <td className={tdClass('font-medium text-slate-800')}>{r.teacher?.name ?? '-'}</td>
                            <td className={tdClass()}>{r.subject?.name ?? '-'}</td>
                            <td className={tdClass()}>{r.class_name ?? '-'}</td>
                            <td className={tdClass()}>{r.total_skor}/{r.max_skor}</td>
                            <td className={tdClass('font-semibold')}>{r.score}</td>
                            <td className={tdClass('whitespace-nowrap')}>
                                <Link href={route('instruments.index', { edit: r.id })} className="text-indigo-600 hover:underline mr-2">Edit</Link>
                                <a href={route('instruments.export', r.id)} className="text-emerald-600 hover:underline mr-2">Excel</a>
                                <button onClick={() => confirm('Hapus record ini?') && router.delete(route('instruments.destroy', r.id))} className="text-rose-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    )}
                />
            </Card>
        </AuthenticatedLayout>
    );
}
