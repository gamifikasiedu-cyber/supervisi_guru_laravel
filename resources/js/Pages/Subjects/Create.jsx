import { Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Card } from '../../Components/UI';

const input = 'mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border focus:border-indigo-500 focus:ring-indigo-500';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({ name: '', code: '' });
    return (
        <AuthenticatedLayout title="Tambah Mata Pelajaran">
            <Card>
                <form onSubmit={(e) => { e.preventDefault(); post(route('subjects.store')); }} className="p-5 space-y-4 max-w-xl">
                    <div>
                        <label className="text-sm font-medium">Nama Mata Pelajaran</label>
                        <input value={data.name} onChange={(e) => setData('name', e.target.value)} className={input} autoFocus />
                        {errors.name && <p className="text-xs text-rose-600 mt-1">{errors.name}</p>}
                    </div>
                    <div>
                        <label className="text-sm font-medium">Kode (opsional)</label>
                        <input value={data.code} onChange={(e) => setData('code', e.target.value)} className={input} />
                    </div>
                    <div className="flex gap-2">
                        <button disabled={processing} className="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50">Simpan</button>
                        <Link href={route('subjects.index')} className="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</Link>
                    </div>
                </form>
            </Card>
        </AuthenticatedLayout>
    );
}
