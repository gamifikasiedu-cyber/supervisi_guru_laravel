import { useState } from 'react';
import { Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Badge, Card, CardHeader, Table, tdClass } from '../../Components/UI';

const ROLES = { admin: 'Admin', kepala_sekolah: 'Kepala Sekolah', supervisor: 'Supervisor', pengawas: 'Pengawas', guru: 'Guru' };

export default function Index({ auth, users = [] }) {
    const [showImport, setShowImport] = useState(false);
    const imp = useForm({ file: null });

    const submitImport = (e) => {
        e.preventDefault();
        imp.post(route('users.import'), { onSuccess: () => { imp.reset(); setShowImport(false); } });
    };

    const destroy = (u) => {
        if (confirm(`Hapus pengguna ${u.name}?`)) router.delete(route('users.destroy', u.id));
    };
    const deleteAll = () => {
        if (confirm('Hapus SEMUA pengguna kecuali akun Anda?')) router.delete(route('users.delete-all'));
    };

    return (
        <AuthenticatedLayout title="Data Pengguna">
            <Card>
                <CardHeader
                    title={`Pengguna (${users.length})`}
                    action={
                        <div className="flex flex-wrap gap-2">
                            <Link href={route('users.create')} className="text-sm px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">+ Tambah</Link>
                            <a href={route('users.export')} className="text-sm px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">⬇ Excel</a>
                            <button onClick={() => setShowImport((v) => !v)} className="text-sm px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700">⬆ Impor</button>
                            <button onClick={deleteAll} className="text-sm px-3 py-1.5 rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-200">Hapus Semua</button>
                        </div>
                    }
                />
                {showImport && (
                    <form onSubmit={submitImport} className="px-5 py-3 border-b border-slate-100 flex items-center gap-2 text-sm">
                        <input type="file" accept=".xlsx,.xls,.csv" onChange={(e) => imp.setData('file', e.target.files[0])} />
                        <button disabled={imp.processing} className="px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700 disabled:opacity-50">Upload</button>
                        {imp.errors.file && <span className="text-rose-600 text-xs">{imp.errors.file}</span>}
                    </form>
                )}
                <Table
                    columns={['Nama', 'Email', 'NIP', 'Role', 'Aksi']}
                    rows={users}
                    renderRow={(u) => (
                        <tr key={u.id}>
                            <td className={tdClass('font-medium text-slate-800')}>{u.name}</td>
                            <td className={tdClass()}>{u.email}</td>
                            <td className={tdClass()}>{u.nip ?? '-'}</td>
                            <td className={tdClass()}>
                                <div className="flex gap-1 flex-wrap">
                                    {(u.role_list ?? [u.role]).map((r) => <Badge key={r} value={ROLES[r] ?? r} />)}
                                </div>
                            </td>
                            <td className={tdClass('whitespace-nowrap')}>
                                <Link href={route('users.edit', u.id)} className="text-indigo-600 hover:underline mr-3">Edit</Link>
                                <button onClick={() => destroy(u)} className="text-rose-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    )}
                />
            </Card>
        </AuthenticatedLayout>
    );
}
