

 # Pixify Wiki

## Overview
Pixify is a web application aimed at enabling users to share, explore, and purchase creative visual content. It combines features such as photo sharing, dynamic categorization, and an interactive user experience. This wiki serves as the central resource for understanding Pixify’s architecture, functionality, and development practices.

---

## Table of Contents

1. [Getting Started](#getting-started)
   - [System Requirements](#system-requirements)
   - [Installation](#installation)
   - [Database Setup](#database-setup)

2. [Features](#features)
   - [Discover and Explore](#discover-and-explore)
   - [User Profiles](#user-profiles)
   - [Photo Upload and Management](#photo-upload-and-management)
   - [Commenting and Likes](#commenting-and-likes)

3. [Development](#development)
   - [Folder Structure](#folder-structure)
   - [Technologies Used](#technologies-used)

4. [API Reference](#api-reference)

5. [Contributing](#contributing)

6. [Troubleshooting](#troubleshooting)

---

## Getting Started

### System Requirements for Development
- **Operating System:** Windows, macOS, or Linux
- **Web Server:** Apache or Nginx
- **PHP Version:** 8.0+
- **Database:** MySQL 8.0
- **SSL Certificate:** Required for secure connections

---

## Features

### Discover and Explore
- Users can browse photos by category, popularity, or personalized recommendations.
- Includes "For You" and "Discover" sections.

### User Profiles
- Users can upload profile pictures and add a bio.
- Follow and interact with other users.

### Photo Upload and Management
- Upload images with metadata such as title, description, and categories.
- Auto-detection of aspect ratios and image orientation.

### Commenting and Likes
- Comment threads with nested replies.
- Likes for posts to boost visibility.

---

## Development

### Folder Structure
   ```plaintext
   Pixify/
   ├── certs/                  # SSL certificates
   ├── css/                    # Stylesheets
   ├── images/                 # Static images
   ├── includes/               # PHP includes for modularity
   ├── pages/                  # Core pages (e.g., Discover, Profile)
   ├── uploads/                # User-uploaded images
   └── README.md               # Project overview
   ```
---

### Technologies Used
- **Frontend:** HTML, CSS (Tailwind, Bootstrap), JavaScript
- **Backend:** PHP
- **Database:** MySQL (with SSL integration for secure connections)
- **Hosting:** Azure

---

## API Reference

### Endpoints
1. **User Authentication**
   - **POST /api/register** - Register a new user
   - **POST /api/login** - Authenticate and create a session

2. **Photo Management**
   - **POST /api/upload** - Upload a new photo
   - **GET /api/photos** - Retrieve a list of photos

3. **Comments**
   - **POST /api/comments** - Add a comment to a post
   - **GET /api/comments** - Fetch comments for a post

---

## Contributing

### Guidelines
- Fork the repository and create feature branches.
- Ensure proper code formatting and include comments.
- Submit pull requests with detailed descriptions.

### Reporting Issues
- Use the GitHub Issues tab to report bugs or request features.
- Include screenshots and reproduction steps for bugs.

---

## Troubleshooting

### Common Issues
1. **Database Connection Errors**
   - Verify the `db_connection.php` file for accurate credentials.
   - Ensure the MySQL server is running.

2. **Missing Styles or JavaScript**
   - Check for missing imports in the HTML `<head>` section.
   - Verify the `css/` and `js/` directories.

3. **404 Errors on Pages**
   - Ensure the `.htaccess` file is configured correctly.
   - Verify file paths in links and includes.

---

For more information, visit the [Pixify GitHub Repository](https://github.com/your-username/pixify).


