import { Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Badge, Card, CardHeader, Table, tdClass } from '../../Components/UI';

export default function Index({ auth, supervisions = [] }) {
    return (
        <AuthenticatedLayout title="Jadwal Supervisi">
            <Card>
                <CardHeader
                    title={`Jadwal (${supervisions.length})`}
                    action={<Link href={route('supervisions.create')} className="text-sm px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">+ Buat Jadwal</Link>}
                />
                <Table
                    columns={['Tanggal', 'Guru', 'Mapel', 'Kelas', 'Supervisor', 'Status', 'Aksi']}
                    rows={supervisions}
                    renderRow={(s) => (
                        <tr key={s.id}>
                            <td className={tdClass()}>{s.schedule_date ?? '-'}</td>
                            <td className={tdClass('font-medium text-slate-800')}>{s.teacher?.name ?? '-'}</td>
                            <td className={tdClass()}>{s.subject?.name ?? '-'}</td>
                            <td className={tdClass()}>{s.class_name ?? '-'}</td>
                            <td className={tdClass()}>{s.supervisor?.name ?? '-'}</td>
                            <td className={tdClass()}><Badge value={s.status} /></td>
                            <td className={tdClass()}>
                                <button onClick={() => confirm('Hapus jadwal ini?') && router.delete(route('supervisions.destroy', s.id))} className="text-rose-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    )}
                />
            </Card>
        </AuthenticatedLayout>
    );
}
