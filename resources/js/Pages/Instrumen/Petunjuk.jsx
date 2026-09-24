import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Card } from '../../Components/UI';

const RUBRIK = [
    { skor: 4, label: 'Sangat Baik', desc: 'Indikator terlaksana sepenuhnya, konsisten, dan menjadi contoh baik.' },
    { skor: 3, label: 'Baik', desc: 'Indikator terlaksana dengan baik, ada sedikit hal yang bisa ditingkatkan.' },
    { skor: 2, label: 'Cukup', desc: 'Indikator terlaksana sebagian, perlu perbaikan pada beberapa aspek.' },
    { skor: 1, label: 'Kurang', desc: 'Indikator belum terlaksana atau jauh dari harapan.' },
];

export default function Petunjuk() {
    return (
        <AuthenticatedLayout title="Petunjuk Instrumen">
            <Card>
                <div className="p-6 max-w-3xl space-y-5 text-sm text-slate-600">
                    <div className="flex justify-between items-center">
                        <h2 className="text-lg font-bold text-slate-800">Petunjuk Pengisian & Rubrik Skor</h2>
                        <Link href={route('instruments.index')} className="text-sm text-indigo-600 hover:underline">← Kembali</Link>
                    </div>
                    <ol className="list-decimal ml-5 space-y-1.5">
                        <li>Pilih guru, mata pelajaran, kelas, dan tanggal observasi.</li>
                        <li>Beri skor <strong>1–4</strong> untuk setiap indikator sesuai rubrik di bawah.</li>
                        <li>Isi kolom <strong>Bukti</strong> dengan fakta/temuan yang diamati di kelas.</li>
                        <li>Isi kolom <strong>Catatan</strong> bila ada hal khusus atau rekomendasi.</li>
                        <li>Klik <strong>Simpan</strong>. Nilai = (total skor ÷ skor maksimal) × 100.</li>
                    </ol>
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm border border-slate-200 rounded-lg overflow-hidden">
                            <thead>
                                <tr className="bg-slate-50 text-left">
                                    <th className="px-4 py-2">Skor</th>
                                    <th className="px-4 py-2">Predikat</th>
                                    <th className="px-4 py-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                {RUBRIK.map((r) => (
                                    <tr key={r.skor}>
                                        <td className="px-4 py-2 font-bold text-indigo-600">{r.skor}</td>
                                        <td className="px-4 py-2 font-medium text-slate-800">{r.label}</td>
                                        <td className="px-4 py-2">{r.desc}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <div className="bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                        <p className="font-semibold text-indigo-800 mb-1">Interpretasi Nilai Akhir</p>
                        <p>91–100 Sangat Baik · 81–90 Baik · 71–80 Cukup · ≤70 Kurang</p>
                    </div>
                </div>
            </Card>
        </AuthenticatedLayout>
    );
}
