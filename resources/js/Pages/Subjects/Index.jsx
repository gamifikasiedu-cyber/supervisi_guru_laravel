import { useState } from 'react';
import { Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Card, CardHeader, Table, tdClass } from '../../Components/UI';

export default function Index({ auth, subjects = [] }) {
    const [showImport, setShowImport] = useState(false);
    const imp = useForm({ file: null });
    const submitImport = (e) => {
        e.preventDefault();
        imp.post(route('subjects.import'), { onSuccess: () => { imp.reset(); setShowImport(false); } });
    };
    return (
        <AuthenticatedLayout title="Mata Pelajaran">
            <Card>
                <CardHeader
                    title={`Mata Pelajaran (${subjects.length})`}
                    action={
                        <div className="flex flex-wrap gap-2">
                            <Link href={route('subjects.create')} className="text-sm px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">+ Tambah</Link>
                            <a href={route('subjects.export')} className="text-sm px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">⬇ Excel</a>
                            <button onClick={() => setShowImport((v) => !v)} className="text-sm px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700">⬆ Impor</button>
                            <button onClick={() => confirm('Hapus SEMUA mata pelajaran?') && router.delete(route('subjects.delete-all'))} className="text-sm px-3 py-1.5 rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-200">Hapus Semua</button>
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
                <Table
                    columns={['Nama', 'Kode', 'Aksi']}
                    rows={subjects}
                    renderRow={(s) => (
                        <tr key={s.id}>
                            <td className={tdClass('font-medium text-slate-800')}>{s.name}</td>
                            <td className={tdClass()}>{s.code ?? '-'}</td>
                            <td className={tdClass('whitespace-nowrap')}>
                                <Link href={route('subjects.edit', s.id)} className="text-indigo-600 hover:underline mr-3">Edit</Link>
                                <button onClick={() => confirm(`Hapus ${s.name}?`) && router.delete(route('subjects.destroy', s.id))} className="text-rose-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    )}
                />
            </Card>
        </AuthenticatedLayout>
    );
}
