import { Link, router } from '@inertiajs/react';

export default function Placeholder({ title, auth, children, data }) {
    const user = auth?.user;
    return (
        <div style={{ minHeight: '100vh', background: '#f1f5f9', padding: 24, fontFamily: 'sans-serif' }}>
            <div style={{ maxWidth: 1100, margin: '0 auto' }}>
                <div
                    style={{
                        background: '#fef3c7',
                        border: '1px solid #f59e0b',
                        borderRadius: 8,
                        padding: '12px 16px',
                        marginBottom: 16,
                    }}
                >
                    <strong>Halaman sementara.</strong> Frontend asli (resources/js) hilang dan belum
                    dipulihkan — backend sudah jalan, tampilan ini hanya placeholder agar aplikasi bisa dibuka.
                </div>

                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 }}>
                    <h1 style={{ fontSize: 22, fontWeight: 700 }}>{title}</h1>
                    <div style={{ display: 'flex', gap: 12, alignItems: 'center' }}>
                        {user && <span style={{ fontSize: 14 }}>{user.name} ({user.email})</span>}
                        <Link href={route('dashboard')} style={{ color: '#4f46e5' }}>
                            Dashboard
                        </Link>
                        {user && (
                            <button
                                onClick={() => router.post(route('logout'))}
                                style={{ color: '#dc2626', background: 'none', border: 'none', cursor: 'pointer' }}
                            >
                                Logout
                            </button>
                        )}
                    </div>
                </div>

                {children}

                <details style={{ marginTop: 16 }}>
                    <summary style={{ cursor: 'pointer', color: '#475569' }}>Lihat data (props)</summary>
                    <pre
                        style={{
                            background: '#0f172a',
                            color: '#e2e8f0',
                            padding: 16,
                            borderRadius: 8,
                            overflow: 'auto',
                            fontSize: 12,
                        }}
                    >
                        {JSON.stringify(data ?? {}, null, 2)}
                    </pre>
                </details>
            </div>
        </div>
    );
}
