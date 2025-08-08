# DatadumpsteR-File-Manager-System-
DatadumpsteR is a  functional web-based file manager built with PHP, JavaScript, HTML, and CSS. It lets you dump your data in style — upload, manage, and retrieve files through a secure, responsive dashboard.




## ✨ Features
- 🔑 **User Authentication** – Secure login & signup system  
- 📤 **File Uploads** – Drag & drop or click-to-upload interface  
- 📁 **File Management** – View, download, and delete files  
- 👁️ **Password Toggle** – Show/hide password for ease of use  
- 🖼️ **Responsive UI** – Works on desktop and mobile  
- 🎨 **Fun Branding** – Dumpster-themed file storage  

---

## 🛠 Tech Stack
- **PHP** – Backend logic & file handling  
- **JavaScript** – Dynamic UI & AJAX requests  
- **HTML & CSS** – Responsive frontend design

project1/
│
├── config/
│ └── database.php # Database connection settings
│
├── main/
│ ├── authenticate.php # Handles login authentication
│ ├── check_session.php # Checks if the user is logged in
│ ├── dashboard.js # Dashboard JavaScript (file handling, UI updates)
│ ├── dashboard.php # Main dashboard UI
│ ├── login.js # Login form handling
│ ├── login.php # Login page UI
│ ├── logout.php # Logs the user out
│ ├── register.php # Handles user registration
│ ├── signup.js # Signup form handling
│ ├── signup.php # Signup page UI
│ ├── styles.css # Project styling
│ ├── upload.php # Handles file uploads
│ ├── get_files.php # Returns list of uploaded files
│ └── delete_file.php # Handles file deletion
│ └── download_file.php # Handles file download
│
└── uploads/ # Directory for storing uploaded files
