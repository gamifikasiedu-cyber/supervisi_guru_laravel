import { useState } from 'react';
import { useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Badge, Card, CardHeader, Table, tdClass } from '../../Components/UI';

const input = 'mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border focus:border-indigo-500 focus:ring-indigo-500';

function ReviewForm({ doc, onDone }) {
    const { data, setData, post, processing, errors } = useForm({
        status: doc.status ?? 'pending', review_notes: doc.review_notes ?? '',
    });
    return (
        <form
            onSubmit={(e) => { e.preventDefault(); post(route('documents.review', doc.id), { onSuccess: onDone }); }}
            className="px-5 py-4 bg-slate-50 border-t border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-3"
        >
            <div>
                <label className="text-sm font-medium">Status</label>
                <select value={data.status} onChange={(e) => setData('status', e.target.value)} className={input}>
                    <option value="pending">Menunggu</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
                {errors.status && <p className="text-xs text-rose-600 mt-1">{errors.status}</p>}
            </div>
            <div className="md:col-span-2">
                <label className="text-sm font-medium">Catatan Review</label>
                <div className="flex gap-2">
                    <input value={data.review_notes} onChange={(e) => setData('review_notes', e.target.value)} className={input} placeholder="Catatan untuk guru…" />
                    <button disabled={processing} className="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 shrink-0">Simpan</button>
                </div>
            </div>
        </form>
    );
}

export default function Documents({ auth, documents = [] }) {
    const [openId, setOpenId] = useState(null);
    return (
        <AuthenticatedLayout title="Review Dokumen">
            <Card>
                <CardHeader title={`Semua Dokumen (${documents.length})`} subtitle="Klik Review untuk memberi penilaian" />
                <Table
                    columns={['Guru', 'Judul', 'Mapel', 'Status', 'Aksi']}
                    rows={documents}
                    renderRow={(d) => (
                        <>
                            <tr key={d.id}>
                                <td className={tdClass('font-medium text-slate-800')}>{d.user?.name ?? '-'}</td>
                                <td className={tdClass()}>{d.title}</td>
                                <td className={tdClass()}>{d.subject?.name ?? '-'}</td>
                                <td className={tdClass()}><Badge value={d.status} /></td>
                                <td className={tdClass('whitespace-nowrap')}>
                                    <a href={route('documents.download', d.id)} className="text-indigo-600 hover:underline mr-3">Unduh</a>
                                    <button onClick={() => setOpenId(openId === d.id ? null : d.id)} className="text-emerald-600 hover:underline">Review</button>
                                </td>
                            </tr>
                            {openId === d.id && (
                                <tr key={`${d.id}-form`}>
                                    <td colSpan={5} className="p-0">
                                        <ReviewForm doc={d} onDone={() => setOpenId(null)} />
                                    </td>
                                </tr>
                            )}
                        </>
                    )}
                />
            </Card>
        </AuthenticatedLayout>
    );
}
