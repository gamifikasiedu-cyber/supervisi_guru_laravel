import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Badge, Card, CardHeader, StatCard, Table, tdClass } from '../../Components/UI';

export default function Index({ auth, stats = {}, pending_documents = [], my_observations = [], supervisions = [] }) {
    return (
        <AuthenticatedLayout title="Dashboard Supervisor">
            <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
                <StatCard label="Perlu Review" value={stats.dokumen_pending} icon="⏳" theme="amber" />
                <StatCard label="Disetujui" value={stats.dokumen_approved} icon="✅" theme="emerald" />
                <StatCard label="Observasi Saya" value={stats.total_observasi} icon="🔍" theme="sky" />
                <StatCard label="Supervisi Aktif" value={stats.supervisi_aktif} icon="📅" theme="violet" />
                <StatCard label="Total Direview" value={stats.total_dokumen_direview} icon="📑" theme="indigo" />
            </div>

            <div className="grid grid-cols-1 xl:grid-cols-2 gap-4 mt-4">
                <Card>
                    <CardHeader
                        title="Dokumen Menunggu Review"
                        subtitle="Perangkat ajar guru"
                        action={<Link href={route('documents.all')} className="text-sm text-indigo-600 hover:underline">Review →</Link>}
                    />
                    <Table
                        columns={['Guru', 'Judul', 'Mapel']}
                        rows={pending_documents.slice(0, 6)}
                        renderRow={(d) => (
                            <tr key={d.id}>
                                <td className={tdClass('font-medium text-slate-800')}>{d.user?.name ?? '-'}</td>
                                <td className={tdClass()}>{d.title}</td>
                                <td className={tdClass()}>{d.subject?.name ?? '-'}</td>
                            </tr>
                        )}
                    />
                </Card>
                <Card>
                    <CardHeader
                        title="Observasi Terakhir Saya"
                        action={<Link href={route('instruments.index')} className="text-sm text-indigo-600 hover:underline">Instrumen →</Link>}
                    />
                    <Table
                        columns={['Guru', 'Tanggal', 'Skor']}
                        rows={my_observations}
                        renderRow={(o) => (
                            <tr key={o.id}>
                                <td className={tdClass('font-medium text-slate-800')}>{o.teacher?.name ?? '-'}</td>
                                <td className={tdClass()}>{o.observation_date ?? '-'}</td>
                                <td className={tdClass()}>{o.score ?? o.total_score ?? '-'}</td>
                            </tr>
                        )}
                    />
                </Card>
            </div>

            <Card className="mt-4">
                <CardHeader
                    title="Jadwal Supervisi Aktif"
                    action={<Link href={route('supervisions.index')} className="text-sm text-indigo-600 hover:underline">Semua →</Link>}
                />
                <Table
                    columns={['Tanggal', 'Guru', 'Mapel', 'Kelas', 'Status']}
                    rows={supervisions}
                    renderRow={(s) => (
                        <tr key={s.id}>
                            <td className={tdClass()}>{s.schedule_date ?? '-'}</td>
                            <td className={tdClass('font-medium text-slate-800')}>{s.teacher?.name ?? '-'}</td>
                            <td className={tdClass()}>{s.subject?.name ?? '-'}</td>
                            <td className={tdClass()}>{s.class_name ?? '-'}</td>
                            <td className={tdClass()}><Badge value={s.status} /></td>
                        </tr>
                    )}
                />
            </Card>
        </AuthenticatedLayout>
    );
}
