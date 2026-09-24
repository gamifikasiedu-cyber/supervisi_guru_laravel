import { useState } from 'react';
import { Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Card, CardHeader, Table, tdClass } from '../../Components/UI';

const input = 'block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border focus:border-indigo-500 focus:ring-indigo-500';

function FieldInput({ field, value, onChange }) {
    if (field.type === 'skor') {
        return (
            <select value={value ?? ''} onChange={(e) => onChange(e.target.value)} className={input}>
                <option value="">-</option>
                {[1, 2, 3, 4].map((n) => <option key={n} value={n}>{n}</option>)}
            </select>
        );
    }
    if (field.type === 'select') {
        return (
            <select value={value ?? ''} onChange={(e) => onChange(e.target.value)} className={input}>
                <option value="">--</option>
                {(field.options ?? []).map((o) => <option key={o} value={o}>{o}</option>)}
            </select>
        );
    }
    if (field.type === 'textarea') {
        return <textarea value={value ?? ''} onChange={(e) => onChange(e.target.value)} className={input} rows={2} />;
    }
    return <input value={value ?? ''} onChange={(e) => onChange(e.target.value)} className={input} />;
}

function RekapView({ summary, riwayat }) {
    if (!summary) return null;
    const rows = summary.rows ?? [];
    const avg = summary.averages ?? {};
    const cell = (v) => (v === null || v === undefined ? '-' : v);
    return (
        <div className="p-5 space-y-6">
            <section>
                <h3 className="font-semibold text-slate-800 mb-2">Rekap Otomatis per Guru</h3>
                <Table
                    columns={['Guru', 'Pra-Obs', 'Instrumen', 'Formatif', 'Sumatif', 'Obs 3M', 'Obs BBM', 'Total', 'Kategori']}
                    rows={rows}
                    renderRow={(g) => (
                        <tr key={g.teacher_id}>
                            <td className={tdClass('font-medium text-slate-800')}>{g.name}</td>
                            <td className={tdClass()}>{cell(g.pra_observasi)}</td>
                            <td className={tdClass()}>{cell(g.instrumen)}</td>
                            <td className={tdClass()}>{cell(g.formatif)}</td>
                            <td className={tdClass()}>{cell(g.sumatif)}</td>
                            <td className={tdClass()}>{cell(g.observasi_3m)}</td>
                            <td className={tdClass()}>{cell(g.observasi_bbm)}</td>
                            <td className={tdClass('font-semibold')}>{cell(g.total_rata)}</td>
                            <td className={tdClass()}>{g.kategori}</td>
                        </tr>
                    )}
                />
                <p className="text-xs text-slate-500 mt-2">
                    Rata-rata kolom: Pra {cell(avg.pra_observasi)} · Instrumen {cell(avg.instrumen)} · Formatif {cell(avg.formatif)} · Sumatif {cell(avg.sumatif)} · 3M {cell(avg.observasi_3m)} · BBM {cell(avg.observasi_bbm)} · Total {cell(avg.total_rata)}
                </p>
            </section>
            {riwayat && Object.entries({ pra_observasi: 'Pra Observasi', instrumen: 'Instrumen', formatif: 'Asesmen Formatif', sumatif: 'Asesmen Sumatif' }).map(([key, label]) => (
                <section key={key}>
                    <h3 className="font-semibold text-slate-800 mb-2">Riwayat {label}</h3>
                    <Table
                        columns={['Tanggal', 'Guru', 'Mapel', 'Kelas', 'Skor', 'Nilai']}
                        rows={riwayat[key] ?? []}
                        renderRow={(r) => (
                            <tr key={r.id}>
                                <td className={tdClass()}>{r.tanggal}</td>
                                <td className={tdClass()}>{r.guru}</td>
                                <td className={tdClass()}>{r.mapel}</td>
                                <td className={tdClass()}>{r.kelas}</td>
                                <td className={tdClass()}>{r.skor}</td>
                                <td className={tdClass('font-semibold')}>{r.nilai ?? '-'}</td>
                            </tr>
                        )}
                    />
                </section>
            ))}
        </div>
    );
}

export default function Index(props) {
    const {
        bagian, meta, nav = [], rows = [], teachers = [], subjects = [],
        records = [], editing = null, is_custom_default = false,
        rekap_summary = null, rekap_riwayat = null,
    } = props;
    const isEdit = !!editing;
    const fields = meta?.fields ?? [];

    const startItems = isEdit && editing.items
        ? editing.items.map((it) => ({ ...it }))
        : (rows ?? []).map((r) => ({ ...r }));

    const { data, setData, post, put, processing, errors } = useForm({
        teacher_id: editing?.teacher_id ?? '',
        subject_id: editing?.subject_id ?? '',
        class_name: editing?.class_name ?? '',
        observation_date: editing?.observation_date ?? new Date().toISOString().slice(0, 10),
        jenis_observasi: editing?.jenis_observasi ?? '',
        items: startItems,
    });
    const [showImport, setShowImport] = useState(false);
    const imp = useForm({ file: null });

    const setItem = (idx, key, val) => {
        const items = [...data.items];
        items[idx] = { ...items[idx], [key]: val };
        setData('items', items);
    };

    const addRow = () => {
        const blank = {};
        fields.forEach((f) => { blank[f.key] = ''; });
        setData('items', [...data.items, blank]);
    };
    const removeRow = (idx) => setData('items', data.items.filter((_, i) => i !== idx));

    const submit = (e) => {
        e.preventDefault();
        if (isEdit) put(route('pasca-supervisi.update', [bagian, editing.id]));
        else post(route('pasca-supervisi.store', bagian));
    };

    const isRekap = bagian === 'rekap';
    const canReset = is_custom_default;

    return (
        <AuthenticatedLayout title={meta?.title ?? 'Pasca Supervisi'}>
            {/* Navigasi 8 bagian */}
            <div className="flex gap-2 overflow-x-auto pb-2 mb-4">
                {nav.map((n) => (
                    <Link
                        key={n.slug}
                        href={route('pasca-supervisi.show', n.slug)}
                        className={`px-3.5 py-2 rounded-lg text-sm font-medium whitespace-nowrap ${n.slug === bagian ? 'bg-indigo-600 text-white shadow' : 'bg-white text-slate-600 border border-slate-200 hover:border-indigo-300'}`}
                    >
                        {n.label}
                    </Link>
                ))}
            </div>

            <Card>
                <CardHeader
                    title={meta?.title}
                    subtitle={meta?.subtitle}
                    action={!isRekap && (
                        <div className="flex flex-wrap gap-2">
                            <a href={route('pasca-supervisi.template', bagian)} className="text-sm px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">⬇ Template</a>
                            <button onClick={() => setShowImport((v) => !v)} className="text-sm px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700">⬆ Impor</button>
                            {canReset && (
                                <button onClick={() => confirm('Kembalikan ke standar?') && router.post(route('pasca-supervisi.reset-default', bagian))} className="text-sm px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">Reset Standar</button>
                            )}
                            {isEdit && <Link href={route('pasca-supervisi.show', bagian)} className="text-sm px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">Batal Edit</Link>}
                        </div>
                    )}
                />

                {isRekap ? (
                    <RekapView summary={rekap_summary} riwayat={rekap_riwayat} />
                ) : (
                    <>
                        {showImport && (
                            <form onSubmit={(e) => { e.preventDefault(); imp.post(route('pasca-supervisi.import', bagian), { onSuccess: () => { imp.reset(); setShowImport(false); } }); }} className="px-5 py-3 border-b border-slate-100 flex items-center gap-2 text-sm">
                                <input type="file" accept=".xlsx,.xls,.csv" onChange={(e) => imp.setData('file', e.target.files[0])} />
                                <button disabled={imp.processing} className="px-3 py-1.5 rounded-lg bg-sky-600 text-white disabled:opacity-50">Upload</button>
                            </form>
                        )}
                        <form onSubmit={submit}>
                            <div className="px-5 py-4 grid grid-cols-1 md:grid-cols-5 gap-3 border-b border-slate-100">
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
                                {bagian === 'asesmen-formatif' && (
                                    <div>
                                        <label className="text-sm font-medium">Jenis Observasi</label>
                                        <select value={data.jenis_observasi} onChange={(e) => setData('jenis_observasi', e.target.value)} className={input}>
                                            <option value="">--</option>
                                            <option value="Observasi_3M">Observasi 3M</option>
                                            <option value="Observasi_BBM">Observasi BBM</option>
                                        </select>
                                    </div>
                                )}
                            </div>
                            <div className="overflow-x-auto">
                                <table className="w-full text-sm min-w-[900px]">
                                    <thead>
                                        <tr className="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                                            <th className="px-4 py-2.5 w-10">No</th>
                                            {fields.map((f) => <th key={f.key} className="px-4 py-2.5">{f.label}</th>)}
                                            <th className="px-4 py-2.5 w-12"></th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-slate-100">
                                        {data.items.map((it, i) => (
                                            <tr key={i}>
                                                <td className="px-4 py-2 text-slate-400">{i + 1}</td>
                                                {fields.map((f) => (
                                                    <td key={f.key} className="px-4 py-2 min-w-[140px]">
                                                        <FieldInput field={f} value={it[f.key]} onChange={(v) => setItem(i, f.key, v)} />
                                                    </td>
                                                ))}
                                                <td className="px-4 py-2">
                                                    <button type="button" onClick={() => removeRow(i)} className="text-rose-500 hover:text-rose-700">✕</button>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                            <div className="px-5 py-4 flex gap-2">
                                <button type="button" onClick={addRow} className="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">+ Baris</button>
                                <button disabled={processing} className="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50">
                                    {isEdit ? 'Perbarui' : 'Simpan'}
                                </button>
                            </div>
                            {errors.items && <p className="text-xs text-rose-600 px-5 pb-3">{errors.items}</p>}
                        </form>
                    </>
                )}
            </Card>

            {!isRekap && (
                <Card className="mt-4">
                    <CardHeader title={`Riwayat ${meta?.label} (${records.length})`} />
                    <Table
                        columns={['Tanggal', 'Guru', 'Mapel', 'Nilai', 'Aksi']}
                        rows={records}
                        renderRow={(r) => (
                            <tr key={r.id}>
                                <td className={tdClass()}>{r.observation_date ?? '-'}</td>
                                <td className={tdClass('font-medium text-slate-800')}>{r.teacher?.name ?? '-'}</td>
                                <td className={tdClass()}>{r.subject?.name ?? '-'}</td>
                                <td className={tdClass('font-semibold')}>{r.score ?? '-'}</td>
                                <td className={tdClass('whitespace-nowrap')}>
                                    <Link href={route('pasca-supervisi.show', [bagian, { edit: r.id }])} className="text-indigo-600 hover:underline mr-2">Edit</Link>
                                    <a href={route('pasca-supervisi.export', [bagian, r.id])} className="text-emerald-600 hover:underline mr-2">Excel</a>
                                    <button onClick={() => confirm('Hapus?') && router.delete(route('pasca-supervisi.destroy', [bagian, r.id]))} className="text-rose-600 hover:underline">Hapus</button>
                                </td>
                            </tr>
                        )}
                    />
                </Card>
            )}
        </AuthenticatedLayout>
    );
}
