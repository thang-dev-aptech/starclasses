<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Course;

class CourseController extends BaseController {
    private $courseModel;

    public function __construct() {
        parent::__construct();
        $this->courseModel = new Course();
    }

    public function index() {
        try {
            $search = $_GET['search'] ?? '';
            $category = $_GET['category'] ?? '';
            $courses = $this->courseModel->getAll($search, $category);
            return $this->success($courses);
        } catch (\Exception $e) {
            return $this->error('Failed to fetch courses');
        }
    }

    public function show($id) {
        try {
            $course = $this->courseModel->getById($id);
            if (!$course) {
                return $this->error('Course not found', 404);
            }
            return $this->success($course);
        } catch (\Exception $e) {
            return $this->error('Failed to fetch course');
        }
    }

    public function store() {
        $errors = $this->validateRequest([
            'course_name' => 'required|min:3',
            'description' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
        ]);

        if (!empty($errors)) {
            return $this->error($errors, 422);
        }

        try {
            // Handle file upload if image is provided
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload($_FILES['image']);
            }

            $courseData = [
                'course_name' => $_POST['course_name'],
                'description' => $_POST['description'],
                'short_description' => $_POST['short_description'] ?? '',
                'price' => $_POST['price'],
                'category' => $_POST['category'],
                'rating' => $_POST['rating'],
                'rating_count' => $_POST['rating_count'],
                'image' => $imagePath,
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            $courseId = $this->courseModel->create($courseData);
            $course = $this->courseModel->getById($courseId);

            return $this->success($course, 'Course created successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to create course');
        }
    }

    public function update($id) {
        $errors = $this->validateRequest([
            'course_name' => 'required|min:3',
            'description' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
        ]);

        if (!empty($errors)) {
            return $this->error($errors, 422);
        }

        try {
            $courseData = [
                'course_name' => $_POST['course_name'],
                'description' => $_POST['description'],
                'short_description' => $_POST['short_description'] ?? '',
                'price' => $_POST['price'],
                'rating' => $_POST['rating'],
                'rating_count' => $_POST['rating_count'],
                'category' => $_POST['category'],
                'is_active' => isset($_POST['is_active']) ? $_POST['is_active'] : 0
            ];

            // Handle file upload if new image is provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $courseData['image'] = $this->handleImageUpload($_FILES['image']);
            } else {
                // Keep existing image if no new image uploaded
                $oldCourse = $this->courseModel->getById($id);
                $courseData['image'] = $oldCourse['image'];
            }

            $this->courseModel->update($id, $courseData);
            return $this->success(null, 'Course updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update course');
        }
    }

    public function delete($id) {
        try {
            $this->courseModel->delete($id);
            return $this->success(null, 'Course deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete course');
        }
    }

    private function handleImageUpload($file) {
        $uploadDir = __DIR__ . '/../../public/uploads/courses/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new \Exception('Failed to upload file');
        }

        return 'uploads/courses/' . $filename;
    }
}
