import React, { useState, useEffect } from 'react';
import TeacherAddModal from './TeacherAddModal';
import { useOutletContext } from 'react-router-dom';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
console.log('API_URL:', API_URL);

function TeachersListPage() {
    const { setHeaderContent } = useOutletContext();
    useEffect(() => {
        setHeaderContent({
            title: 'Teachers List',
            desc: 'Welcome to Star Classes admin panel'
        });
    }, [setHeaderContent]);
    const [showAddModal, setShowAddModal] = useState(false);
    const [showEditModal, setShowEditModal] = useState(false);
    const [selectedTeacher, setSelectedTeacher] = useState(null);
    const [teachers, setTeachers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [search, setSearch] = useState('');
    const [categoryFilter, setCategoryFilter] = useState('all');

    const fetchTeachers = async (searchValue = search, categoryValue = categoryFilter) => {
        setLoading(true);
        setError(null);
        try {
            let url = `${API_URL}/api/teachers`;
            const params = [];
            if (searchValue) params.push(`search=${encodeURIComponent(searchValue)}`);
            if (categoryValue && categoryValue !== 'all') params.push(`category=${encodeURIComponent(categoryValue)}`);
            if (params.length > 0) url += `?${params.join('&')}`;
            console.log('Fetching from:', url);
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            console.log('API Response:', data);
            setTeachers(data.data || []);
        } catch (err) {
            console.error('Error fetching teachers:', err);
            setError(err.message);
            setTeachers([]);
        } finally {
            setLoading(false);
        }
    };

    const handleEdit = (teacher) => {
        setSelectedTeacher(teacher);
        setShowEditModal(true);
    };

    const handleDelete = async (teacher) => {
        if (!window.confirm(`Bạn có chắc chắn muốn xoá giáo viên "${teacher.teacher_name}"?`)) return;
        try {
            const response = await fetch(`${API_URL}/api/teachers/${teacher.id}`, {
                method: 'DELETE',
            });
            const result = await response.json();
            if (response.ok && (result.status === 'success' || result.message === 'Deleted')) {
                fetchTeachers(search, categoryFilter);
            } else {
                alert('Xoá thất bại: ' + (result.message || 'Unknown error'));
            }
        } catch {
            alert('Lỗi kết nối server!');
        }
    };

    const handleSearchSubmit = async (e) => {
        e.preventDefault();
        fetchTeachers(search, categoryFilter);
    };

    useEffect(() => {
        fetchTeachers();
    }, []);

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <>
            <section className='p-4 main-content'>
                <div className="d-flex justify-content-between align-items-center mb-4">
                    <div className="d-flex gap-2">
                        <button className="btn btn-primary" onClick={() => setShowAddModal(true)}>
                            <i className="bi bi-plus-lg me-2"></i>Add New Teacher
                        </button>
                    </div>
                    <div className="d-flex gap-2">
                        <form className="d-flex gap-2" style={{maxWidth: '600px'}} onSubmit={handleSearchSubmit}>
                            <input
                                type="text"
                                name="search"
                                className="form-control w-auto"
                                placeholder="Search teacher..."
                                value={search}
                                onChange={e => setSearch(e.target.value)}
                            />
                            <select
                                name="category"
                                className="form-select"
                                style={{width: '180px'}}
                                value={categoryFilter}
                                onChange={e => setCategoryFilter(e.target.value)}
                            >
                                <option value="all">All Categories</option>
                                <option value="programming">Programming</option>
                                <option value="design">Design</option>
                                <option value="business">Business</option>
                            </select>
                            <button className="btn btn-outline-primary" type="submit">
                                <i className="bi bi-search me-1"></i>Search
                            </button>
                        </form>
                    </div>
                </div>
                <div className="card border-0 shadow-sm">
                    <div className="card-body p-0">
                        <div className="table-responsive">
                            <table className="table table-hover align-middle mb-0">
                                <thead className="bg-light">
                                    <tr>
                                        <th className="border-0 text-center" style={{width: '60px'}}>ID</th>
                                        <th className="border-0">Teacher Name</th>
                                        <th className="border-0 text-center">Category</th>
                                        <th className="border-0">Subject</th>
                                        <th className="border-0">Experience</th>
                                        <th className="border-0">Bio</th>
                                        <th className="border-0 text-center">Status</th>
                                        <th className="border-0 text-center">Image</th>
                                        <th className="border-0 text-center" style={{width: '150px'}}>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {teachers.length === 0 ? (
                                        <tr>
                                            <td colSpan={9} className="text-center">No teachers found</td>
                                        </tr>
                                    ) : (
                                        teachers.map(teacher => (
                                            <tr key={teacher.id}>
                                                <td className="text-center">{teacher.id}</td>
                                                <td>{teacher.teacher_name}</td>
                                                <td className="text-center">{teacher.category}</td>
                                                <td>{teacher.subject}</td>
                                                <td>{teacher.experience}</td>
                                                <td>{teacher.bio}</td>
                                                <td className="text-center">
                                                    {teacher.is_active ? (
                                                        <span className="badge bg-success-subtle text-success">
                                                            <i className="bi bi-check-circle-fill me-1"></i>Hiển thị
                                                        </span>
                                                    ) : (
                                                        <span className="badge bg-secondary">
                                                            <i className="bi bi-eye-slash me-1"></i>Ẩn
                                                        </span>
                                                    )}
                                                </td>
                                                <td className="text-center">
                                                    {teacher.image ? (
                                                        <img
                                                            src={`${API_URL}/${teacher.image}`}
                                                            alt={teacher.teacher_name}
                                                            style={{width: '40px', height: '40px', objectFit: 'cover', borderRadius: 4, border: '1px solid #e3e6f0'}}
                                                            onError={(e) => {
                                                                e.target.src = '/assets/no-image.png';
                                                            }}
                                                        />
                                                    ) : (
                                                        <img
                                                            src="/assets/no-image.png"
                                                            alt="No image"
                                                            style={{width: '40px', height: '40px', objectFit: 'cover', borderRadius: 4, border: '1px solid #e3e6f0'}}
                                                        />
                                                    )}
                                                </td>
                                                <td className="text-center">
                                                    <button className="btn btn-sm btn-light" onClick={() => handleEdit(teacher)}>
                                                        <i className="bi bi-pencil me-1"></i>
                                                    </button>
                                                    <button className="btn btn-sm btn-light text-danger" onClick={() => handleDelete(teacher)}>
                                                        <i className="bi bi-trash me-1"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <TeacherAddModal
                show={showAddModal}
                onHide={() => setShowAddModal(false)}
                onSuccess={fetchTeachers}
            />
            <TeacherAddModal
                show={showEditModal}
                onHide={() => { setShowEditModal(false); setSelectedTeacher(null); }}
                onSuccess={() => { setShowEditModal(false); setSelectedTeacher(null); fetchTeachers(); }}
                teacher={selectedTeacher}
                isEdit={true}
            />
        </>
    );
}

export default TeachersListPage;