<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'mentor') {
    header("Location: index.php");
    exit();
}

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];

    if (!file_exists($file)) {
        $error = "Error: File not uploaded.";
    } else {
        try {
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();

            $conn = new mysqli('localhost', 'root', '', 'mentorship');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $success_count = 0;
            $error_count = 0;
            
            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $data = [];
                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getValue();
                }

                if (count($data) < 6) {
                    $error_count++;
                    continue;
                }

                $mentee_email = trim($data[0]);
                $subject1 = intval($data[1]);
                $subject2 = intval($data[2]);
                $subject3 = intval($data[3]);
                $subject4 = intval($data[4]);
                $subject5 = intval($data[5]);

                $total_marks = $subject1 + $subject2 + $subject3 + $subject4 + $subject5;
                $overall_percentage = ($total_marks / 500) * 100;

                $stmt = $conn->prepare("INSERT INTO marks (mentee_email, subject1, subject2, subject3, subject4, subject5, overall_percentage) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("siiiiid", $mentee_email, $subject1, $subject2, $subject3, $subject4, $subject5, $overall_percentage);
                    if ($stmt->execute()) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                    $stmt->close();
                } else {
                    $error_count++;
                }
            }

            $conn->close();
            
            if ($success_count > 0) {
                $success_message = "Successfully uploaded marks for $success_count mentees.";
                if ($error_count > 0) {
                    $success_message .= " $error_count records failed.";
                }
            } else {
                $error = "No records were uploaded. Please check your Excel file format.";
            }
            
        } catch (Exception $e) {
            $error = "Error loading Excel file: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Marks | Mentor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            color: var(--dark-color);
        }
        
        .upload-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2.5rem;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .upload-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }
        
        .upload-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .upload-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .upload-header .icon {
            font-size: 3.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .upload-header p {
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        .upload-box {
            border: 2px dashed #ced4da;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .upload-box:hover {
            border-color: var(--accent-color);
            background-color: rgba(72, 149, 239, 0.05);
        }
        
        .upload-box i {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }
        
        .upload-box h3 {
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }
        
        .upload-box p {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
        
        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
        
        .file-info {
            display: none;
            margin-top: 1rem;
            padding: 0.75rem;
            background-color: #e9ecef;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .btn-upload {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-upload:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-back {
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin-top: 1.5rem;
            transition: all 0.2s ease;
        }
        
        .btn-back:hover {
            color: var(--secondary-color);
            transform: translateX(-3px);
        }
        
        .alert-custom {
            border-radius: 8px;
            padding: 1rem 1.25rem;
            font-weight: 500;
        }
        
        .requirements {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .requirements h4 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .requirements ul {
            padding-left: 1.5rem;
        }
        
        .requirements li {
            margin-bottom: 0.5rem;
            color: #495057;
        }
        
        @media (max-width: 768px) {
            .upload-container {
                padding: 1.5rem;
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <div class="upload-header">
            <div class="icon">
                <i class="fas fa-file-excel"></i>
            </div>
            <h1>Upload Student Marks</h1>
            <p>Upload an Excel file containing student marks and performance data</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" id="uploadForm">
            <div class="upload-box">
                <i class="fas fa-cloud-upload-alt"></i>
                <h3>Select Excel File</h3>
                <p>Drag & drop your file here or click to browse</p>
                <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx, .xls" required class="file-input">
                <div id="fileInfo" class="file-info"></div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-upload">
                <i class="fas fa-upload me-2"></i> Upload Marks
            </button>
        </form>
        
        <div class="requirements">
            <h4><i class="fas fa-info-circle me-2"></i>File Requirements</h4>
            <ul>
                <li>File must be in Excel format (.xlsx or .xls)</li>
                <li>First row should contain headers (will be skipped)</li>
                <li>Required columns in order: Mentee Email, Subject 1, Subject 2, Subject 3, Subject 4, Subject 5</li>
                <li>Each subject mark should be between 0-100</li>
                <li>File should not exceed 5MB in size</li>
            </ul>
        </div>
        
        <a href="mentor.php" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Display selected file name
        document.getElementById('excel_file').addEventListener('change', function(e) {
            const fileInfo = document.getElementById('fileInfo');
            if (this.files.length > 0) {
                fileInfo.style.display = 'block';
                fileInfo.innerHTML = `<i class="fas fa-file-excel me-2"></i> Selected: <strong>${this.files[0].name}</strong> (${(this.files[0].size / 1024 / 1024).toFixed(2)} MB)`;
            } else {
                fileInfo.style.display = 'none';
            }
        });
        
        // Drag and drop functionality
        const uploadBox = document.querySelector('.upload-box');
        const fileInput = document.getElementById('excel_file');
        
        uploadBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadBox.style.borderColor = '#4361ee';
            uploadBox.style.backgroundColor = 'rgba(67, 97, 238, 0.1)';
        });
        
        uploadBox.addEventListener('dragleave', () => {
            uploadBox.style.borderColor = '#ced4da';
            uploadBox.style.backgroundColor = '#f8f9fa';
        });
        
        uploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadBox.style.borderColor = '#ced4da';
            uploadBox.style.backgroundColor = '#f8f9fa';
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });
    </script>
</body>
</html>