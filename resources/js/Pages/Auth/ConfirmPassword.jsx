import { useForm } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors } = useForm({ password: '' });
    const submit = (e) => {
        e.preventDefault();
        post(route('password.confirm'));
    };
    return (
        <GuestLayout title="Konfirmasi Password">
            <h2 className="text-lg font-bold text-slate-800 mb-2">Area aman</h2>
            <p className="text-sm text-slate-500 mb-5">Konfirmasi password untuk melanjutkan.</p>
            <form onSubmit={submit} className="space-y-4">
                <input type="password" value={data.password} onChange={(e) => setData('password', e.target.value)} placeholder="Password" className="block w-full rounded-lg border-slate-300 text-sm px-3 py-2.5 border focus:border-indigo-500 focus:ring-indigo-500" autoFocus />
                {errors.password && <p className="text-xs text-rose-600">{errors.password}</p>}
                <button type="submit" disabled={processing} className="w-full py-2.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 disabled:opacity-50 transition">
                    Konfirmasi
                </button>
            </form>
        </GuestLayout>
    );
}
