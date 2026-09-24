import { router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Badge, Card, CardHeader, Table, tdClass } from '../../Components/UI';

const input = 'mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border focus:border-indigo-500 focus:ring-indigo-500';

function fmtSize(bytes) {
    if (!bytes) return '-';
    const u = ['B', 'KB', 'MB', 'GB'];
    let i = 0;
    while (bytes >= 1024 && i < 3) { bytes /= 1024; i++; }
    return `${Math.round(bytes * 100) / 100} ${u[i]}`;
}
function fmtDate(ts) {
    if (!ts) return '-';
    return new Date(ts * 1000).toLocaleString('id-ID');
}

export default function Index({ auth, settings = {}, periods = [], backups = [] }) {
    const s = useForm({
        school_name: settings.school_name ?? '', address: settings.address ?? '',
        npsn: settings.npsn ?? '', tahun_ajaran: settings.tahun_ajaran ?? '',
        semester: settings.semester ?? '', logo: null,
    });
    const p = useForm({ tahun_ajaran: '', semester: 'Ganjil' });
    const r = useForm({ file: null });

    const saveSettings = (e) => {
        e.preventDefault();
        s.post(route('settings.update'));
    };

    return (
        <AuthenticatedLayout title="Pengaturan">
            <div className="grid grid-cols-1 xl:grid-cols-2 gap-4">
                <Card>
                    <CardHeader title="Identitas Sekolah" />
                    <form onSubmit={saveSettings} className="p-5 space-y-4">
                        <div>
                            <label className="text-sm font-medium">Nama Sekolah</label>
                            <input value={s.data.school_name} onChange={(e) => s.setData('school_name', e.target.value)} className={input} />
                        </div>
                        <div>
                            <label className="text-sm font-medium">Alamat</label>
                            <textarea value={s.data.address} onChange={(e) => s.setData('address', e.target.value)} className={input} rows={2} />
                        </div>
                        <div className="grid grid-cols-2 gap-3">
                            <div>
                                <label className="text-sm font-medium">NPSN</label>
                                <input value={s.data.npsn} onChange={(e) => s.setData('npsn', e.target.value)} className={input} />
                            </div>
                            <div>
                                <label className="text-sm font-medium">Semester</label>
                                <select value={s.data.semester} onChange={(e) => s.setData('semester', e.target.value)} className={input}>
                                    <option value="">--</option>
                                    <option value="Ganjil">Ganjil</option>
                                    <option value="Genap">Genap</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label className="text-sm font-medium">Tahun Ajaran</label>
                            <input value={s.data.tahun_ajaran} onChange={(e) => s.setData('tahun_ajaran', e.target.value)} className={input} placeholder="cth: 2026/2027" />
                        </div>
                        <div>
                            <label className="text-sm font-medium">Logo (png/jpg/svg, maks 2MB)</label>
                            <div className="flex items-center gap-3 mt-1">
                                {settings.logo_url && <img src={settings.logo_url} alt="Logo" className="w-12 h-12 rounded-lg border object-contain" />}
                                <input type="file" accept="image/*" onChange={(e) => s.setData('logo', e.target.files[0])} className="text-sm" />
                            </div>
                            {s.errors.logo && <p className="text-xs text-rose-600 mt-1">{s.errors.logo}</p>}
                        </div>
                        <button disabled={s.processing} className="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50">Simpan Pengaturan</button>
                    </form>
                </Card>

                <Card>
                    <CardHeader title={`Periode Akademik (${periods.length})`} />
                    <form
                        onSubmit={(e) => { e.preventDefault(); p.post(route('periods.store'), { onSuccess: () => p.reset() }); }}
                        className="px-5 py-4 border-b border-slate-100 grid grid-cols-3 gap-2"
                    >
                        <input value={p.data.tahun_ajaran} onChange={(e) => p.setData('tahun_ajaran', e.target.value)} className={input} placeholder="2026/2027" />
                        <select value={p.data.semester} onChange={(e) => p.setData('semester', e.target.value)} className={input}>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                        <button disabled={p.processing} className="px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700 disabled:opacity-50">+ Tambah</button>
                        {p.errors.tahun_ajaran && <p className="text-xs text-rose-600 col-span-3">{p.errors.tahun_ajaran}</p>}
                    </form>
                    <Table
                        columns={['Tahun Ajaran', 'Semester', 'Aksi']}
                        rows={periods}
                        renderRow={(per) => (
                            <tr key={per.id}>
                                <td className={tdClass('font-medium text-slate-800')}>{per.tahun_ajaran}</td>
                                <td className={tdClass()}>{per.semester} {per.is_active ? <Badge value="approved" /> : null}</td>
                                <td className={tdClass('whitespace-nowrap')}>
                                    <button onClick={() => router.post(route('periods.activate', per.id))} className="text-emerald-600 hover:underline mr-3">Aktifkan</button>
                                    <button onClick={() => confirm('Hapus periode ini?') && router.delete(route('periods.destroy', per.id))} className="text-rose-600 hover:underline">Hapus</button>
                                </td>
                            </tr>
                        )}
                    />
                </Card>
            </div>

            <Card className="mt-4">
                <CardHeader
                    title={`Backup Data (${backups.length})`}
                    action={
                        <button onClick={() => router.post(route('settings.backup'))} className="text-sm px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">+ Buat Backup</button>
                    }
                />
                <form
                    onSubmit={(e) => { e.preventDefault(); r.post(route('settings.restore'), { onSuccess: () => r.reset() }); }}
                    className="px-5 py-3 border-b border-slate-100 flex items-center gap-2 text-sm"
                >
                    <span className="text-slate-500">Restore dari file:</span>
                    <input type="file" accept=".json" onChange={(e) => r.setData('file', e.target.files[0])} />
                    <button disabled={r.processing} className="px-3 py-1.5 rounded-lg bg-amber-500 text-white hover:bg-amber-600 disabled:opacity-50">Restore</button>
                    {r.errors.file && <span className="text-rose-600 text-xs">{r.errors.file}</span>}
                </form>
                <Table
                    columns={['Nama File', 'Ukuran', 'Dibuat', 'Aksi']}
                    rows={backups}
                    renderRow={(b) => (
                        <tr key={b.name}>
                            <td className={tdClass('font-medium text-slate-800')}>{b.name}</td>
                            <td className={tdClass()}>{fmtSize(b.size)}</td>
                            <td className={tdClass()}>{fmtDate(b.created_at)}</td>
                            <td className={tdClass('whitespace-nowrap')}>
                                <a href={route('settings.download', b.name)} className="text-indigo-600 hover:underline mr-3">Unduh</a>
                                <button onClick={() => confirm(`Restore dari ${b.name}? Data saat ini akan diganti.`) && router.post(route('settings.restore', b.name))} className="text-amber-600 hover:underline mr-3">Restore</button>
                                <button onClick={() => confirm('Hapus backup ini?') && router.delete(route('settings.backup.destroy', b.name))} className="text-rose-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    )}
                />
            </Card>
        </AuthenticatedLayout>
    );
}
