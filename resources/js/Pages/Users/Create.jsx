import { Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Card } from '../../Components/UI';

const ROLES = { admin: 'Admin', kepala_sekolah: 'Kepala Sekolah', supervisor: 'Supervisor', pengawas: 'Pengawas', guru: 'Guru' };
const input = 'mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border focus:border-indigo-500 focus:ring-indigo-500';

export function UserForm({ initial, submitLabel, onSubmit, processing, errors }) {
    return (
        <form onSubmit={onSubmit} className="p-5 space-y-4 max-w-2xl">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label className="text-sm font-medium">Nama</label>
                    <input value={initial.name} onChange={(e) => initial.set('name', e.target.value)} className={input} />
                    {errors.name && <p className="text-xs text-rose-600 mt-1">{errors.name}</p>}
                </div>
                <div>
                    <label className="text-sm font-medium">Email</label>
                    <input type="email" value={initial.email} onChange={(e) => initial.set('email', e.target.value)} className={input} />
                    {errors.email && <p className="text-xs text-rose-600 mt-1">{errors.email}</p>}
                </div>
                <div>
                    <label className="text-sm font-medium">Password {initial.isEdit ? '(kosongkan jika tidak diubah)' : ''}</label>
                    <input type="password" value={initial.password} onChange={(e) => initial.set('password', e.target.value)} className={input} />
                    {errors.password && <p className="text-xs text-rose-600 mt-1">{errors.password}</p>}
                </div>
                <div>
                    <label className="text-sm font-medium">NIP</label>
                    <input value={initial.nip} onChange={(e) => initial.set('nip', e.target.value)} className={input} />
                </div>
                <div className="md:col-span-2">
                    <label className="text-sm font-medium">Mata Pelajaran</label>
                    <input value={initial.mata_pelajaran} onChange={(e) => initial.set('mata_pelajaran', e.target.value)} className={input} />
                </div>
            </div>
            <div>
                <label className="text-sm font-medium">Peran (boleh lebih dari satu)</label>
                <div className="flex flex-wrap gap-3 mt-2">
                    {Object.entries(ROLES).map(([key, label]) => (
                        <label key={key} className="flex items-center gap-1.5 text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 cursor-pointer">
                            <input
                                type="checkbox"
                                checked={(initial.roles ?? []).includes(key)}
                                onChange={(e) => {
                                    const cur = initial.roles ?? [];
                                    initial.set('roles', e.target.checked ? [...cur, key] : cur.filter((r) => r !== key));
                                }}
                                className="rounded text-indigo-600"
                            />
                            {label}
                        </label>
                    ))}
                </div>
                {errors.roles && <p className="text-xs text-rose-600 mt-1">{errors.roles}</p>}
            </div>
            <div className="flex gap-2">
                <button disabled={processing} className="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50">{submitLabel}</button>
                <Link href={route('users.index')} className="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</Link>
            </div>
        </form>
    );
}

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '', email: '', password: '', roles: ['guru'], nip: '', mata_pelajaran: '',
    });
    const initial = { ...data, set: setData, isEdit: false };
    return (
        <AuthenticatedLayout title="Tambah Pengguna">
            <Card>
                <UserForm initial={initial} submitLabel="Simpan" processing={processing} errors={errors} onSubmit={(e) => { e.preventDefault(); post(route('users.store')); }} />
            </Card>
        </AuthenticatedLayout>
    );
}
