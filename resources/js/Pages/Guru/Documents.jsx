import { router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Badge, Card, CardHeader, Table, tdClass } from '../../Components/UI';

const input = 'mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border focus:border-indigo-500 focus:ring-indigo-500';

export default function Documents({ auth, documents = [], subjects = [], document_types = [] }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        title: '', description: '', document_type: '', subject_id: '', file: null,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('documents.store'), { onSuccess: () => reset() });
    };

    return (
        <AuthenticatedLayout title="Dokumen Perangkat Ajar Saya">
            <Card>
                <CardHeader title="Unggah Dokumen" subtitle="1 paket RAR/ZIP per unggahan, maksimal 100MB" />
                <form onSubmit={submit} className="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label className="text-sm font-medium">Judul</label>
                        <input value={data.title} onChange={(e) => setData('title', e.target.value)} className={input} />
                        {errors.title && <p className="text-xs text-rose-600 mt-1">{errors.title}</p>}
                    </div>
                    <div>
                        <label className="text-sm font-medium">Jenis Dokumen</label>
                        <select value={data.document_type} onChange={(e) => setData('document_type', e.target.value)} className={input}>
                            <option value="">-- Pilih jenis --</option>
                            {document_types.map((t) => <option key={t} value={t}>{t}</option>)}
                        </select>
                        {errors.document_type && <p className="text-xs text-rose-600 mt-1">{errors.document_type}</p>}
                    </div>
                    <div>
                        <label className="text-sm font-medium">Mata Pelajaran (opsional)</label>
                        <select value={data.subject_id} onChange={(e) => setData('subject_id', e.target.value)} className={input}>
                            <option value="">-- Tanpa mapel --</option>
                            {subjects.map((s) => <option key={s.id} value={s.id}>{s.name}</option>)}
                        </select>
                    </div>
                    <div>
                        <label className="text-sm font-medium">File (.rar / .zip)</label>
                        <input type="file" accept=".rar,.zip" onChange={(e) => setData('file', e.target.files[0])} className="mt-1 block w-full text-sm" />
                        {errors.file && <p className="text-xs text-rose-600 mt-1">{errors.file}</p>}
                    </div>
                    <div className="md:col-span-2">
                        <label className="text-sm font-medium">Deskripsi (opsional)</label>
                        <textarea value={data.description} onChange={(e) => setData('description', e.target.value)} className={input} rows={2} />
                    </div>
                    <div className="md:col-span-2">
                        <button disabled={processing} className="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50">
                            {processing ? 'Mengunggah…' : 'Unggah'}
                        </button>
                    </div>
                </form>
            </Card>

            <Card className="mt-4">
                <CardHeader title={`Dokumen Saya (${documents.length})`} />
                <Table
                    columns={['Judul', 'Jenis', 'Mapel', 'Ukuran', 'Status', 'Aksi']}
                    rows={documents}
                    renderRow={(d) => (
                        <tr key={d.id}>
                            <td className={tdClass('font-medium text-slate-800')}>{d.title}</td>
                            <td className={tdClass()}>{d.document_type}</td>
                            <td className={tdClass()}>{d.subject?.name ?? '-'}</td>
                            <td className={tdClass()}>{d.file_size ?? '-'}</td>
                            <td className={tdClass()}><Badge value={d.status} /></td>
                            <td className={tdClass('whitespace-nowrap')}>
                                <a href={route('documents.download', d.id)} className="text-indigo-600 hover:underline mr-3">Unduh</a>
                                <button onClick={() => confirm('Hapus dokumen ini?') && router.delete(route('documents.destroy', d.id))} className="text-rose-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    )}
                />
            </Card>
        </AuthenticatedLayout>
    );
}
