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
                    <Bar dataKey="nilai" fill="#0ea5e9" radius={[6, 6, 0, 0]} name="Rata-rata" />
                </BarChart>
            </ResponsiveContainer>
        </div>
    );
}

export default function Index({
    auth,
    stats = {},
    recent_observations = [],
    approved_observations = [],
    guru_recap = [],
    chart_data = [],
}) {
    return (
        <AuthenticatedLayout title="Dashboard Pengawas">
            <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                <StatCard label="Total Guru" value={stats.total_guru} icon="🧑‍🏫" theme="indigo" />
                <StatCard label="Supervisi Selesai" value={stats.total_supervisi_selesai} icon="✅" theme="emerald" />
                <StatCard label="Observasi Disetujui" value={stats.observasi_approved} icon="📝" theme="sky" hint={`${stats.total_observasi ?? 0} total observasi`} />
                <StatCard label="Rata-rata Sekolah" value={stats.rata_rata_sekolah} icon="⭐" theme="amber" />
                <StatCard label="Kepatuhan Dokumen" value={`${stats.kepatuhan_dokumen ?? 0}%`} icon="📑" theme="violet" />
                <Card className="p-5 flex gap-2">
                    <a href={route('dashboard.export-excel')} className="flex-1 text-center text-sm px-3 py-2.5 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700">⬇ Excel</a>
                    <a href={route('dashboard.export-pdf')} className="flex-1 text-center text-sm px-3 py-2.5 rounded-lg bg-rose-600 text-white font-medium hover:bg-rose-700">⬇ PDF</a>
                </Card>
            </div>

            <Card className="mt-4">
                <CardHeader title="Mutu Sekolah per Guru" subtitle="Audit kepatuhan standar" />
                <ScoreChart data={chart_data} />
            </Card>

            <Card className="mt-4">
                <CardHeader title="Rekap Guru" subtitle="Status ketercapaian standar" />
                <Table
                    columns={['Guru', 'NIP', 'Observasi', 'Rata-rata', 'Status']}
                    rows={guru_recap}
                    renderRow={(g) => (
                        <tr key={g.id}>
                            <td className={tdClass('font-medium text-slate-800')}>{g.name}</td>
                            <td className={tdClass()}>{g.nip ?? '-'}</td>
                            <td className={tdClass()}>{g.total_observasi}</td>
                            <td className={tdClass('font-semibold')}>{g.rata_rata_nilai}</td>
                            <td className={tdClass()}>
                                <span className={`inline-block px-2.5 py-0.5 rounded-full text-xs font-medium ${g.status === 'Sesuai Standar' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600'}`}>
                                    {g.status}
                                </span>
                            </td>
                        </tr>
                    )}
                />
            </Card>

            <div className="grid grid-cols-1 xl:grid-cols-2 gap-4 mt-4">
                <Card>
                    <CardHeader title="Observasi Terbaru" />
                    <Table
                        columns={['Tanggal', 'Guru', 'Status']}
                        rows={(recent_observations ?? []).slice(0, 6)}
                        renderRow={(o) => (
                            <tr key={o.id}>
                                <td className={tdClass()}>{o.observation_date ?? '-'}</td>
                                <td className={tdClass('font-medium text-slate-800')}>{o.teacher?.name ?? '-'}</td>
                                <td className={tdClass()}><Badge value={o.status} /></td>
                            </tr>
                        )}
                    />
                </Card>
                <Card>
                    <CardHeader title="Observasi Disetujui" subtitle="Bukti mutu sekolah" />
                    <Table
                        columns={['Tanggal', 'Guru', 'Total']}
                        rows={(approved_observations ?? []).slice(0, 6)}
                        renderRow={(o) => (
                            <tr key={o.id}>
                                <td className={tdClass()}>{o.observation_date ?? '-'}</td>
                                <td className={tdClass('font-medium text-slate-800')}>{o.teacher?.name ?? '-'}</td>
                                <td className={tdClass()}>{o.total_score ?? '-'}</td>
                            </tr>
                        )}
                    />
                </Card>
            </div>
        </AuthenticatedLayout>
    );
}
