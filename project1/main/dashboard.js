// Dashboard functionality

document.addEventListener('DOMContentLoaded', async function() {
    // Check if user is logged in
    try {
        const response = await fetch('check_session.php');
        const data = await response.json();
        
        if (!data.logged_in) {
            window.location.href = 'login.php';
            return;
        }
        
        // User is logged in, initialize dashboard
        initializeDashboard(data.username);
        loadFiles();
        setupEventListeners();
    } catch (error) {
        // If there's an error, redirect to login
        window.location.href = 'login.php';
        return;
    }

    function initializeDashboard(username) {
        // Update welcome message 
        const welcomeMessage = document.getElementById('welcomeMessage');
        if (welcomeMessage && !welcomeMessage.textContent.includes(username)) {
            welcomeMessage.textContent = `Welcome, ${username}!`;
        }
    }

    function setupEventListeners() {
        // Logout functionality
        const logoutBtn = document.getElementById('logoutBtn');
        logoutBtn.addEventListener('click', async function() {
        try {
        await fetch('logout.php', { method: 'POST' });
        } catch (error) {
            console.error('Logout failed:', error);
        }
     window.location.href = 'login.php';
});
        // File upload functionality
        const fileInput = document.getElementById('fileInput');
        const uploadBtn = document.getElementById('uploadBtn');
        const uploadArea = document.getElementById('uploadArea');

        uploadBtn.addEventListener('click', function() {
            fileInput.click();
        });

        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function(e) {
            handleFileUpload(e.target.files);
        });

        // Drag and drop functionality
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('drag-active');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-active');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-active');
            handleFileUpload(e.dataTransfer.files);
        });
    }

    async function handleFileUpload(files) {
    if (!files || files.length === 0) return;

    const formData = new FormData();
    
    // Add all files to FormData
    Array.from(files).forEach(file => {
        formData.append('files[]', file);
    });

    try {
        const response = await fetch('upload.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Reload files to show the new uploads
            loadFiles();
        } else {
            alert('Upload failed: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        alert('Upload failed: Network error');
    }
}

    async function loadFiles() {
    console.log("loadFiles() called"); 
    try {
        const response = await fetch('get_files.php');
        const data = await response.json();
        console.log("API Response:", data); 
        
        if (data.success) {
            updateFileCount(data.files.length);
            renderFiles(data.files);
        }
    
    } catch (error) {
        console.error('Failed to load files:', error);
    }
}


    function updateFileCount(count) {
        const fileCount = document.getElementById('fileCount');
        fileCount.textContent = count;
    }

    function renderFiles(files) {
        const emptyState = document.getElementById('emptyState');
        const filesTable = document.getElementById('filesTable');
        const filesTableBody = document.getElementById('filesTableBody');

        if (files.length === 0) {
            emptyState.classList.remove('hidden');
            filesTable.classList.add('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        filesTable.classList.remove('hidden');

        // clear existing rows
        filesTableBody.innerHTML = '';

        // Add file rows
        files.forEach(file => {
            const row = createFileRow(file);
            filesTableBody.appendChild(row);
        });
    }

    function createFileRow(file) {
        const row = document.createElement('div');
        row.className = 'table-row';
        row.innerHTML = `
            <div class="table-cell">
                <div class="file-info">
                    <span class="file-emoji">${getFileEmoji(file.type)}</span>
                    <span class="file-name">${file.name}</span>
                </div>
            </div>
            <div class="table-cell file-size">${file.size}</div>
            <div class="table-cell file-date">${file.uploadDate}</div>
            <div class="table-cell">
                <div class="file-actions">
                    <button class="action-btn download" onclick="downloadFile('${file.id}')" title="Download">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7,10 12,15 17,10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </button>
                    <button class="action-btn delete" onclick="deleteFile('${file.id}')" title="Delete">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3,6 5,6 21,6"></polyline>
                            <path d="m19,6v14a2,2 0 0,1-2,2H7a2,2 0 0,1-2-2V6m3,0V4a2,2 0 0,1,2-2h4a2,2 0 0,1,2,2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        return row;
    }

    // Global functions (attached to window for onclick handlers)
    window.downloadFile = function(fileId) {
    // Create a temporary link and click it to trigger download
    window.open('download_file.php?id=' + fileId, '_blank');
};

    window.deleteFile = async function(fileId) {
    if (confirm('Are you sure you want to delete this file?')) {
        try {
            const response = await fetch('delete_file.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    fileId: fileId
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Reload files to reflect the deletion
                loadFiles();
            } else {
                alert('Failed to delete file: ' + (data.error || 'Unknown error'));
            }
        } catch (error) {
            alert('Failed to delete file: Network error');
        }
    }
};

    function getFileEmoji(type) {
        if (type.startsWith('image/')) return '🖼️';
        if (type.startsWith('video/')) return '🎥';
        if (type.startsWith('audio/')) return '🎵';
        if (type.includes('pdf')) return '📄';
        if (type.includes('text')) return '📝';
        if (type.includes('zip') || type.includes('archive')) return '📦';
        if (type.includes('word') || type.includes('document')) return '📃';
        if (type.includes('excel') || type.includes('spreadsheet')) return '📊';
        if (type.includes('powerpoint') || type.includes('presentation')) return '📽️';
        return '📁';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function generateId() {
        return Math.random().toString(36).substr(2, 9) + Date.now().toString(36);
    }
});