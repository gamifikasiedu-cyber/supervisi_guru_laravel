import { useForm } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

const input = 'block w-full rounded-lg border-slate-300 text-sm px-3 py-2.5 border focus:border-indigo-500 focus:ring-indigo-500';

export default function ResetPassword({ token, email }) {
    const { data, setData, post, processing, errors } = useForm({
        token: token ?? '',
        email: email ?? '',
        password: '',
        password_confirmation: '',
    });
    const submit = (e) => {
        e.preventDefault();
        post(route('password.store'));
    };
    return (
        <GuestLayout title="Reset Password">
            <h2 className="text-lg font-bold text-slate-800 mb-5">Atur password baru</h2>
            <form onSubmit={submit} className="space-y-4">
                <input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} placeholder="Email" className={input} />
                {errors.email && <p className="text-xs text-rose-600">{errors.email}</p>}
                <input type="password" value={data.password} onChange={(e) => setData('password', e.target.value)} placeholder="Password baru" className={input} />
                {errors.password && <p className="text-xs text-rose-600">{errors.password}</p>}
                <input type="password" value={data.password_confirmation} onChange={(e) => setData('password_confirmation', e.target.value)} placeholder="Konfirmasi password" className={input} />
                <button type="submit" disabled={processing} className="w-full py-2.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 disabled:opacity-50 transition">
                    Reset Password
                </button>
            </form>
        </GuestLayout>
    );
}
