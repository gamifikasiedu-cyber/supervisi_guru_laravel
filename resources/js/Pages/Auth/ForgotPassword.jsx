import { useForm } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

export default function ForgotPassword({ status }) {
    const { data, setData, post, processing, errors } = useForm({ email: '' });
    const submit = (e) => {
        e.preventDefault();
        post(route('password.email'));
    };
    return (
        <GuestLayout title="Lupa Password">
            <h2 className="text-lg font-bold text-slate-800">Lupa password?</h2>
            <p className="text-sm text-slate-500 mb-5">Masukkan email, kami kirim link reset password.</p>
            {status && <div className="mb-4 text-sm text-emerald-600 bg-emerald-50 rounded-lg px-3 py-2">{status}</div>}
            <form onSubmit={submit} className="space-y-4">
                <input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} placeholder="nama@sekolah.id" className="block w-full rounded-lg border-slate-300 text-sm px-3 py-2.5 border focus:border-indigo-500 focus:ring-indigo-500" />
                {errors.email && <p className="text-xs text-rose-600">{errors.email}</p>}
                <button type="submit" disabled={processing} className="w-full py-2.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 disabled:opacity-50 transition">
                    Kirim Link Reset
                </button>
            </form>
        </GuestLayout>
    );
}
