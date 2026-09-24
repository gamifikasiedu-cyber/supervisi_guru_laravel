import { Link, useForm } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

const input = 'mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2.5 border';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('register'), { onFinish: () => reset('password', 'password_confirmation') });
    };

    return (
        <GuestLayout title="Register">
            <h2 className="text-lg font-bold text-slate-800">Buat akun baru</h2>
            <p className="text-sm text-slate-500 mb-5">Akun baru terdaftar sebagai Guru</p>
            <form onSubmit={submit} className="space-y-4">
                <div>
                    <label className="text-sm font-medium text-slate-700">Nama Lengkap</label>
                    <input value={data.name} onChange={(e) => setData('name', e.target.value)} className={input} autoFocus />
                    {errors.name && <p className="text-xs text-rose-600 mt-1">{errors.name}</p>}
                </div>
                <div>
                    <label className="text-sm font-medium text-slate-700">Email</label>
                    <input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} className={input} />
                    {errors.email && <p className="text-xs text-rose-600 mt-1">{errors.email}</p>}
                </div>
                <div>
                    <label className="text-sm font-medium text-slate-700">Password</label>
                    <input type="password" value={data.password} onChange={(e) => setData('password', e.target.value)} className={input} />
                    {errors.password && <p className="text-xs text-rose-600 mt-1">{errors.password}</p>}
                </div>
                <div>
                    <label className="text-sm font-medium text-slate-700">Konfirmasi Password</label>
                    <input type="password" value={data.password_confirmation} onChange={(e) => setData('password_confirmation', e.target.value)} className={input} />
                </div>
                <button type="submit" disabled={processing} className="w-full py-2.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 disabled:opacity-50 transition">
                    Daftar
                </button>
            </form>
            <p className="mt-5 text-sm text-center">
                <Link href={route('login')} className="text-indigo-600 hover:underline">Sudah punya akun? Masuk</Link>
            </p>
        </GuestLayout>
    );
}
