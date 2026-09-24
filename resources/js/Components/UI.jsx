export function Card({ className = '', children }) {
    return (
        <div className={`bg-white rounded-xl shadow-sm border border-slate-200 ${className}`}>
            {children}
        </div>
    );
}

export function CardHeader({ title, subtitle, action }) {
    return (
        <div className="flex items-start justify-between px-5 pt-4 pb-3 border-b border-slate-100">
            <div>
                <h3 className="font-semibold text-slate-800">{title}</h3>
                {subtitle && <p className="text-sm text-slate-500 mt-0.5">{subtitle}</p>}
            </div>
            {action}
        </div>
    );
}

const STAT_THEMES = {
    indigo: 'bg-indigo-50 text-indigo-600',
    emerald: 'bg-emerald-50 text-emerald-600',
    amber: 'bg-amber-50 text-amber-600',
    rose: 'bg-rose-50 text-rose-600',
    sky: 'bg-sky-50 text-sky-600',
    violet: 'bg-violet-50 text-violet-600',
};

export function StatCard({ label, value, icon, theme = 'indigo', hint }) {
    return (
        <Card className="p-5 flex items-center gap-4">
            <div className={`w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 ${STAT_THEMES[theme] ?? STAT_THEMES.indigo}`}>
                {icon}
            </div>
            <div className="min-w-0">
                <div className="text-2xl font-bold text-slate-800 truncate">{value ?? 0}</div>
                <div className="text-sm text-slate-500">{label}</div>
                {hint && <div className="text-xs text-slate-400 mt-0.5">{hint}</div>}
            </div>
        </Card>
    );
}

const BADGE_THEMES = {
    pending: 'bg-amber-100 text-amber-700',
    approved: 'bg-emerald-100 text-emerald-700',
    rejected: 'bg-rose-100 text-rose-700',
    submitted: 'bg-sky-100 text-sky-700',
    draft: 'bg-slate-100 text-slate-600',
    Scheduled: 'bg-sky-100 text-sky-700',
    Completed: 'bg-emerald-100 text-emerald-700',
    Cancelled: 'bg-slate-100 text-slate-600',
};

export function Badge({ value }) {
    const theme = BADGE_THEMES[value] ?? 'bg-slate-100 text-slate-600';
    return (
        <span className={`inline-block px-2.5 py-0.5 rounded-full text-xs font-medium ${theme}`}>
            {value ?? '-'}
        </span>
    );
}

export function Table({ columns, rows, emptyText = 'Belum ada data.', renderRow }) {
    if (!rows || rows.length === 0) {
        return <p className="px-5 py-6 text-sm text-slate-400 text-center">{emptyText}</p>;
    }
    return (
        <div className="overflow-x-auto">
            <table className="w-full text-sm">
                <thead>
                    <tr className="text-left text-xs uppercase tracking-wide text-slate-400 border-b border-slate-100">
                        {columns.map((c) => (
                            <th key={c} className="px-5 py-2.5 font-medium whitespace-nowrap">{c}</th>
                        ))}
                    </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                    {rows.map((row, i) => renderRow(row, i))}
                </tbody>
            </table>
        </div>
    );
}

export function tdClass(extra = '') {
    return `px-5 py-3 text-slate-600 ${extra}`;
}
