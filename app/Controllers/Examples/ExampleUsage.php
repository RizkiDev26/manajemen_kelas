<?php

namespace App\Controllers\Examples;

use App\Controllers\BaseController;

/**
 * Example Controller showing how to use the new helper functions
 * This is for demonstration purposes and can be removed in production
 */
class ExampleUsage extends BaseController
{
    /**
     * Example of using validation helpers
     */
    public function createStudent()
    {
        if ($this->request->getMethod() === 'POST') {
            // Get form data
            $formData = $this->request->getPost();
            
            // Validate using our helper
            $validation = validate_form_data($formData, 'siswa');
            
            if (!$validation['valid']) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $validation['errors']);
            }
            
            // Use sanitized data
            $cleanData = $validation['validated_data'];
            
            // Log the activity
            $this->logActivity('create_student', 'Created new student: ' . $cleanData['nama_siswa']);
            
            // Process the student creation
            // ... your business logic here
            
            return redirect()->to('/admin/data-siswa')
                ->with('success', 'Siswa berhasil ditambahkan');
        }
        
        return $this->renderView('admin/data-siswa/create');
    }
    
    /**
     * Example of using caching helpers
     */
    public function getStudentsByClass()
    {
        $classId = $this->request->getGet('class_id');
        
        if (!$classId) {
            return $this->errorResponse('Class ID is required');
        }
        
        // Use caching helper
        $students = cache_students_by_class((int)$classId);
        
        return $this->successResponse('Students retrieved successfully', $students);
    }
    
    /**
     * Example of using error handling helpers
     */
    public function getGrades()
    {
        try {
            // Simulate database operation that might fail
            $grades = safe_data_fetch(function() {
                // Your database query here
                $model = new \App\Models\NilaiModel();
                return $model->findAll();
            }, [], 'fetching grades');
            
            if (empty($grades)) {
                // Return fallback content
                return $this->renderView('admin/nilai/index', [
                    'fallback_content' => fallback_content('grades')
                ]);
            }
            
            return $this->renderView('admin/nilai/index', ['grades' => $grades]);
            
        } catch (\Exception $e) {
            return $this->handleError($e, 'fetching grades', '/admin/nilai');
        }
    }
    
    /**
     * Example of using permission checking
     */
    public function deleteStudent()
    {
        // Check permissions
        if (!check_permissions('delete')) {
            return $this->errorResponse('Insufficient permissions', [], 403);
        }
        
        $studentId = $this->request->getPost('student_id');
        
        // Validate input
        $sanitizedId = sanitize_input($studentId, 'int');
        
        if (!$sanitizedId) {
            return $this->errorResponse('Invalid student ID');
        }
        
        try {
            // Your deletion logic here
            // ...
            
            $this->logActivity('delete_student', "Deleted student ID: {$sanitizedId}");
            
            return $this->successResponse('Student deleted successfully');
            
        } catch (\Exception $e) {
            return $this->handleError($e, 'deleting student');
        }
    }
}