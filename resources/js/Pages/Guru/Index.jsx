import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Badge, Card, CardHeader, StatCard, Table, tdClass } from '../../Components/UI';

export default function Index({ auth, stats = {}, documents = [], supervisions = [] }) {
    return (
        <AuthenticatedLayout title="Dashboard Guru">
            <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <StatCard label="Dokumen Saya" value={stats.total_dokumen} icon="📄" theme="indigo" />
                <StatCard label="Menunggu Review" value={stats.dokumen_pending} icon="⏳" theme="amber" />
                <StatCard label="Disetujui" value={stats.dokumen_approved} icon="✅" theme="emerald" />
                <StatCard label="Jadwal Supervisi" value={stats.total_supervisi} icon="📅" theme="sky" />
            </div>

            <div className="grid grid-cols-1 xl:grid-cols-2 gap-4 mt-4">
                <Card>
                    <CardHeader
                        title="Dokumen Perangkat Ajar"
                        subtitle="Status review supervisor"
                        action={<Link href={route('documents.index')} className="text-sm text-indigo-600 hover:underline">Kelola →</Link>}
                    />
                    <Table
                        columns={['Judul', 'Mapel', 'Status']}
                        rows={documents.slice(0, 6)}
                        renderRow={(d) => (
                            <tr key={d.id}>
                                <td className={tdClass('font-medium text-slate-800')}>{d.title}</td>
                                <td className={tdClass()}>{d.subject?.name ?? '-'}</td>
                                <td className={tdClass()}><Badge value={d.status} /></td>
                            </tr>
                        )}
                    />
                </Card>
                <Card>
                    <CardHeader
                        title="Supervisi Mendatang"
                        subtitle="Jadwal observasi Anda"
                        action={<Link href={route('supervisions.index')} className="text-sm text-indigo-600 hover:underline">Lihat →</Link>}
                    />
                    <Table
                        columns={['Tanggal', 'Mapel', 'Supervisor', 'Status']}
                        rows={supervisions}
                        renderRow={(s) => (
                            <tr key={s.id}>
                                <td className={tdClass()}>{s.schedule_date ?? '-'}</td>
                                <td className={tdClass()}>{s.subject?.name ?? '-'}</td>
                                <td className={tdClass()}>{s.supervisor?.name ?? '-'}</td>
                                <td className={tdClass()}><Badge value={s.status} /></td>
                            </tr>
                        )}
                    />
                </Card>
            </div>
        </AuthenticatedLayout>
    );
}
