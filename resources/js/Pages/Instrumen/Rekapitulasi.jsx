import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Card, CardHeader, Table, tdClass } from '../../Components/UI';

function ScoreTable({ columns, rows, renderRow, emptyText }) {
    return <Table columns={columns} rows={rows} renderRow={renderRow} emptyText={emptyText} />;
}

export default function Rekapitulasi({
    auth, komponen = [], dimensi = [], matriks = [], matriksDimensi = [], perGuru = [], jumlahRecord = 0,
}) {
    const guruIds = perGuru.map((g) => String(g.teacher_id));

    const renderMatrix = (row) => (
        <tr key={row.nama}>
            <td className={tdClass('font-medium text-slate-800')}>{row.nama}</td>
            {guruIds.map((gid) => (
                <td key={gid} className={tdClass('text-center')}>{row.sel?.[gid] ?? row.sel?.[Number(gid)] ?? '-'}</td>
            ))}
            <td className={tdClass('text-center font-semibold')}>{row.rata}</td>
            <td className={tdClass()}>{row.predikat}</td>
        </tr>
    );

    return (
        <AuthenticatedLayout title="Rekapitulasi Instrumen">
            <Card>
                <CardHeader
                    title={`Rekapitulasi (${jumlahRecord} record)`}
                    action={<Link href={route('instruments.index')} className="text-sm text-indigo-600 hover:underline">← Instrumen</Link>}
                />
                <div className="p-5 space-y-6">
                    <section>
                        <h3 className="font-semibold text-slate-800 mb-2">Nilai per Guru</h3>
                        <ScoreTable
                            columns={['Guru', 'NIP', 'Mapel', 'Kelas', 'Observasi', 'Nilai', 'Predikat']}
                            rows={perGuru}
                            renderRow={(g) => (
                                <tr key={g.teacher_id}>
                                    <td className={tdClass('font-medium text-slate-800')}>{g.nama}</td>
                                    <td className={tdClass()}>{g.nip ?? '-'}</td>
                                    <td className={tdClass()}>{g.mapel}</td>
                                    <td className={tdClass()}>{g.kelas}</td>
                                    <td className={tdClass('text-center')}>{g.observasi}</td>
                                    <td className={tdClass('font-semibold')}>{g.nilai}</td>
                                    <td className={tdClass()}>{g.predikat}</td>
                                </tr>
                            )}
                        />
                    </section>
                    <section>
                        <h3 className="font-semibold text-slate-800 mb-2">Nilai per Tahap / Komponen</h3>
                        <ScoreTable
                            columns={['Komponen', 'Jumlah', 'Maks', 'Diperoleh', 'Nilai', 'Predikat']}
                            rows={komponen}
                            renderRow={(r) => (
                                <tr key={r.nama}>
                                    <td className={tdClass('font-medium text-slate-800')}>{r.nama}</td>
                                    <td className={tdClass()}>{r.jumlah}</td>
                                    <td className={tdClass()}>{r.maks}</td>
                                    <td className={tdClass()}>{r.diperoleh}</td>
                                    <td className={tdClass('font-semibold')}>{r.nilai}</td>
                                    <td className={tdClass()}>{r.predikat}</td>
                                </tr>
                            )}
                        />
                    </section>
                    <section>
                        <h3 className="font-semibold text-slate-800 mb-2">Nilai per Dimensi</h3>
                        <ScoreTable
                            columns={['Dimensi', 'Jumlah', 'Maks', 'Diperoleh', 'Nilai', 'Predikat']}
                            rows={dimensi}
                            renderRow={(r) => (
                                <tr key={r.nama}>
                                    <td className={tdClass('font-medium text-slate-800')}>{r.nama}</td>
                                    <td className={tdClass()}>{r.jumlah}</td>
                                    <td className={tdClass()}>{r.maks}</td>
                                    <td className={tdClass()}>{r.diperoleh}</td>
                                    <td className={tdClass('font-semibold')}>{r.nilai}</td>
                                    <td className={tdClass()}>{r.predikat}</td>
                                </tr>
                            )}
                        />
                    </section>
                    {matriks.length > 0 && (
                        <section>
                            <h3 className="font-semibold text-slate-800 mb-2">Matriks Guru × Tahap</h3>
                            <ScoreTable
                                columns={['Tahap', ...perGuru.map((g) => g.nama), 'Rata', 'Predikat']}
                                rows={matriks}
                                renderRow={renderMatrix}
                            />
                        </section>
                    )}
                    {matriksDimensi.length > 0 && (
                        <section>
                            <h3 className="font-semibold text-slate-800 mb-2">Matriks Guru × Dimensi</h3>
                            <ScoreTable
                                columns={['Dimensi', ...perGuru.map((g) => g.nama), 'Rata', 'Predikat']}
                                rows={matriksDimensi}
                                renderRow={renderMatrix}
                            />
                        </section>
                    )}
                </div>
            </Card>
        </AuthenticatedLayout>
    );
}
