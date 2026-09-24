import { Link, useForm } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

export default function VerifyEmail({ status }) {
    const { post, processing } = useForm({});
    const submit = (e) => {
        e.preventDefault();
        post(route('verification.send'));
    };
    return (
        <GuestLayout title="Verifikasi Email">
            <h2 className="text-lg font-bold text-slate-800 mb-2">Verifikasi email Anda</h2>
            <p className="text-sm text-slate-500 mb-5">Klik tombol di bawah untuk menerima link verifikasi baru.</p>
            {status === 'verification-link-sent' && (
                <div className="mb-4 text-sm text-emerald-600 bg-emerald-50 rounded-lg px-3 py-2">Link verifikasi baru telah dikirim.</div>
            )}
            <div className="flex items-center justify-between">
                <button onClick={submit} disabled={processing} className="px-4 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 disabled:opacity-50 transition">
                    Kirim Ulang
                </button>
                <Link href={route('logout')} method="post" as="button" className="text-sm text-rose-600 hover:underline">Keluar</Link>
            </div>
        </GuestLayout>
    );
}
