<?php
session_start();

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DataDumpster</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard">
        <header class="header">
            <div class="header-content">
                <div class="header-left">
                    <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7,10 12,15 17,10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    <h1>DatadumpsteR</h1>
                </div>
                <div class="header-right">
                    <span id="welcomeMessage">Welcome, <?php echo htmlspecialchars($username); ?>!</span>
                    <button id="logoutBtn" class="btn-secondary">
                        <svg class="logout-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m9 21 7-7-7-7"></path>
                            <path d="M20 12H8"></path>
                            <path d="M8 12V6a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v12a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4v-6"></path>
                        </svg>
                        Logout
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Upload Area -->
            <section class="upload-section">
                <h2>Upload Files</h2>
                <div id="uploadArea" class="upload-area">
                    <svg class="upload-large-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7,10 12,15 17,10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    <p class="upload-text">Drop files here or click to upload</p>
                    <p class="upload-subtext">Supports all file types</p>
                    <input type="file" id="fileInput" multiple hidden>
                    <button id="uploadBtn" class="btn-primary">
                        <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7,10 12,15 17,10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Choose Files
                    </button>
                </div>
            </section>

            <!-- Files List -->
            <section class="files-section">
                <h2>Uploaded Files (<span id="fileCount">0</span>)</h2>
                <div id="filesContainer">
                    <div id="emptyState" class="empty-state">
                        <svg class="file-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        <p>No files uploaded yet</p>
                    </div>
                    <div id="filesTable" class="files-table hidden">
                        <div class="table-header">
                            <div class="table-cell">File</div>
                            <div class="table-cell">Size</div>
                            <div class="table-cell">Upload Date</div>
                            <div class="table-cell">Actions</div>
                        </div>
                        <div id="filesTableBody" class="table-body">
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="dashboard.js"></script>
</body>
</html>