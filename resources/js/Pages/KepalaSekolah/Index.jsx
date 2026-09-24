import { Bar, BarChart, CartesianGrid, ResponsiveContainer, Tooltip, XAxis, YAxis } from 'recharts';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Badge, Card, CardHeader, StatCard, Table, tdClass } from '../../Components/UI';

function ScoreChart({ data = [] }) {
    if (!data.length) return <p className="px-5 py-6 text-sm text-slate-400 text-center">Belum ada nilai.</p>;
    return (
        <div className="px-3 py-2" style={{ height: 280 }}>
            <ResponsiveContainer width="100%" height="100%">
                <BarChart data={data} margin={{ top: 8, right: 8, left: -16, bottom: 40 }}>
                    <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" />
                    <XAxis dataKey="nama" tick={{ fontSize: 11 }} interval={0} angle={-20} textAnchor="end" />
                    <YAxis domain={[0, 100]} tick={{ fontSize: 11 }} />
                    <Tooltip />
                    <Bar dataKey="nilai" fill="#6366f1" radius={[6, 6, 0, 0]} name="Rata-rata" />
                </BarChart>
            </ResponsiveContainer>
        </div>
    );
}

export default function Index({
    auth,
    stats = {},
    pending_observations = [],
    recent_observations = [],
    guru_recap = [],
    chart_data = [],
}) {
    return (
        <AuthenticatedLayout title="Dashboard Kepala Sekolah">
            <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                <StatCard label="Total Guru" value={stats.total_guru} icon="🧑‍🏫" theme="indigo" />
                <StatCard label="Total Supervisi" value={stats.total_supervisi} icon="📅" theme="sky" />
                <StatCard label="Menunggu Persetujuan" value={stats.approval_pending} icon="⏳" theme="amber" hint={`${stats.approval_approved ?? 0} disetujui`} />
                <StatCard label="Rata-rata Nilai" value={stats.rata_rata_nilai} icon="⭐" theme="emerald" />
                <StatCard label="Total Observasi" value={stats.total_observasi} icon="🔍" theme="violet" />
                <Card className="p-5 flex gap-2">
                    <a href={route('dashboard.export-excel')} className="flex-1 text-center text-sm px-3 py-2.5 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700">⬇ Excel</a>
                    <a href={route('dashboard.export-pdf')} className="flex-1 text-center text-sm px-3 py-2.5 rounded-lg bg-rose-600 text-white font-medium hover:bg-rose-700">⬇ PDF</a>
                </Card>
            </div>

            <Card className="mt-4">
                <CardHeader title="Grafik Nilai per Guru" subtitle="Rata-rata observasi yang disetujui" />
                <ScoreChart data={chart_data} />
            </Card>

            <div className="grid grid-cols-1 xl:grid-cols-2 gap-4 mt-4">
                <Card>
                    <CardHeader title="Menunggu Persetujuan" subtitle="Observasi berstatus submitted" />
                    <Table
                        columns={['Guru', 'Mapel', 'Tanggal', 'Total']}
                        rows={pending_observations}
                        renderRow={(o) => (
                            <tr key={o.id}>
                                <td className={tdClass('font-medium text-slate-800')}>{o.teacher?.name ?? '-'}</td>
                                <td className={tdClass()}>{o.subject?.name ?? '-'}</td>
                                <td className={tdClass()}>{o.observation_date ?? '-'}</td>
                                <td className={tdClass()}>{o.total_score ?? '-'}</td>
                            </tr>
                        )}
                    />
                </Card>
                <Card>
                    <CardHeader title="Rekap per Guru" subtitle="Rata-rata nilai disetujui" />
                    <Table
                        columns={['Guru', 'Observasi', 'Rata-rata']}
                        rows={guru_recap}
                        renderRow={(g) => (
                            <tr key={g.id}>
                                <td className={tdClass('font-medium text-slate-800')}>{g.name}</td>
                                <td className={tdClass()}>{g.total_observasi}</td>
                                <td className={tdClass('font-semibold')}>{g.rata_rata_nilai}</td>
                            </tr>
                        )}
                    />
                </Card>
            </div>

            <Card className="mt-4">
                <CardHeader title="Observasi Terbaru" />
                <Table
                    columns={['Tanggal', 'Guru', 'Supervisor', 'Mapel', 'Status']}
                    rows={(recent_observations ?? []).slice(0, 8)}
                    renderRow={(o) => (
                        <tr key={o.id}>
                            <td className={tdClass()}>{o.observation_date ?? '-'}</td>
                            <td className={tdClass('font-medium text-slate-800')}>{o.teacher?.name ?? '-'}</td>
                            <td className={tdClass()}>{o.supervisor?.name ?? '-'}</td>
                            <td className={tdClass()}>{o.subject?.name ?? '-'}</td>
                            <td className={tdClass()}><Badge value={o.status} /></td>
                        </tr>
                    )}
                />
            </Card>
        </AuthenticatedLayout>
    );
}
