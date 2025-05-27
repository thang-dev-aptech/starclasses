import React, { useState, useEffect } from 'react';
import { Modal, Button, Form, Row, Col } from 'react-bootstrap';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';

function CoursesEditModal({ show, onHide, onSuccess, course }) {
  const [formData, setFormData] = useState({
    course_name: '',
    short_description: '',
    description: '',
    category: '',
    price: '',
    rating: 0,
    rating_count: 0,
    image: null,
    is_active: true
  });
  const [previewImage, setPreviewImage] = useState('/assets/no-image.png');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (show && course) {
      setFormData({
        course_name: course.course_name || '',
        short_description: course.short_description || '',
        description: course.description || '',
        category: course.category || '',
        price: course.price || '',
        rating: course.rating || 0,
        rating_count: course.rating_count || 0,
        image: null, // reset file input
        is_active: course.is_active ? true : false
      });
      setPreviewImage(course.image ? `${API_URL}/${course.image}` : '/assets/no-image.png');
    }
  }, [show, course]);

  const handleChange = (e) => {
    const { name, value, files, type, checked } = e.target;
    if (type === 'file') {
      const file = files[0];
      setFormData(prev => ({ ...prev, [name]: file }));
      if (file) {
        setPreviewImage(URL.createObjectURL(file));
      } else {
        setPreviewImage(course && course.image ? `${API_URL}/${course.image}` : '/assets/no-image.png');
      }
    } else {
      setFormData(prev => ({
        ...prev,
        [name]: type === 'checkbox' ? checked : value
      }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    const data = new FormData();
    data.append('course_name', formData.course_name);
    data.append('short_description', formData.short_description);
    data.append('description', formData.description);
    data.append('category', formData.category);
    data.append('price', formData.price);
    data.append('rating', formData.rating);
    data.append('rating_count', formData.rating_count);
    data.append('is_active', formData.is_active ? 1 : 0);
    if (formData.image) {
      data.append('image', formData.image);
    }
    try {
      const response = await fetch(`${API_URL}/api/courses/${course.id}`, {
        method: 'POST', // Nếu backend hỗ trợ PUT thì đổi thành 'PUT'
        body: data
      });
      if (!response.ok) throw new Error('Failed to update course');
      if (typeof onSuccess === 'function') onSuccess();
      onHide();
    } catch (error) {
      alert('Error: ' + error.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <Modal show={show} onHide={onHide} size="lg" centered backdrop="static" keyboard={false}>
      <Modal.Header closeButton>
        <Modal.Title>Chỉnh sửa khóa học</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Form onSubmit={handleSubmit} id="editCourseForm">
          <Row>
            <Col md={8}>
              <Form.Group className="mb-3">
                <Form.Label>Tên khóa học <span className="text-danger">*</span></Form.Label>
                <Form.Control
                  type="text"
                  name="course_name"
                  value={formData.course_name}
                  onChange={handleChange}
                  required
                />
              </Form.Group>
              <Form.Group className="mb-3">
                <Form.Label>Mô tả ngắn</Form.Label>
                <Form.Control
                  type="text"
                  name="short_description"
                  value={formData.short_description}
                  onChange={handleChange}
                  maxLength={255}
                />
                <Form.Text>Hiển thị trên thẻ khóa học (tối đa 255 ký tự)</Form.Text>
              </Form.Group>
              <Form.Group className="mb-3">
                <Form.Label>Mô tả chi tiết</Form.Label>
                <Form.Control
                  as="textarea"
                  rows={4}
                  name="description"
                  value={formData.description}
                  onChange={handleChange}
                  required
                />
              </Form.Group>
            </Col>
            <Col md={4}>
              <Form.Group className="mb-3">
                <Form.Label>Giá khóa học (VNĐ) <span className="text-danger">*</span></Form.Label>
                <Form.Control
                  type="number"
                  name="price"
                  value={formData.price}
                  onChange={handleChange}
                  required
                  min="0"
                  step="0.01"
                />
              </Form.Group>
              <Form.Group className="mb-3">
                <Form.Label>Danh mục (Category) <span className="text-danger">*</span></Form.Label>
                <Form.Select
                  name="category"
                  value={formData.category}
                  onChange={handleChange}
                  required
                >
                  <option value="">Chọn danh mục</option>
                  <option value="programming">Programming</option>
                  <option value="design">Design</option>
                  <option value="business">Business</option>
                </Form.Select>
              </Form.Group>
              <Form.Group className="mb-3">
                <Form.Label>Điểm đánh giá (Rating)</Form.Label>
                <Form.Control
                  type="number"
                  name="rating"
                  value={formData.rating}
                  onChange={handleChange}
                  min="0"
                  max="5"
                  step="0.1"
                />
              </Form.Group>
              <Form.Group className="mb-3">
                <Form.Label>Số lượt đánh giá (Rating Count)</Form.Label>
                <Form.Control
                  type="number"
                  name="rating_count"
                  value={formData.rating_count}
                  onChange={handleChange}
                  min="0"
                  step="1"
                />
              </Form.Group>
              <Form.Group className="mb-3">
                <Form.Label>Ảnh đại diện</Form.Label>
                <Form.Control
                  type="file"
                  name="image"
                  onChange={handleChange}
                  accept="image/*"
                />
                <Form.Text>Định dạng: JPG, PNG, GIF. Tối đa 2MB</Form.Text>
                <div className="mt-2 text-center">
                  <img
                    src={previewImage}
                    alt="Preview"
                    style={{ width: 80, height: 80, objectFit: 'cover', border: '1px solid #eee', borderRadius: 8, display: 'inline-block' }}
                  />
                </div>
              </Form.Group>
              <Form.Group className="mb-3">
                <Form.Check
                  type="switch"
                  id="status-switch"
                  name="is_active"
                  label="Hiển thị khóa học"
                  checked={formData.is_active}
                  onChange={handleChange}
                />
              </Form.Group>
            </Col>
          </Row>
        </Form>
      </Modal.Body>
      <Modal.Footer>
        <Button variant="secondary" onClick={onHide} disabled={loading}>
          Hủy
        </Button>
        <Button variant="primary" type="submit" form="editCourseForm" disabled={loading}>
          {loading ? 'Đang lưu...' : 'Lưu thay đổi'}
        </Button>
      </Modal.Footer>
    </Modal>
  );
}

export default CoursesEditModal;
