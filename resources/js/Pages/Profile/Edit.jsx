import { useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Card, CardHeader } from '../../Components/UI';

const input = 'mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border focus:border-indigo-500 focus:ring-indigo-500';

export default function Edit({ auth, mustVerifyEmail, status }) {
    const info = useForm({ name: auth.user.name ?? '', email: auth.user.email ?? '' });
    const del = useForm({ password: '' });

    return (
        <AuthenticatedLayout title="Profil Saya">
            <div className="grid grid-cols-1 xl:grid-cols-2 gap-4 max-w-5xl">
                <Card>
                    <CardHeader title="Informasi Akun" />
                    <form
                        onSubmit={(e) => { e.preventDefault(); info.patch(route('profile.update')); }}
                        className="p-5 space-y-4"
                    >
                        <div>
                            <label className="text-sm font-medium">Nama</label>
                            <input value={info.data.name} onChange={(e) => info.setData('name', e.target.value)} className={input} />
                            {info.errors.name && <p className="text-xs text-rose-600 mt-1">{info.errors.name}</p>}
                        </div>
                        <div>
                            <label className="text-sm font-medium">Email</label>
                            <input type="email" value={info.data.email} onChange={(e) => info.setData('email', e.target.value)} className={input} />
                            {info.errors.email && <p className="text-xs text-rose-600 mt-1">{info.errors.email}</p>}
                        </div>
                        {mustVerifyEmail && auth.user.email_verified_at === null && (
                            <p className="text-sm text-amber-600">Email belum diverifikasi.</p>
                        )}
                        {status === 'verification-link-sent' && (
                            <p className="text-sm text-emerald-600">Link verifikasi baru telah dikirim.</p>
                        )}
                        <button disabled={info.processing} className="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50">Simpan</button>
                    </form>
                </Card>

                <Card>
                    <CardHeader title="Hapus Akun" subtitle="Tindakan ini permanen" />
                    <form
                        onSubmit={(e) => { e.preventDefault(); if (confirm('Yakin hapus akun Anda?')) del.delete(route('profile.destroy')); }}
                        className="p-5 space-y-4"
                    >
                        <div>
                            <label className="text-sm font-medium">Password saat ini</label>
                            <input type="password" value={del.data.password} onChange={(e) => del.setData('password', e.target.value)} className={input} />
                            {del.errors.password && <p className="text-xs text-rose-600 mt-1">{del.errors.password}</p>}
                        </div>
                        <button disabled={del.processing} className="px-4 py-2 rounded-lg bg-rose-600 text-white text-sm font-medium hover:bg-rose-700 disabled:opacity-50">Hapus Akun</button>
                    </form>
                </Card>
            </div>
        </AuthenticatedLayout>
    );
}
