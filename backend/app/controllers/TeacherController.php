<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Teacher;

class TeacherController extends BaseController {
    private $teacherModel;

    public function __construct() {
        parent::__construct();
        $this->teacherModel = new Teacher();
    }

    public function index() {
        try {
            $search = $_GET['search'] ?? '';
            $category = $_GET['category'] ?? '';
            $teachers = $this->teacherModel->getAll($search, $category);
            return $this->success($teachers);
        } catch (\Exception $e) {
            return $this->error('Failed to fetch teachers');
        }
    }

    public function show($id) {
        try {
            $teacher = $this->teacherModel->getById($id);
            if (!$teacher) {
                return $this->error('Teacher not found', 404);
            }
            return $this->success($teacher);
        } catch (\Exception $e) {
            return $this->error('Failed to fetch teacher');
        }
    }

    public function store() {
        $errors = $this->validateRequest([
            'teacher_name' => 'required|min:3',
            'category' => 'required',
            'subject' => 'required',
            'experience' => 'required',
        ]);

        if (!empty($errors)) {
            return $this->error($errors, 422);
        }

        try {
            // Handle image upload if provided
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload($_FILES['image']);
            }

            $teacherData = [
                'teacher_name' => $_POST['teacher_name'],
                'category' => $_POST['category'],
                'subject' => $_POST['subject'],
                'experience' => $_POST['experience'],
                'bio' => $_POST['bio'] ?? null,
                'image' => $imagePath,
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            $teacher = $this->teacherModel->create($teacherData);
            return $this->success($teacher, 'Teacher created successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to create teacher');
        }
    }

    public function update($id) {
        $errors = $this->validateRequest([
            'teacher_name' => 'required|min:3',
            'category' => 'required',
            'subject' => 'required',
            'experience' => 'required',
        ]);

        if (!empty($errors)) {
            return $this->error($errors, 422);
        }

        try {
            $teacherData = [
                'teacher_name' => $_POST['teacher_name'],
                'category' => $_POST['category'],
                'subject' => $_POST['subject'],
                'experience' => $_POST['experience'],
                'bio' => $_POST['bio'] ?? null,
                'image' => null,
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            // Handle image upload if provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $teacherData['image'] = $this->handleImageUpload($_FILES['image']);
            }

            $this->teacherModel->update($id, $teacherData);
            return $this->success(null, 'Teacher updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update teacher');
        }
    }

    public function delete($id) {
        try {
            $this->teacherModel->delete($id);
            return $this->success(null, 'Teacher deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete teacher');
        }
    }

    private function handleImageUpload($file) {
        $uploadDir = __DIR__ . '/../../public/uploads/teachers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new \Exception('Failed to upload file');
        }

        return 'uploads/teachers/' . $filename;
    }
}
