import { useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { Card } from '../../Components/UI';
import { UserForm } from './Create';

export default function Edit({ auth, user }) {
    const { data, setData, put, processing, errors } = useForm({
        name: user.name ?? '',
        email: user.email ?? '',
        password: '',
        roles: user.role_list ?? [user.role],
        nip: user.nip ?? '',
        mata_pelajaran: user.mata_pelajaran ?? '',
    });
    const initial = { ...data, set: setData, isEdit: true };
    return (
        <AuthenticatedLayout title={`Edit: ${user.name}`}>
            <Card>
                <UserForm initial={initial} submitLabel="Perbarui" processing={processing} errors={errors} onSubmit={(e) => { e.preventDefault(); put(route('users.update', user.id)); }} />
            </Card>
        </AuthenticatedLayout>
    );
}
