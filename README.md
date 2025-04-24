<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development/)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).




Okay, here is API documentation in Markdown format based on your `routes/api.php` file. This documentation assumes you are using Laravel Sanctum for token-based authentication.

**Note:** This documentation provides the structure and basic information. You should enhance it with:

*   **Specific Validation Rules:** Detail the exact validation rules for each input field in request bodies.
*   **Detailed Response Examples:** Provide more specific JSON examples for success and various error scenarios.
*   **API Resources:** It's highly recommended to use Laravel API Resources to standardize your JSON output format. The documentation examples assume a basic structure.
*   **Role Middleware:** Explicitly state which middleware (e.g., `isAdmin`, `isCompanyManager`) protects each route group or specific route.

---

# Masar App API Documentation (Version 1)

## Introduction

This document provides details about the REST API for the Masar App application. The API allows interaction with users, profiles, jobs, courses, companies, articles, and other resources.

**Base URL:** `/api/v1`

**Data Format:** All request and response bodies should be in JSON format (`Content-Type: application/json`, `Accept: application/json`).

## Authentication

This API uses **Laravel Sanctum** for authentication. Most endpoints require authentication via a bearer token.

1.  **Login/Register:** Use the `POST /login` or `POST /register` endpoints to obtain an API token.
2.  **Send Token:** For all subsequent requests to protected endpoints, include the token in the `Authorization` header:
    ```
    Authorization: Bearer <your_api_token>
    ```
3.  **Get User:** Use `GET /user` to verify the token and retrieve the currently authenticated user's data.
4.  **Logout:** Use `POST /logout` to invalidate the current token.

## General Concepts

*   **Pagination:** List endpoints (e.g., `GET /articles`, `GET /jobs`) support pagination using standard query parameters:
    *   `page`: The page number to retrieve (e.g., `?page=2`).
    *   `per_page` (Optional): Number of items per page (e.g., `?per_page=25`). Default is usually 15.
    The response for paginated results will typically include a `data` array with the items and a `links` and `meta` object for pagination details.
*   **Rate Limiting:** API routes are typically rate-limited. Exceeding the limit will result in a `429 Too Many Requests` error.
*   **Error Responses:**
    *   `401 Unauthorized`: Missing or invalid authentication token.
    *   `403 Forbidden`: Authenticated user does not have permission to perform the action.
    *   `404 Not Found`: The requested resource does not exist.
    *   `422 Unprocessable Entity`: Validation failed. The response body will contain an `errors` object detailing the validation failures.
    *   `500 Internal Server Error`: A server error occurred.

## API Endpoints

---

### 1. Authentication (Public)

These endpoints do not require an authentication token.

*   **Register New User**
    *   **Endpoint:** `POST /register`
    *   **Description:** Creates a new user account.
    *   **Request Body:**
        ```json
        {
            "first_name": "string|required",
            "last_name": "string|required",
            "username": "string|required|unique",
            "email": "email|required|unique",
            "password": "string|required|min:8|confirmed",
            "password_confirmation": "string|required",
            "phone": "string|nullable",
            "type": "string|required|in:خريج,خبير استشاري,مدير شركة"
        }
        ```
    *   **Success Response (201):**
        ```json
        {
            "user": { ...user_data... },
            "token": "string (api_token)"
        }
        ```
    *   **Error Responses:** `422` (Validation Failed)

*   **Login User**
    *   **Endpoint:** `POST /login`
    *   **Description:** Authenticates a user and returns an API token.
    *   **Request Body:**
        ```json
        {
            "login": "string|required (email or username)",
            "password": "string|required",
            "device_name": "string|required (e.g., 'Mobile App', 'Web Browser')"
        }
        ```
    *   **Success Response (200):**
        ```json
        {
            "user": { ...user_data... },
            "token": "string (api_token)"
        }
        ```
    *   **Error Responses:** `401` (Invalid Credentials), `422` (Validation Failed)

*   **Forgot Password** (Implementation needed in controller)
    *   **Endpoint:** `POST /forgot-password`
    *   **Description:** Sends a password reset link to the user's email.
    *   **Request Body:**
        ```json
        {
            "email": "email|required"
        }
        ```
    *   **Success Response (200):**
        ```json
        { "message": "Password reset link sent." }
        ```
    *   **Error Responses:** `422`, `404` (Email not found)

---

### 2. Public Resources (Public)

These endpoints allow viewing public data without authentication.

*   **List Articles**
    *   **Endpoint:** `GET /articles`
    *   **Description:** Retrieves a paginated list of articles.
    *   **Query Parameters:** `page`, `per_page`, `search` (optional), `type` (optional: استشاري, نصائح)
    *   **Success Response (200):** Paginated list of Article objects.
*   **Get Article**
    *   **Endpoint:** `GET /articles/{article}`
    *   **Description:** Retrieves details of a specific article.
    *   **URL Parameters:** `article` (ID of the article)
    *   **Success Response (200):** Single Article object.
    *   **Error Responses:** `404`

*   **List Job Opportunities**
    *   **Endpoint:** `GET /jobs`
    *   **Description:** Retrieves a paginated list of active job opportunities.
    *   **Query Parameters:** `page`, `per_page`, `search` (optional), `type` (optional: وظيفة, تدريب), `location` (optional), `skills` (optional)
    *   **Success Response (200):** Paginated list of JobOpportunity objects.
*   **Get Job Opportunity**
    *   **Endpoint:** `GET /jobs/{job_opportunity}`
    *   **Description:** Retrieves details of a specific job opportunity.
    *   **URL Parameters:** `job_opportunity` (ID of the job)
    *   **Success Response (200):** Single JobOpportunity object (with company details).
    *   **Error Responses:** `404`

*   **List Training Courses**
    *   **Endpoint:** `GET /courses`
    *   **Description:** Retrieves a paginated list of training courses.
    *   **Query Parameters:** `page`, `per_page`, `search` (optional), `stage` (optional: مبتدئ, متوسط, متقدم)
    *   **Success Response (200):** Paginated list of TrainingCourse objects.
*   **Get Training Course**
    *   **Endpoint:** `GET /courses/{training_course}`
    *   **Description:** Retrieves details of a specific training course.
    *   **URL Parameters:** `training_course` (ID of the course)
    *   **Success Response (200):** Single TrainingCourse object (with creator details).
    *   **Error Responses:** `404`

*   **List Companies**
    *   **Endpoint:** `GET /companies`
    *   **Description:** Retrieves a paginated list of companies.
    *   **Query Parameters:** `page`, `per_page`, `search` (optional), `city` (optional), `country` (optional)
    *   **Success Response (200):** Paginated list of Company objects.
*   **Get Company**
    *   **Endpoint:** `GET /companies/{company}`
    *   **Description:** Retrieves details of a specific company, including active jobs.
    *   **URL Parameters:** `company` (ID of the company)
    *   **Success Response (200):** Single Company object (with manager and active job opportunities).
    *   **Error Responses:** `404`

*   **List Groups**
    *   **Endpoint:** `GET /groups`
    *   **Description:** Retrieves a paginated list of groups (Telegram links).
    *   **Query Parameters:** `page`, `per_page`
    *   **Success Response (200):** Paginated list of Group objects.
*   **Get Group** (May just redirect or not be used if index is sufficient)
    *   **Endpoint:** `GET /groups/{group}`
    *   **Description:** Retrieves details of a specific group.
    *   **URL Parameters:** `group` (ID of the group)
    *   **Success Response (200):** Single Group object.
    *   **Error Responses:** `404`

*   **List Skills** (Search)
    *   **Endpoint:** `GET /skills`
    *   **Description:** Retrieves a list of available skills, possibly filtered by search term.
    *   **Query Parameters:** `search` (optional)
    *   **Success Response (200):** List of Skill objects.

---

### 3. Authenticated User Actions (Requires Auth: Sanctum)

These endpoints require a valid Sanctum token.

*   **Get Authenticated User**
    *   **Endpoint:** `GET /user`
    *   **Description:** Retrieves the details of the currently authenticated user, including their profile and skills.
    *   **Success Response (200):** User object with loaded `profile` and `skills` relations.

*   **Logout User**
    *   **Endpoint:** `POST /logout`
    *   **Description:** Invalidates the current user's API token.
    *   **Success Response (200):**
        ```json
        { "message": "Logged out successfully." }
        ```

*   **Show User Profile**
    *   **Endpoint:** `GET /profile`
    *   **Description:** Retrieves the profile details of the authenticated user.
    *   **Success Response (200):** Profile object associated with the user.

*   **Update User Profile**
    *   **Endpoint:** `PUT /profile`
    *   **Description:** Updates the profile details and basic user info (name, phone, photo) of the authenticated user.
    *   **Request Body:** (Include fields to update)
        ```json
        {
            "first_name": "string|required",
            "last_name": "string|required",
            "phone": "string|nullable",
            "photo": "file|nullable|image", // Handle file upload separately
            "University": "string|nullable",
            "GPA": "numeric|nullable",
            "Personal Description": "string|nullable",
            "Technical Description": "string|nullable",
            "Git Hyper Link": "url|nullable"
        }
        ```
    *   **Success Response (200):** Updated Profile object.
    *   **Error Responses:** `422`

*   **Sync User Skills**
    *   **Endpoint:** `POST /profile/skills`
    *   **Description:** Updates/replaces the skills associated with the authenticated user, including their proficiency level.
    *   **Request Body:** (Object where keys are Skill IDs and values are proficiency levels)
        ```json
        {
            "skills": {
                "1": "متقدم",
                "3": "متوسط",
                "7": "مبتدئ"
            }
        }
        ```
    *   **Success Response (200):**
        ```json
        { "message": "Skills updated successfully." }
        ```
    *   **Error Responses:** `422`

*   **List User's Job Applications**
    *   **Endpoint:** `GET /my-applications`
    *   **Description:** Retrieves a paginated list of job applications submitted by the authenticated user.
    *   **Query Parameters:** `page`, `per_page`
    *   **Success Response (200):** Paginated list of JobApplication objects (with job opportunity details).

*   **Apply for a Job**
    *   **Endpoint:** `POST /jobs/{job_opportunity}/apply`
    *   **Description:** Submits a job application for the authenticated user. Requires CV upload.
    *   **URL Parameters:** `job_opportunity` (ID of the job)
    *   **Request Body:** (Multipart form-data)
        *   `CV`: file|required|mimes:pdf,doc,docx|max:5120
        *   `Description`: string|nullable (Cover letter)
    *   **Success Response (201):** Newly created JobApplication object.
    *   **Error Responses:** `404` (Job not found), `422`, `403` (Already applied, Job inactive)

*   **Withdraw Job Application**
    *   **Endpoint:** `DELETE /my-applications/{job_application}`
    *   **Description:** Cancels/withdraws a job application submitted by the authenticated user.
    *   **URL Parameters:** `job_application` (ID of the application)
    *   **Authorization:** User must own the application.
    *   **Success Response (200):**
        ```json
        { "message": "Application withdrawn successfully." }
        ```
    *   **Error Responses:** `404`, `403` (Not allowed to withdraw, e.g., if status is 'Hired')

*   **List User's Enrollments**
    *   **Endpoint:** `GET /my-enrollments`
    *   **Description:** Retrieves a paginated list of course enrollments for the authenticated user.
    *   **Query Parameters:** `page`, `per_page`
    *   **Success Response (200):** Paginated list of Enrollment objects (with course details).

*   **Enroll in a Course**
    *   **Endpoint:** `POST /courses/{training_course}/enroll`
    *   **Description:** Enrolls the authenticated user in a specific training course.
    *   **URL Parameters:** `training_course` (ID of the course)
    *   **Success Response (201):** Newly created Enrollment object.
    *   **Error Responses:** `404` (Course not found), `403` (Already enrolled)

*   **Cancel Course Enrollment**
    *   **Endpoint:** `DELETE /my-enrollments/{enrollment}`
    *   **Description:** Cancels a course enrollment for the authenticated user.
    *   **URL Parameters:** `enrollment` (ID of the enrollment)
    *   **Authorization:** User must own the enrollment.
    *   **Success Response (200):**
        ```json
        { "message": "Enrollment cancelled successfully." }
        ```
    *   **Error Responses:** `404`, `403` (Not allowed to cancel, e.g., if course completed)

*   **Get Recommendations**
    *   **Endpoint:** `GET /recommendations`
    *   **Description:** Retrieves personalized job and course recommendations for the authenticated user based on their profile and skills.
    *   **Success Response (200):**
        ```json
        {
            "recommended_jobs": [ ... JobOpportunity objects ... ],
            "recommended_courses": [ ... TrainingCourse objects ... ],
            "ai_recommendations": [] // Placeholder for potential AI results
        }
        ```

---

### 4. Company Manager Actions (Requires Auth: Sanctum + Role: Company Manager)

These endpoints require the user to be authenticated and have the 'مدير شركة' role.

*   **Get Managed Company Details**
    *   **Endpoint:** `GET /company-manager/company`
    *   **Description:** Retrieves the details of the company associated with the authenticated manager.
    *   **Success Response (200):** Company object.
    *   **Error Responses:** `404` (Company not found/set up for manager)

*   **Update Managed Company Details**
    *   **Endpoint:** `PUT /company-manager/company`
    *   **Description:** Updates the details of the company associated with the authenticated manager.
    *   **Request Body:** (Fields from Company model to update)
        ```json
        {
            "Name": "string|required",
            "Email": "email|nullable|unique",
            "Phone": "string|nullable",
             // ... other company fields ...
        }
        ```
    *   **Success Response (200):** Updated Company object.
    *   **Error Responses:** `404`, `422`

*   **List Managed Job Opportunities**
    *   **Endpoint:** `GET /company-manager/jobs`
    *   **Description:** Retrieves a paginated list of job opportunities created by the authenticated manager.
    *   **Query Parameters:** `page`, `per_page`, `status` (optional)
    *   **Success Response (200):** Paginated list of JobOpportunity objects.

*   **Create Job Opportunity**
    *   **Endpoint:** `POST /company-manager/jobs`
    *   **Description:** Creates a new job opportunity associated with the manager's company.
    *   **Request Body:** (Job details)
        ```json
         {
            "Job Title": "string|required",
            "Job Description": "string|required",
            "Qualification": "string|nullable",
            "Site": "string|required",
            "Skills": "string|nullable", // Or array
            "Type": "string|required|in:وظيفة,تدريب",
            "End Date": "date|nullable"
         }
        ```
    *   **Success Response (201):** Newly created JobOpportunity object.
    *   **Error Responses:** `422`

*   **Get Managed Job Opportunity**
    *   **Endpoint:** `GET /company-manager/jobs/{job}`
    *   **Description:** Retrieves details of a specific job opportunity created by the manager.
    *   **URL Parameters:** `job` (ID of the job opportunity)
    *   **Authorization:** Manager must own the job.
    *   **Success Response (200):** Single JobOpportunity object.
    *   **Error Responses:** `404`, `403`

*   **Update Managed Job Opportunity**
    *   **Endpoint:** `PUT /company-manager/jobs/{job}`
    *   **Description:** Updates a specific job opportunity created by the manager.
    *   **URL Parameters:** `job` (ID of the job opportunity)
    *   **Authorization:** Manager must own the job.
    *   **Request Body:** (Job details to update)
    *   **Success Response (200):** Updated JobOpportunity object.
    *   **Error Responses:** `404`, `403`, `422`

*   **Delete Managed Job Opportunity**
    *   **Endpoint:** `DELETE /company-manager/jobs/{job}`
    *   **Description:** Deletes a specific job opportunity created by the manager.
    *   **URL Parameters:** `job` (ID of the job opportunity)
    *   **Authorization:** Manager must own the job.
    *   **Success Response (200):**
        ```json
        { "message": "Job opportunity deleted successfully." }
        ```
    *   **Error Responses:** `404`, `403`

*   **List Managed Training Courses** (If applicable)
    *   **Endpoint:** `GET /company-manager/courses`
    *   *(Similar to jobs)*
*   **Create Training Course** (If applicable)
    *   **Endpoint:** `POST /company-manager/courses`
    *   *(Similar to jobs)*
*   **Get Managed Training Course** (If applicable)
    *   **Endpoint:** `GET /company-manager/courses/{course}`
    *   *(Similar to jobs)*
*   **Update Managed Training Course** (If applicable)
    *   **Endpoint:** `PUT /company-manager/courses/{course}`
    *   *(Similar to jobs)*
*   **Delete Managed Training Course** (If applicable)
    *   **Endpoint:** `DELETE /company-manager/courses/{course}`
    *   *(Similar to jobs)*

*   **List Job Applicants**
    *   **Endpoint:** `GET /company-manager/jobs/{job_opportunity}/applicants`
    *   **Description:** Retrieves a paginated list of applicants for a specific job opportunity owned by the manager.
    *   **URL Parameters:** `job_opportunity` (ID of the job)
    *   **Authorization:** Manager must own the job.
    *   **Query Parameters:** `page`, `per_page`, `status` (optional)
    *   **Success Response (200):** Paginated list of JobApplication objects (with user details).
    *   **Error Responses:** `404`, `403`

*   **List Course Enrollees**
    *   **Endpoint:** `GET /company-manager/courses/{training_course}/enrollees`
    *   **Description:** Retrieves a paginated list of users enrolled in a specific course owned by the manager.
    *   **URL Parameters:** `training_course` (ID of the course)
    *   **Authorization:** Manager must own the course.
    *   **Query Parameters:** `page`, `per_page`, `status` (optional)
    *   **Success Response (200):** Paginated list of Enrollment objects (with user details).
    *   **Error Responses:** `404`, `403`

---

### 5. Consultant Actions (Requires Auth: Sanctum + Role: Consultant)

These endpoints require the user to be authenticated and have the 'خبير استشاري' role.

*   **List Managed Articles**
    *   **Endpoint:** `GET /consultant/articles`
    *   **Description:** Retrieves a paginated list of articles created by the authenticated consultant.
    *   **Query Parameters:** `page`, `per_page`
    *   **Success Response (200):** Paginated list of Article objects.

*   **Create Article**
    *   **Endpoint:** `POST /consultant/articles`
    *   **Description:** Creates a new article authored by the consultant.
    *   **Request Body:**
        ```json
        {
            "Title": "string|required",
            "Description": "string|required",
            "Type": "string|required|in:استشاري,نصائح",
            "Article Photo": "file|nullable|image" // Handle file upload separately
        }
        ```
    *   **Success Response (201):** Newly created Article object.
    *   **Error Responses:** `422`

*   **Get Managed Article**
    *   **Endpoint:** `GET /consultant/articles/{article}`
    *   **Description:** Retrieves details of a specific article created by the consultant.
    *   **URL Parameters:** `article` (ID of the article)
    *   **Authorization:** Consultant must own the article.
    *   **Success Response (200):** Single Article object.
    *   **Error Responses:** `404`, `403`

*   **Update Managed Article**
    *   **Endpoint:** `PUT /consultant/articles/{article}`
    *   **Description:** Updates a specific article created by the consultant.
    *   **URL Parameters:** `article` (ID of the article)
    *   **Authorization:** Consultant must own the article.
    *   **Request Body:** (Article details to update)
    *   **Success Response (200):** Updated Article object.
    *   **Error Responses:** `404`, `403`, `422`

*   **Delete Managed Article**
    *   **Endpoint:** `DELETE /consultant/articles/{article}`
    *   **Description:** Deletes a specific article created by the consultant.
    *   **URL Parameters:** `article` (ID of the article)
    *   **Authorization:** Consultant must own the article.
    *   **Success Response (200):**
        ```json
        { "message": "Article deleted successfully." }
        ```
    *   **Error Responses:** `404`, `403`

*   *(Add routes for managing courses if consultants can create them, similar to company manager course routes)*

---

### 6. Admin Actions (Requires Auth: Sanctum + Role: Admin)

These endpoints require the user to be authenticated and have the 'Admin' role. They typically operate on all resources, not just those owned by the admin.

*   **List Users**
    *   **Endpoint:** `GET /admin/users`
    *   **Description:** Retrieves a paginated list of all users.
    *   **Query Parameters:** `page`, `per_page`, `search`, `type`, `status`
    *   **Success Response (200):** Paginated list of User objects.

*   **Create User**
    *   **Endpoint:** `POST /admin/users`
    *   **Description:** Creates a new user account (any type).
    *   **Request Body:** (User details, including type and status)
    *   **Success Response (201):** Newly created User object.
    *   **Error Responses:** `422`

*   **Get User**
    *   **Endpoint:** `GET /admin/users/{user}`
    *   **Description:** Retrieves details of a specific user.
    *   **URL Parameters:** `user` (ID of the user)
    *   **Success Response (200):** Single User object.
    *   **Error Responses:** `404`

*   **Update User**
    *   **Endpoint:** `PUT /admin/users/{user}`
    *   **Description:** Updates details of a specific user.
    *   **URL Parameters:** `user` (ID of the user)
    *   **Request Body:** (User details to update, including type/status, optional password)
    *   **Success Response (200):** Updated User object.
    *   **Error Responses:** `404`, `422`

*   **Delete User**
    *   **Endpoint:** `DELETE /admin/users/{user}`
    *   **Description:** Deletes a specific user account.
    *   **URL Parameters:** `user` (ID of the user)
    *   **Success Response (200):** `{ "message": "User deleted." }`
    *   **Error Responses:** `404`, `403` (e.g., cannot delete self)

*   **List Skills (Admin)**
    *   **Endpoint:** `GET /admin/skills`
    *   **Description:** Retrieves a paginated list of all skills.
    *   **Query Parameters:** `page`, `per_page`, `search`
    *   **Success Response (200):** Paginated list of Skill objects.

*   **Create Skill**
    *   **Endpoint:** `POST /admin/skills`
    *   **Request Body:** `{ "Name": "string|required|unique" }`
    *   **Success Response (201):** New Skill object.
    *   **Error Responses:** `422`

*   **Get Skill**
    *   **Endpoint:** `GET /admin/skills/{skill}`
    *   **Success Response (200):** Skill object.
    *   **Error Responses:** `404`

*   **Update Skill**
    *   **Endpoint:** `PUT /admin/skills/{skill}`
    *   **Request Body:** `{ "Name": "string|required|unique(ignore self)" }`
    *   **Success Response (200):** Updated Skill object.
    *   **Error Responses:** `404`, `422`

*   **Delete Skill**
    *   **Endpoint:** `DELETE /admin/skills/{skill}`
    *   **Success Response (200):** `{ "message": "Skill deleted." }`
    *   **Error Responses:** `404`, `403` (if skill is in use?)

*   *(Repeat `apiResource` pattern for `groups`, `companies`, `articles`, `jobs`, `courses` providing full CRUD access for Admin, reusing public/other controllers where appropriate but ensuring Admin authorization)*

*   **List Pending Company Requests**
    *   **Endpoint:** `GET /admin/company-requests`
    *   **Description:** Retrieves a list of companies awaiting approval (assuming 'Pending' status).
    *   **Query Parameters:** `page`, `per_page`
    *   **Success Response (200):** Paginated list of Company objects (with user details).

*   **Approve Company Request**
    *   **Endpoint:** `PUT /admin/company-requests/{company}/approve`
    *   **Description:** Approves a pending company request, changing its status.
    *   **URL Parameters:** `company` (ID of the company)
    *   **Success Response (200):** `{ "message": "Company approved." }`
    *   **Error Responses:** `404`, `403` (if not pending)

*   **Reject Company Request**
    *   **Endpoint:** `PUT /admin/company-requests/{company}/reject`
    *   **Description:** Rejects a pending company request, changing its status.
    *   **URL Parameters:** `company` (ID of the company)
    *   **Request Body:** `{ "rejection_reason": "string|nullable" }` (Optional)
    *   **Success Response (200):** `{ "message": "Company rejected." }`
    *   **Error Responses:** `404`, `403` (if not pending)

---