<?php

/**
 * Input Validation Helper
 * 
 * Provides consistent input validation rules and methods
 * for the classroom management system
 */

if (! function_exists('get_validation_rules')) {
    /**
     * Get standardized validation rules for different entities
     *
     * @param string $entity
     * @return array
     */
    function get_validation_rules(string $entity): array
    {
        $rules = [
            'user' => [
                'username' => [
                    'label' => 'Username',
                    'rules' => 'required|min_length[3]|max_length[50]|alpha_numeric_punct',
                    'errors' => [
                        'required' => 'Username harus diisi.',
                        'min_length' => 'Username minimal 3 karakter.',
                        'max_length' => 'Username maksimal 50 karakter.',
                        'alpha_numeric_punct' => 'Username hanya boleh mengandung huruf, angka, dan tanda baca.'
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[6]|max_length[255]',
                    'errors' => [
                        'required' => 'Password harus diisi.',
                        'min_length' => 'Password minimal 6 karakter.',
                        'max_length' => 'Password terlalu panjang.'
                    ]
                ],
                'email' => [
                    'label' => 'Email',
                    'rules' => 'permit_empty|valid_email|max_length[100]',
                    'errors' => [
                        'valid_email' => 'Format email tidak valid.',
                        'max_length' => 'Email terlalu panjang.'
                    ]
                ],
                'nama_lengkap' => [
                    'label' => 'Nama Lengkap',
                    'rules' => 'required|min_length[2]|max_length[100]',
                    'errors' => [
                        'required' => 'Nama lengkap harus diisi.',
                        'min_length' => 'Nama lengkap minimal 2 karakter.',
                        'max_length' => 'Nama lengkap maksimal 100 karakter.'
                    ]
                ]
            ],
            'siswa' => [
                'nama_siswa' => [
                    'label' => 'Nama Siswa',
                    'rules' => 'required|min_length[2]|max_length[100]|regex_match[/^[a-zA-Z\s]+$/]',
                    'errors' => [
                        'required' => 'Nama siswa harus diisi.',
                        'min_length' => 'Nama siswa minimal 2 karakter.',
                        'max_length' => 'Nama siswa maksimal 100 karakter.',
                        'regex_match' => 'Nama siswa hanya boleh mengandung huruf dan spasi.'
                    ]
                ],
                'kelas' => [
                    'label' => 'Kelas',
                    'rules' => 'required|in_list[1,2,3,4,5,6]',
                    'errors' => [
                        'required' => 'Kelas harus dipilih.',
                        'in_list' => 'Kelas harus antara 1-6.'
                    ]
                ],
                'nisn' => [
                    'label' => 'NISN',
                    'rules' => 'permit_empty|exact_length[10]|numeric',
                    'errors' => [
                        'exact_length' => 'NISN harus 10 digit.',
                        'numeric' => 'NISN hanya boleh berisi angka.'
                    ]
                ]
            ],
            'guru' => [
                'nama_guru' => [
                    'label' => 'Nama Guru',
                    'rules' => 'required|min_length[2]|max_length[100]|regex_match[/^[a-zA-Z\s\.]+$/]',
                    'errors' => [
                        'required' => 'Nama guru harus diisi.',
                        'min_length' => 'Nama guru minimal 2 karakter.',
                        'max_length' => 'Nama guru maksimal 100 karakter.',
                        'regex_match' => 'Nama guru hanya boleh mengandung huruf, spasi, dan titik.'
                    ]
                ],
                'nip' => [
                    'label' => 'NIP',
                    'rules' => 'permit_empty|min_length[8]|max_length[20]|numeric',
                    'errors' => [
                        'min_length' => 'NIP minimal 8 digit.',
                        'max_length' => 'NIP maksimal 20 digit.',
                        'numeric' => 'NIP hanya boleh berisi angka.'
                    ]
                ]
            ],
            'nilai' => [
                'nilai' => [
                    'label' => 'Nilai',
                    'rules' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
                    'errors' => [
                        'required' => 'Nilai harus diisi.',
                        'decimal' => 'Nilai harus berupa angka.',
                        'greater_than_equal_to' => 'Nilai tidak boleh kurang dari 0.',
                        'less_than_equal_to' => 'Nilai tidak boleh lebih dari 100.'
                    ]
                ]
            ],
            'absensi' => [
                'tanggal' => [
                    'label' => 'Tanggal',
                    'rules' => 'required|valid_date[Y-m-d]',
                    'errors' => [
                        'required' => 'Tanggal harus diisi.',
                        'valid_date' => 'Format tanggal tidak valid.'
                    ]
                ],
                'status' => [
                    'label' => 'Status',
                    'rules' => 'required|in_list[Hadir,Sakit,Izin,Alpha]',
                    'errors' => [
                        'required' => 'Status absensi harus dipilih.',
                        'in_list' => 'Status absensi tidak valid.'
                    ]
                ]
            ]
        ];

        return $rules[$entity] ?? [];
    }
}

if (! function_exists('validate_form_data')) {
    /**
     * Validate form data using predefined rules
     *
     * @param array $data
     * @param string $entity
     * @param array $additionalRules
     * @return array
     */
    function validate_form_data(array $data, string $entity, array $additionalRules = []): array
    {
        $validation = \Config\Services::validation();
        $rules = get_validation_rules($entity);
        
        // Merge additional rules if provided
        if (!empty($additionalRules)) {
            $rules = array_merge($rules, $additionalRules);
        }
        
        // Extract just the validation rules
        $validationRules = [];
        foreach ($rules as $field => $config) {
            $validationRules[$field] = $config['rules'];
        }
        
        $validation->setRules($validationRules);
        
        $result = [
            'valid' => $validation->run($data),
            'errors' => $validation->getErrors(),
            'validated_data' => $validation->getValidated()
        ];
        
        return $result;
    }
}

if (! function_exists('sanitize_input')) {
    /**
     * Sanitize input data based on field type
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    function sanitize_input($value, string $type = 'text')
    {
        if (is_null($value)) {
            return null;
        }
        
        switch ($type) {
            case 'text':
                return trim(strip_tags($value));
            case 'html':
                // Allow basic HTML tags but sanitize
                return trim(strip_tags($value, '<p><br><strong><em><u>'));
            case 'email':
                return filter_var(trim($value), FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var(trim($value), FILTER_SANITIZE_URL);
            case 'int':
                return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'alpha':
                return preg_replace('/[^a-zA-Z]/', '', $value);
            case 'alphanumeric':
                return preg_replace('/[^a-zA-Z0-9]/', '', $value);
            case 'filename':
                return preg_replace('/[^a-zA-Z0-9\-_\.]/', '', $value);
            default:
                return trim(strip_tags($value));
        }
    }
}

if (! function_exists('validate_file_upload')) {
    /**
     * Validate file upload
     *
     * @param array $file
     * @param array $config
     * @return array
     */
    function validate_file_upload(array $file, array $config = []): array
    {
        $defaultConfig = [
            'max_size' => 2048, // KB
            'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],
            'max_width' => 2000, // pixels
            'max_height' => 2000 // pixels
        ];
        
        $config = array_merge($defaultConfig, $config);
        $errors = [];
        
        if (empty($file['name'])) {
            return ['valid' => false, 'errors' => ['File harus dipilih.']];
        }
        
        // Check file size
        if ($file['size'] > ($config['max_size'] * 1024)) {
            $errors[] = "Ukuran file maksimal " . $config['max_size'] . " KB.";
        }
        
        // Check file type
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $config['allowed_types'])) {
            $errors[] = "Tipe file tidak diizinkan. Hanya: " . implode(', ', $config['allowed_types']);
        }
        
        // Check image dimensions if it's an image
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $imageInfo = getimagesize($file['tmp_name']);
            if ($imageInfo) {
                if ($imageInfo[0] > $config['max_width'] || $imageInfo[1] > $config['max_height']) {
                    $errors[] = "Dimensi gambar maksimal {$config['max_width']}x{$config['max_height']} pixels.";
                }
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}