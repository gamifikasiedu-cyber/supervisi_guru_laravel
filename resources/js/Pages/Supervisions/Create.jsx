import { Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Card } from '../../Components/UI';

const input = 'mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border focus:border-indigo-500 focus:ring-indigo-500';

export default function Create({ auth, teachers = [], subjects = [], supervisors = [] }) {
    const { data, setData, post, processing, errors } = useForm({
        teacher_id: '', subject_id: '', class_name: '', schedule_date: '', supervisor_id: '',
    });
    return (
        <AuthenticatedLayout title="Buat Jadwal Supervisi">
            <Card>
                <form onSubmit={(e) => { e.preventDefault(); post(route('supervisions.store')); }} className="p-5 space-y-4 max-w-2xl">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label className="text-sm font-medium">Guru yang disupervisi</label>
                            <select value={data.teacher_id} onChange={(e) => setData('teacher_id', e.target.value)} className={input}>
                                <option value="">-- Pilih guru --</option>
                                {teachers.map((t) => <option key={t.id} value={t.id}>{t.name}</option>)}
                            </select>
                            {errors.teacher_id && <p className="text-xs text-rose-600 mt-1">{errors.teacher_id}</p>}
                        </div>
                        <div>
                            <label className="text-sm font-medium">Supervisor</label>
                            <select value={data.supervisor_id} onChange={(e) => setData('supervisor_id', e.target.value)} className={input}>
                                <option value="">-- Pilih supervisor --</option>
                                {supervisors.map((s) => <option key={s.id} value={s.id}>{s.name}</option>)}
                            </select>
                            {errors.supervisor_id && <p className="text-xs text-rose-600 mt-1">{errors.supervisor_id}</p>}
                        </div>
                        <div>
                            <label className="text-sm font-medium">Mata Pelajaran</label>
                            <select value={data.subject_id} onChange={(e) => setData('subject_id', e.target.value)} className={input}>
                                <option value="">-- Pilih mapel --</option>
                                {subjects.map((s) => <option key={s.id} value={s.id}>{s.name}</option>)}
                            </select>
                            {errors.subject_id && <p className="text-xs text-rose-600 mt-1">{errors.subject_id}</p>}
                        </div>
                        <div>
                            <label className="text-sm font-medium">Kelas</label>
                            <input value={data.class_name} onChange={(e) => setData('class_name', e.target.value)} className={input} placeholder="cth: XII RPL 1" />
                            {errors.class_name && <p className="text-xs text-rose-600 mt-1">{errors.class_name}</p>}
                        </div>
                        <div className="md:col-span-2">
                            <label className="text-sm font-medium">Tanggal & Jam</label>
                            <input type="datetime-local" value={data.schedule_date} onChange={(e) => setData('schedule_date', e.target.value)} className={input} />
                            {errors.schedule_date && <p className="text-xs text-rose-600 mt-1">{errors.schedule_date}</p>}
                        </div>
                    </div>
                    <div className="flex gap-2">
                        <button disabled={processing} className="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50">Simpan Jadwal</button>
                        <Link href={route('supervisions.index')} className="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</Link>
                    </div>
                </form>
            </Card>
        </AuthenticatedLayout>
    );
}
