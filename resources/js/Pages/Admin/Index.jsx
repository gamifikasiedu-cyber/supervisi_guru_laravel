import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Badge, Card, CardHeader, StatCard, Table, tdClass } from '../../Components/UI';

export default function Index({ auth, stats = {}, users = [], subjects = [] }) {
    return (
        <AuthenticatedLayout title="Dashboard Admin">
            <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <StatCard label="Total Pengguna" value={stats.total_pengguna} icon="👥" theme="indigo" />
                <StatCard label="Guru" value={stats.guru} icon="🧑‍🏫" theme="emerald" />
                <StatCard label="Supervisor" value={stats.supervisor} icon="🧑‍💼" theme="sky" />
                <StatCard label="Mata Pelajaran" value={stats.total_mapel} icon="📚" theme="violet" />
                <StatCard label="Dokumen Pending" value={stats.dokumen_pending} icon="⏳" theme="amber" hint={`${stats.total_dokumen ?? 0} total dokumen`} />
                <StatCard label="Jadwal Supervisi" value={stats.total_supervisi} icon="📅" theme="sky" />
                <StatCard label="Observasi" value={stats.total_observasi} icon="🔍" theme="emerald" />
                <StatCard label="Kepsek / Pengawas" value={(stats.kepala_sekolah ?? 0) + (stats.pengawas ?? 0)} icon="🏛️" theme="rose" />
            </div>

            <div className="grid grid-cols-1 xl:grid-cols-2 gap-4 mt-4">
                <Card>
                    <CardHeader
                        title="Pengguna Terbaru"
                        subtitle="8 akun terakhir"
                        action={<Link href={route('users.index')} className="text-sm text-indigo-600 hover:underline">Kelola →</Link>}
                    />
                    <Table
                        columns={['Nama', 'Email', 'Role']}
                        rows={users}
                        renderRow={(u) => (
                            <tr key={u.id}>
                                <td className={tdClass('font-medium text-slate-800')}>{u.name}</td>
                                <td className={tdClass()}>{u.email}</td>
                                <td className={tdClass()}><Badge value={u.role} /></td>
                            </tr>
                        )}
                    />
                </Card>
                <Card>
                    <CardHeader
                        title="Mata Pelajaran"
                        subtitle={`${subjects.length} mapel terdaftar`}
                        action={<Link href={route('subjects.index')} className="text-sm text-indigo-600 hover:underline">Kelola →</Link>}
                    />
                    <Table
                        columns={['Nama', 'Kode']}
                        rows={subjects.slice(0, 8)}
                        renderRow={(s) => (
                            <tr key={s.id}>
                                <td className={tdClass('font-medium text-slate-800')}>{s.name}</td>
                                <td className={tdClass()}>{s.code ?? '-'}</td>
                            </tr>
                        )}
                    />
                </Card>
            </div>
        </AuthenticatedLayout>
    );
}
