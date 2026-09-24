import { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';

function hasRole(user, ...roles) {
    const list = user?.role_list ?? (user?.role ? [user.role] : []);
    return roles.some((r) => list.includes(r));
}

function NavItem({ href, active, icon, children, onClick }) {
    return (
        <Link
            href={href}
            onClick={onClick}
            className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition ${
                active
                    ? 'bg-indigo-600 text-white shadow'
                    : 'text-slate-300 hover:bg-slate-700/60 hover:text-white'
            }`}
        >
            <span className="w-5 text-center">{icon}</span>
            {children}
        </Link>
    );
}

function NavGroup({ label, children }) {
    return (
        <div className="mt-6 first:mt-0">
            <p className="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-500">{label}</p>
            <div className="space-y-1">{children}</div>
        </div>
    );
}

export default function AuthenticatedLayout({ title, children }) {
    const { auth, settings, active_period, jadwal_notif, flash } = usePage().props;
    const user = auth?.user;
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [showNotif, setShowNotif] = useState(false);
    const [showFlash, setShowFlash] = useState(true);

    const isActive = (name, params) => {
        try {
            return route().current(name, params);
        } catch {
            return false;
        }
    };

    const guruOnly = hasRole(user, 'guru') && !hasRole(user, 'admin', 'supervisor', 'kepala_sekolah', 'pengawas');
    const canReview = hasRole(user, 'admin', 'supervisor', 'kepala_sekolah', 'pengawas');
    const isAdmin = hasRole(user, 'admin');
    const isSupervisorArea = hasRole(user, 'admin', 'supervisor', 'kepala_sekolah', 'pengawas');

    const notifItems = jadwal_notif?.items ?? [];
    const school = settings?.school_name || 'Supervisi Guru';

    const closeSidebar = () => setSidebarOpen(false);

    const sidebar = (
        <div className="flex flex-col h-full">
            <div className="px-4 py-5 flex items-center gap-3 border-b border-slate-700/60">
                {settings?.logo_url ? (
                    <img src={settings.logo_url} alt="Logo" className="w-10 h-10 rounded-lg bg-white p-0.5 object-contain shrink-0" />
                ) : (
                    <div className="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold shrink-0">S</div>
                )}
                <div className="min-w-0">
                    <p className="text-white text-sm font-bold leading-tight truncate">{school}</p>
                    <p className="text-slate-400 text-xs">Supervisi Akademik</p>
                </div>
            </div>

            <nav className="flex-1 overflow-y-auto p-3">
                <NavGroup label="Utama">
                    <NavItem href={route('dashboard')} active={isActive('dashboard')} icon="🏠" onClick={closeSidebar}>Dashboard</NavItem>
                    <NavItem href={route('supervisions.index')} active={isActive('supervisions.*')} icon="📅" onClick={closeSidebar}>Jadwal Supervisi</NavItem>
                </NavGroup>

                {!guruOnly && (
                    <NavGroup label="Dokumen">
                        <NavItem href={route('documents.index')} active={isActive('documents.index')} icon="📄" onClick={closeSidebar}>Dokumen Saya</NavItem>
                        {canReview && (
                            <NavItem href={route('documents.all')} active={isActive('documents.all')} icon="📑" onClick={closeSidebar}>Review Dokumen</NavItem>
                        )}
                    </NavGroup>
                )}
                {guruOnly && (
                    <NavGroup label="Dokumen">
                        <NavItem href={route('documents.index')} active={isActive('documents.*')} icon="📄" onClick={closeSidebar}>Dokumen Saya</NavItem>
                    </NavGroup>
                )}

                {isSupervisorArea && (
                    <NavGroup label="Supervisi">
                        <NavItem href={route('instruments.index')} active={isActive('instruments.*')} icon="📝" onClick={closeSidebar}>Instrumen</NavItem>
                        <NavItem href={route('instruments.rekapitulasi')} active={isActive('instruments.rekapitulasi')} icon="📊" onClick={closeSidebar}>Rekapitulasi</NavItem>
                        <NavItem href={route('pre-observations.index')} active={isActive('pre-observations.*')} icon="🔍" onClick={closeSidebar}>Pra Observasi</NavItem>
                        <NavItem href={route('konferensi.index')} active={isActive('konferensi.*')} icon="💬" onClick={closeSidebar}>Konferensi</NavItem>
                        <NavItem href={route('pasca-supervisi.index')} active={isActive('pasca-supervisi.*')} icon="📋" onClick={closeSidebar}>Pasca Supervisi</NavItem>
                    </NavGroup>
                )}
                {guruOnly && (
                    <NavGroup label="Supervisi">
                        <NavItem href={route('pasca-supervisi.index')} active={isActive('pasca-supervisi.*')} icon="📋" onClick={closeSidebar}>Monitoring & Rekap</NavItem>
                    </NavGroup>
                )}

                {isAdmin && (
                    <NavGroup label="Master">
                        <NavItem href={route('users.index')} active={isActive('users.*')} icon="👥" onClick={closeSidebar}>Pengguna</NavItem>
                        <NavItem href={route('subjects.index')} active={isActive('subjects.*')} icon="📚" onClick={closeSidebar}>Mata Pelajaran</NavItem>
                        <NavItem href={route('settings.index')} active={isActive('settings.*')} icon="⚙️" onClick={closeSidebar}>Pengaturan</NavItem>
                    </NavGroup>
                )}
            </nav>

            <div className="p-3 border-t border-slate-700/60">
                <div className="flex items-center gap-3 px-2 py-2">
                    <div className="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                        {(user?.name ?? '?').charAt(0).toUpperCase()}
                    </div>
                    <div className="min-w-0 flex-1">
                        <p className="text-white text-sm font-medium truncate">{user?.name}</p>
                        <p className="text-slate-400 text-xs truncate">{(user?.role_labels ?? []).join(', ')}</p>
                    </div>
                </div>
                <div className="flex gap-2 mt-1">
                    <Link href={route('profile.edit')} className="flex-1 text-center text-xs px-2 py-1.5 rounded-lg bg-slate-700/60 text-slate-200 hover:bg-slate-700">Profil</Link>
                    <button
                        onClick={() => router.post(route('logout'))}
                        className="flex-1 text-xs px-2 py-1.5 rounded-lg bg-rose-600/20 text-rose-300 hover:bg-rose-600/30"
                    >
                        Keluar
                    </button>
                </div>
            </div>
        </div>
    );

    return (
        <div className="min-h-screen bg-slate-100">
            <Head title={title} />

            {/* Sidebar desktop */}
            <aside className="hidden lg:flex fixed inset-y-0 left-0 w-64 bg-slate-800 flex-col z-30">{sidebar}</aside>

            {/* Sidebar mobile */}
            {sidebarOpen && (
                <div className="fixed inset-0 z-40 lg:hidden">
                    <div className="absolute inset-0 bg-black/50" onClick={closeSidebar} />
                    <aside className="absolute inset-y-0 left-0 w-64 bg-slate-800 flex-col flex">{sidebar}</aside>
                </div>
            )}

            <div className="lg:pl-64 flex flex-col min-h-screen">
                {/* Topbar */}
                <header className="sticky top-0 z-20 bg-white border-b border-slate-200 px-4 py-3 flex items-center gap-3">
                    <button onClick={() => setSidebarOpen(true)} className="lg:hidden text-slate-600 text-xl px-1">☰</button>
                    <h1 className="font-bold text-slate-800 truncate flex-1">{title}</h1>

                    {active_period && (
                        <span className="hidden sm:inline-block text-xs px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 font-medium whitespace-nowrap">
                            {active_period.tahun_ajaran} · {active_period.semester}
                        </span>
                    )}

                    {/* Notifikasi jadwal */}
                    <div className="relative">
                        <button
                            onClick={() => setShowNotif((v) => !v)}
                            className="relative text-xl px-2 py-1 rounded-lg hover:bg-slate-100"
                            title="Jadwal mendatang"
                        >
                            🔔
                            {notifItems.length > 0 && (
                                <span className="absolute -top-0.5 -right-0.5 bg-rose-500 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1">
                                    {notifItems.length}
                                </span>
                            )}
                        </button>
                        {showNotif && (
                            <div className="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
                                <p className="px-4 py-2.5 text-sm font-semibold border-b border-slate-100">Jadwal Mendatang</p>
                                <div className="max-h-72 overflow-y-auto">
                                    {notifItems.length === 0 && (
                                        <p className="px-4 py-4 text-sm text-slate-400">Tidak ada jadwal mendatang.</p>
                                    )}
                                    {notifItems.map((n) => (
                                        <div key={n.id} className="px-4 py-2.5 border-b border-slate-50 text-sm">
                                            <p className="font-medium text-slate-700">{n.guru} <span className="text-slate-400 font-normal">· {n.kelas}</span></p>
                                            <p className="text-xs text-slate-500">{n.tanggal} · {n.mapel} · {n.supervisor}</p>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </header>

                {/* Flash */}
                {showFlash && (flash?.success || flash?.error) && (
                    <div className={`mx-4 mt-4 px-4 py-3 rounded-xl text-sm flex justify-between items-center ${flash?.success ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200'}`}>
                        <span>{flash?.success ?? flash?.error}</span>
                        <button onClick={() => setShowFlash(false)} className="ml-4 font-bold">✕</button>
                    </div>
                )}

                {/* Content */}
                <main className="flex-1 p-4 md:p-6 w-full max-w-7xl mx-auto">{children}</main>

                <footer className="px-6 py-4 text-center text-xs text-slate-400">
                    {school}{active_period ? ` · ${active_period.tahun_ajaran} ${active_period.semester}` : ''}
                </footer>
            </div>
        </div>
    );
}
