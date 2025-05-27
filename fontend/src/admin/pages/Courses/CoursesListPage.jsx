import React, { useState, useEffect } from 'react';
import {useOutletContext } from 'react-router-dom'; 
import '@/admin/styles/admin-global.css';
import CoursesAddModal from './CoursesAddModal';
import CoursesEditModal from './CousesEditModal';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';

// Hàm tìm kiếm riêng
async function searchCourses(searchText, category) {
  let url = `${API_URL}/api/courses?`;
  if (searchText) url += `search=${encodeURIComponent(searchText)}&`;
  if (category && category !== 'all') url += `category=${encodeURIComponent(category)}&`;
  try {
    const response = await fetch(url);
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    const data = await response.json();
    return data.data || [];
  } catch {
    return [];
  }
}

function CoursesListPage() {
    const { setHeaderContent } = useOutletContext();
    const [showAddModal, setShowAddModal] = useState(false);
    const [showEditModal, setShowEditModal] = useState(false);
    const [selectedCourse, setSelectedCourse] = useState(null);
    const [courses, setCourses] = useState([]);
    const [loading, setLoading] = useState(true);
    const [searchLoading, setSearchLoading] = useState(false);
    const [error, setError] = useState(null);
    const [search, setSearch] = useState('');
    const [categoryFilter, setCategoryFilter] = useState('all');

    useEffect(() => {
        setHeaderContent({
            title: 'Courses',
            desc: 'Manage your tutoring courses'
        });
        fetchCourses();
    }, [setHeaderContent]);

    const fetchCourses = async () => {
        setLoading(true);
        setError(null);
        try {
            const result = await searchCourses('', 'all');
            setCourses(result);
        } catch (err) {
            setError(err.message);
            setCourses([]);
        } finally {
            setLoading(false);
        }
    };

    const handleEdit = (course) => {
        setSelectedCourse(course);
        setShowEditModal(true);
    };

    const handleSearchSubmit = async (e) => {
        e.preventDefault();
        setSearchLoading(true);
        setError(null);
        try {
            const result = await searchCourses(search, categoryFilter);
            setCourses(result);
        } catch (err) {
            setError(err.message);
            setCourses([]);
        } finally {
            setSearchLoading(false);
        }
    };

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    const handleDelete = async (id) => {
        if (!window.confirm('Bạn có chắc chắn muốn xóa khóa học này?')) return;
        try {
          const response = await fetch(`${API_URL}/api/courses/${id}`, {
            method: 'DELETE',
          });
          if (!response.ok) throw new Error('Xóa thất bại');
          fetchCourses(); // Cập nhật lại danh sách
        } catch (err) {
          alert('Lỗi khi xóa: ' + err.message);
        }
      };

    return (
        <section className='p-4 main-content'>
            <div className="d-flex justify-content-between align-items-center mb-4">
                <div className="d-flex gap-2">
                    <button className="btn btn-primary" onClick={() => setShowAddModal(true)}>
                        <i className="bi bi-plus-lg me-2"></i>Add New Course
                    </button>
                </div>
                <div className="d-flex gap-2">
                    <form className="d-flex gap-2" style={{maxWidth: '600px'}} onSubmit={handleSearchSubmit}>
                        <input
                            type="text"
                            name="search"
                            className="form-control w-auto"
                            placeholder="Search courses..."
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
                        <button disabled={searchLoading} className="btn btn-outline-primary" type="submit">
                            {searchLoading ? <span className="spinner-border spinner-border-sm me-1" /> : <i className="bi bi-search me-1"></i>}
                            Search
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
                                    <th className="border-0" style={{width: '60px'}}>ID</th>
                                    <th className="border-0">Course Name</th>
                                    <th className="border-0">Category</th>
                                    <th className="border-0">Price</th>
                                    <th className="border-0">Description</th>
                                    <th className="border-0 text-center">Rating</th>
                                    <th className="border-0 text-center">Rating Count</th>
                                    <th className="border-0">Status</th>
                                    <th className="border-0 text-center">Image</th>
                                    <th className="border-0 text-center" style={{width: '150px'}}>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {courses.length === 0 ? (
                                    <tr>
                                        <td colSpan={10} className="text-center">No courses found</td>
                                    </tr>
                                ) : (
                                    courses.map(course => (
                                        <tr key={course.id}>
                                            <td>{course.id}</td>
                                            <td>{course.course_name}</td>
                                            <td>{course.category}</td>
                                            <td>${course.price}</td>
                                            <td>{course.short_description}</td>
                                            <td className="text-center">
                                                {course.rating}
                                                <i className="bi bi-star-fill text-warning ms-1"></i>
                                            </td>
                                            <td className="text-center">{course.rating_count}</td>
                                            <td>
                                                {course.is_active ? (
                                                    <span className="badge bg-success-subtle text-success">
                                                        <i className="bi bi-check-circle-fill me-1"></i>Active
                                                    </span>
                                                ) : (
                                                    <span className="badge bg-secondary">
                                                        <i className="bi bi-eye-slash me-1"></i>Inactive
                                                    </span>
                                                )}
                                            </td>
                                            <td className="text-center">
                                                {course.image ? (
                                                    <img
                                                        src={`${API_URL}/${course.image}`}
                                                        alt={course.course_name}
                                                        style={{width: '40px', height: '40px', objectFit: 'cover'}}
                                                        onError={(e) => {
                                                            e.target.src = '/assets/no-image.png';
                                                        }}
                                                    />
                                                ) : (
                                                    <img
                                                        src="/assets/no-image.png"
                                                        alt="No image"
                                                        style={{width: '40px', height: '40px', objectFit: 'cover'}}
                                                    />
                                                )}
                                            </td>
                                            <td className="text-center">
                                                <button
                                                    className="btn btn-sm btn-light"
                                                    onClick={() => handleEdit(course)}
                                                >
                                                    <i className="bi bi-pencil me-1"></i>
                                                </button>
                                                <button className="btn btn-sm btn-light text-danger" onClick={() => handleDelete(course.id)}>
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
            <CoursesAddModal
                show={showAddModal}
                onHide={() => setShowAddModal(false)}
                onSuccess={fetchCourses}
            />
            <CoursesEditModal
                show={showEditModal}
                onHide={() => setShowEditModal(false)}
                onSuccess={fetchCourses}
                course={selectedCourse}
            />
        </section>
    );
}

export default CoursesListPage;