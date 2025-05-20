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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #2ecc71;
            --light-color: #f8f9fa;
            --dark-color: #333;
            --text-muted: #777;
            --border-color: #ddd;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                        url('https://images.unsplash.com/photo-1532629345422-7515f833a6ac?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--dark-color);
        }

        .upload-container {
            background-color: var(--light-color);
            padding: 3rem;
            border-radius: 15px;
            box-shadow: var(--box-shadow);
            width: 90%;
            max-width: 700px;
            text-align: center;
        }

        .upload-header {
            color: var(--primary-color);
            margin-bottom: 2.5rem;
        }

        .upload-header .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .upload-header h1 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .upload-header p {
            color: var(--text-muted);
            font-size: 1rem;
        }

        #uploadForm {
            margin-top: 2rem;
        }

        .upload-box {
            border: 2px dashed var(--border-color);
            border-radius: 10px;
            padding: 2rem;
            background-color: #fff;
            transition: border-color 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .upload-box:hover {
            border-color: var(--accent-color);
            background-color: #f9f9f9;
        }

        .upload-box i {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .upload-box h3 {
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .upload-box p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
            height: 100%;
        }

        #fileInfo {
            margin-top: 1rem;
            padding: 0.75rem;
            background-color: #e7f3ff;
            border-radius: 5px;
            font-size: 0.9rem;
            color: var(--primary-color);
            display: none;
            border: 1px solid #d1e7ff;
        }

        .btn-upload {
            background-color: var(--accent-color);
            color: var(--light-color);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
            margin-top: 1rem;
            box-shadow: var(--box-shadow);
        }

        .btn-upload:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            text-decoration: none;
            margin-top: 2rem;
            transition: color 0.3s ease;
        }

        .btn-back:hover {
            color: var(--secondary-color);
        }

        .alert-custom {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-danger {
            background-color: #ffebee;
            color: #d32f2f;
            border: 1px solid #ef9a9a;
        }

        .alert-success {
            background-color: #e6f9e8;
            color: #388e3c;
            border: 1px solid #a5d6a7;
        }

        .requirements {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 2rem;
            text-align: left;
            border: 1px solid var(--border-color);
        }

        .requirements h4 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .requirements ul {
            padding-left: 1.2rem;
            list-style-type: disc;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .requirements li {
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .upload-container {
                padding: 2rem;
                margin: 1rem;
            }

            .upload-header h1 {
                font-size: 2rem;
            }

            .upload-box {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <div class="upload-header">
            <div class="icon">
                <i class="fas fa-upload"></i>
            </div>
            <h1>Upload Student Marks</h1>
            <p>Effortlessly upload student marks from an Excel file.</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-custom">
                <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success alert-custom">
                <i class="fas fa-check-circle me-2"></i> <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="uploadForm">
            <div class="upload-box">
                <i class="fas fa-file-excel"></i>
                <h3>Drag & Drop Excel File Here</h3>
                <p>or</p>
                <div class="file-input-wrapper">
                    <input type="file" class="file-input" id="excel_file" name="excel_file" accept=".xlsx, .xls" required>
                    <button type="button" class="btn btn-outline-secondary w-100"><i class="fas fa-folder-open me-2"></i> Browse Files</button>
                </div>
                <div id="fileInfo" class="file-info"></div>
            </div>

            <button type="submit" class="btn btn-primary btn-upload">
                <i class="fas fa-cloud-upload-alt me-2"></i> Upload Marks
            </button>
        </form>

        <div class="requirements">
            <h4><i class="fas fa-lightbulb me-2"></i> File Upload Instructions</h4>
            <ul>
                <li>Ensure your file is in <span class="fw-bold">.xlsx</span> or <span class="fw-bold">.xls</span> format.</li>
                <li>The first row of your Excel sheet will be skipped (assumed to be headers).</li>
                <li>The columns in your file <span class="fw-bold">must</span> be in this order: <span class="fw-bold">Mentee Email, Subject 1, Subject 2, Subject 3, Subject 4, Subject 5</span>.</li>
                <li>Marks for each subject should be numerical values between 0 and 100.</li>
                <li>Please keep the file size under 5MB for efficient uploading.</li>
            </ul>
        </div>

        <a href="mentor.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Mentor Dashboard
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
        const fileInputWrapper = document.querySelector('.file-input-wrapper');
        const fileInput = document.getElementById('excel_file');

        uploadBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadBox.style.borderColor = var(--primary-color);
            uploadBox.style.backgroundColor = 'rgba(52, 152, 219, 0.1)';
        });

        uploadBox.addEventListener('dragleave', () => {
            uploadBox.style.borderColor = var(--border-color);
            uploadBox.style.backgroundColor = '#fff';
        });

        uploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadBox.style.borderColor = var(--border-color);
            uploadBox.style.backgroundColor = '#fff';

            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });
    </script>
</body>
</html>