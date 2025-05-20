<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentor') {
    header("Location: index.php");
    exit();
}

require 'vendor/autoload.php'; // For PhpSpreadsheet

$db = new mysqli('localhost', 'username', 'password', 'lms_db');
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['marks_file'])) {
    $file = $_FILES['marks_file'];
    
    if ($file['error'] == UPLOAD_ERR_OK) {
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        if (in_array($file_ext, ['xlsx', 'xls', 'csv'])) {
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file['tmp_name']);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                // Remove header row
                array_shift($rows);
                
                // Prepare statement
                $stmt = $db->prepare("INSERT INTO student_marks (mentee_email, subject, marks, max_marks, uploaded_by, uploaded_at) VALUES (?, ?, ?, ?, ?, NOW())");
                
                $uploaded_by = $_SESSION['email'];
                $db->begin_transaction();
                
                foreach ($rows as $row) {
                    if (count($row) >= 4) {
                        $mentee_email = $row[0];
                        $subject = $row[1];
                        $marks = $row[2];
                        $max_marks = $row[3];
                        
                        $stmt->bind_param("ssdds", $mentee_email, $subject, $marks, $max_marks, $uploaded_by);
                        $stmt->execute();
                    }
                }
                
                $db->commit();
                $success = "Marks uploaded successfully!";
            } catch (Exception $e) {
                $db->rollback();
                $error = "Error processing file: " . $e->getMessage();
            }
        } else {
            $error = "Invalid file format. Please upload Excel files only.";
        }
    } else {
        $error = "File upload error: " . $file['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Marks | LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .upload-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .upload-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .upload-header h2 {
            color: #4361ee;
            font-weight: 700;
        }
        .upload-box {
            border: 2px dashed #4361ee;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            background-color: #f0f4ff;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-box:hover {
            background-color: #e2e8ff;
        }
        .upload-icon {
            font-size: 50px;
            color: #4361ee;
            margin-bottom: 20px;
        }
        .btn-upload {
            background-color: #4361ee;
            color: white;
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: 600;
            border: none;
        }
        .btn-upload:hover {
            background-color: #3a56d4;
            color: white;
        }
        .instructions {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="upload-container">
            <div class="upload-header">
                <h2><i class="fas fa-file-upload me-2"></i>Upload Student Marks</h2>
                <p class="text-muted">Upload an Excel file containing student marks</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form action="upload_marks.php" method="post" enctype="multipart/form-data">
                <input type="file" name="marks_file" id="marks_file" class="d-none" accept=".xlsx,.xls,.csv">
                
                <div class="upload-box" onclick="document.getElementById('marks_file').click()">
                    <div class="upload-icon">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <h4>Click to select Excel file</h4>
                    <p class="text-muted">or drag and drop file here</p>
                    <div id="file-name" class="mt-3 text-primary fw-bold"></div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-upload" id="upload-btn" disabled>
                        <i class="fas fa-upload me-2"></i>Upload Marks
                    </button>
                </div>
            </form>
            
            <div class="instructions">
                <h5><i class="fas fa-info-circle me-2"></i>File Format Instructions</h5>
                <ul class="mt-3">
                    <li>File must be in Excel format (.xlsx, .xls, .csv)</li>
                    <li>First row should be headers (will be skipped)</li>
                    <li>Required columns in order: Mentee Email, Subject, Marks, Max Marks</li>
                    <li><a href="sample_marks.xlsx" download class="text-primary">Download sample file</a></li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('marks_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
            document.getElementById('file-name').textContent = fileName;
            document.getElementById('upload-btn').disabled = !e.target.files[0];
        });
        
        // Drag and drop functionality
        const uploadBox = document.querySelector('.upload-box');
        
        uploadBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadBox.style.backgroundColor = '#e2e8ff';
            uploadBox.style.borderColor = '#3a56d4';
        });
        
        uploadBox.addEventListener('dragleave', () => {
            uploadBox.style.backgroundColor = '#f0f4ff';
            uploadBox.style.borderColor = '#4361ee';
        });
        
        uploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadBox.style.backgroundColor = '#f0f4ff';
            uploadBox.style.borderColor = '#4361ee';
            
            if (e.dataTransfer.files.length) {
                document.getElementById('marks_file').files = e.dataTransfer.files;
                document.getElementById('file-name').textContent = e.dataTransfer.files[0].name;
                document.getElementById('upload-btn').disabled = false;
            }
        });
    </script>
</body>
</html>