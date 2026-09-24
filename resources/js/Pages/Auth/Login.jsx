import { Link, useForm } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

const input = 'mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2.5 border';

export default function Login({ status, canResetPassword, periods }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
        period_id: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('login'), { onFinish: () => reset('password') });
    };

    return (
        <GuestLayout title="Login">
            <h2 className="text-lg font-bold text-slate-800">Selamat datang kembali</h2>
            <p className="text-sm text-slate-500 mb-5">Masuk untuk mengelola supervisi akademik</p>

            {status && <div className="mb-4 text-sm text-emerald-600 bg-emerald-50 rounded-lg px-3 py-2">{status}</div>}

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <label className="text-sm font-medium text-slate-700">Email</label>
                    <input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} className={input} autoFocus placeholder="nama@sekolah.id" />
                    {errors.email && <p className="text-xs text-rose-600 mt-1">{errors.email}</p>}
                </div>
                <div>
                    <label className="text-sm font-medium text-slate-700">Password</label>
                    <input type="password" value={data.password} onChange={(e) => setData('password', e.target.value)} className={input} placeholder="••••••••" />
                    {errors.password && <p className="text-xs text-rose-600 mt-1">{errors.password}</p>}
                </div>
                {(periods ?? []).length > 0 && (
                    <div>
                        <label className="text-sm font-medium text-slate-700">Periode Akademik</label>
                        <select value={data.period_id} onChange={(e) => setData('period_id', e.target.value)} className={input}>
                            <option value="">-- Tanpa periode --</option>
                            {(periods ?? []).map((p) => (
                                <option key={p.id} value={p.id}>{p.tahun_ajaran} · {p.semester}</option>
                            ))}
                        </select>
                        {errors.period_id && <p className="text-xs text-rose-600 mt-1">{errors.period_id}</p>}
                    </div>
                )}
                <label className="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" checked={data.remember} onChange={(e) => setData('remember', e.target.checked)} className="rounded border-slate-300 text-indigo-600" />
                    Ingat saya
                </label>
                <button type="submit" disabled={processing} className="w-full py-2.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 disabled:opacity-50 transition">
                    {processing ? 'Memproses…' : 'Masuk'}
                </button>
            </form>

            <div className="mt-5 flex justify-between text-sm">
                {canResetPassword && <Link href={route('password.request')} className="text-indigo-600 hover:underline">Lupa password?</Link>}
                <Link href={route('register')} className="text-indigo-600 hover:underline">Belum punya akun?</Link>
            </div>
        </GuestLayout>
    );
}
