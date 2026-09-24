import { Head, Link, usePage } from '@inertiajs/react';

export default function GuestLayout({ title, children }) {
    const { settings } = usePage().props;
    const school = settings?.school_name || 'Sistem Informasi Supervisi Guru';

    return (
        <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-600 via-indigo-700 to-slate-900 p-4">
            <Head title={title} />
            <div className="w-full max-w-md">
                <div className="text-center mb-6">
                    {settings?.logo_url && (
                        <img src={settings.logo_url} alt="Logo" className="w-16 h-16 mx-auto mb-3 rounded-xl bg-white p-1 object-contain" />
                    )}
                    <h1 className="text-white text-xl font-bold leading-snug">{school}</h1>
                    <p className="text-indigo-200 text-sm mt-1">Supervisi Akademik Guru</p>
                </div>
                <div className="bg-white rounded-2xl shadow-2xl p-8">
                    {children}
                </div>
                <p className="text-center text-indigo-200/70 text-xs mt-6">
                    <Link href={route('login')} className="hover:text-white">Masuk</Link>
                    {' · '}
                    <Link href={route('register')} className="hover:text-white">Daftar</Link>
                </p>
            </div>
        </div>
    );
}
