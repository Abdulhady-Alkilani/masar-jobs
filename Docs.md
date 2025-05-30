Okay, I will reformat the provided API documentation into a structured Markdown (`.md`) file. I'll organize the endpoints, format the URLs and JSON code blocks correctly, and make it more readable for developers.

```markdown
# Masar-App API Documentation

## General Information

### Base URLs

*   **Locally:** `http://127.0.0.1:8000`
*   **API Prefix:** `/api/v1`
*   **Total Base URL:** `http://127.0.0.1:8000/api/v1`

### Role-Specific Base URLs

*   **Admin:** `http://127.0.0.1:8000/api/v1/admin`
*   **Company Manager:** `http://127.0.0.1:8000/api/v1/company-manager`
*   **Consultant:** `http://127.0.0.1:8000/api/v1/consultant`

*(Note: All subsequent endpoint URLs are relative to the **Total Base URL** unless otherwise specified by a role prefix)*

### Important Hints

*   **Request Body:** Remember to put data in the request body for HTTP `POST` & `PUT` requests.
*   **Authentication:** For endpoints requiring authentication, you must first log in to obtain a Bearer token. Include this token in the `Authorization` header for every subsequent request:
    ```
    Authorization: Bearer YOUR_ACCESS_TOKEN
    ```
    This applies to all authenticated user types (Admin, Graduate, Company Manager, Consultant).
*   **Content Type:** All request bodies should be in JSON format.
*   **Headers:** Include the `Accept: application/json` header on every HTTP request to ensure you receive JSON responses.
*   **`apiResources` Routes:** When a resource uses `apiResources` (e.g., `/posts`), it implies standard CRUD operations mapped as follows:
    *   `GET /resource` => `index` (Show all items)
    *   `POST /resource` => `store` (Create new item)
    *   `GET /resource/{id}` => `show` (Show a single item)
    *   `PUT /resource/{id}` => `update` (Update an existing item)
    *   `DELETE /resource/{id}` => `destroy` (Delete an item)

---

## Authentication

### Register User

*   **Method:** `POST`
*   **URL:** `/register`
*   **Authentication:** None Required
*   **Input Body:**
    ```json
    {
        "first_name": "Sami",
        "last_name": "Ahmed",
        "username": "samiahmed",
        "email": "sami.ahmed@email.com",
        "password": "complex_password123",
        "password_confirmation": "complex_password123",
        "phone": "0551122334",
        "type": "خريج" // Or 'خبير استشاري' or 'مدير شركة'
    }
    ```
*   **Output (Success: 201 Created):**
    ```json
    {
        "message": "User registered successfully",
        "access_token": "6|ObcWf9OZLp16WuoMhl0nWQe4iyBWlhFayljh00Ta719a4d70", // Example Token
        "token_type": "Bearer",
        "user": {
            "first_name": "Sami",
            "last_name": "Ahmed",
            "username": "samiahmed",
            "email": "sami.ahmed@email.com",
            "phone": "0551122334",
            "type": "خريج",
            "status": "مفعل",
            "updated_at": "2025-05-02T14:45:53.000000Z",
            "created_at": "2025-05-02T14:45:53.000000Z",
            "UserID": 5,
            "profile": { // Profile is created but initially empty/null
                "ProfileID": 3,
                "UserID": 5,
                "University": null,
                "GPA": null,
                "Personal Description": null,
                "Technical Description": null,
                "Git Hyper Link": null,
                "created_at": "2025-05-02T14:45:53.000000Z",
                "updated_at": "2025-05-02T14:45:53.000000Z"
            }
        }
    }
    ```
*   **Output (Failure):**
    *   `422 Unprocessable Entity`: Validation errors (e.g., missing fields, email format, password mismatch).

### Login User

*   **Method:** `POST`
*   **URL:** `/login`
*   **Authentication:** None Required
*   **Input Body:**
    ```json
    {
        "email": "admin@example.com",
        "password": "password"
    }
    ```
*   **Output (Success: 200 OK):**
    ```json
    {
        "message": "Login successful",
        "access_token": "7|GZza5JfEWhoyY3xLWKTnFzv2e2uefyMe2JpYy8js0b063db1", // Example Token
        "token_type": "Bearer",
        "user": {
            "UserID": 1,
            "first_name": "Admin",
            "last_name": "User",
            "username": "admin",
            "email": "admin@example.com",
            "email_verified": 1,
            "phone": "111111111",
            "photo": null,
            "status": "مفعل",
            "type": "Admin",
            "created_at": "2025-04-29T18:23:07.000000Z",
            "updated_at": "2025-04-29T18:23:07.000000Z",
            "profile": null,
            "skills": [],
            "company": null
        }
    }
    ```
*   **Output (Failure):**
    *   `401 Unauthorized` / `422 Unprocessable Entity`: Invalid credentials.
    *   `403 Forbidden`: Account is not active.

### Logout User

*   **Method:** `POST`
*   **URL:** `/logout`
*   **Authentication:** Bearer Token Required
*   **Input Body:** None
*   **Output (Success: 200 OK):**
    ```json
    {
        "message": "Successfully logged out"
    }
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.

### Show Current User Data

*   **Method:** `GET`
*   **URL:** `/user`
*   **Authentication:** Bearer Token Required
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Example for Admin user)
    ```json
    {
    "UserID": 3,
    "first_name": "Expert",
    "last_name": "Consultant",
    "username": "expertconsultant",
    "email": "expert@consultant.com",
    "email_verified": 1,
    "phone": "333333333",
    "photo": null,
    "status": "مفعل",
    "type": "خبير استشاري",
    "created_at": "2025-04-29T18:23:08.000000Z",
    "updated_at": "2025-04-29T18:23:08.000000Z",
    "profile": {
        "ProfileID": 2,
        "UserID": 3,
        "University": "Stanford University",
        "GPA": "3.9",
        "Personal Description": "Experienced consultant in software architecture.",
        "Technical Description": "Specializing in scalable backend systems and cloud infrastructure.",
        "Git Hyper Link": "https://github.com/expertconsultant",
        "created_at": "2025-04-29T18:23:08.000000Z",
        "updated_at": "2025-04-29T18:23:08.000000Z"
    },
    "skills": [
        {
            "SkillID": 1,
            "Name": "PHP",
            "pivot": {
                "UserID": 3,
                "SkillID": 1,
                "Stage": "متقدم"
            }
        },
        {
            "SkillID": 6,
            "Name": "MySQL",
            "pivot": {
                "UserID": 3,
                "SkillID": 6,
                "Stage": "مبتدئ"
            }
        },
        {
            "SkillID": 7,
            "Name": "Problem Solving",
            "pivot": {
                "UserID": 3,
                "SkillID": 7,
                "Stage": "متقدم"
            }
        },
        {
            "SkillID": 9,
            "Name": "Teamwork",
            "pivot": {
                "UserID": 3,
                "SkillID": 9,
                "Stage": "متوسط"
            }
        }
    ]
}
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.

---

## Public Endpoints (No Authentication Required)

### List Articles

*   **Method:** `GET`
*   **URL:** `/articles`
*   **Authentication:** None Required
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Paginated List)
    ```json
    {
        "current_page": 1,
        "data": [
            {
                "ArticleID": 1,
                "UserID": 3,
                "Title": "أهمية بناء ملف شخصي قوي للخريجين",
                "Description": "... نص المقال هنا يتحدث عن كيفية بناء ملف شخصي جذاب لأصحاب العمل",
                "Date": "2025-04-24T00:00:00.000000Z",
                "Type": "نصائح",
                "Article Photo": null,
                "created_at": "2025-04-29T18:23:08.000000Z",
                "updated_at": "2025-04-29T18:23:08.000000Z",
                "user": {
                    "UserID": 3,
                    "first_name": "Expert",
                    "last_name": "Consultant"
                }
            },
            {
                "ArticleID": 2,
                "UserID": 3,
                "Title": "الاتجاهات الحديثة في تطوير الويب لعام 2024",
                "Description": "... استعراض لأحدث التقنيات والأطر في عالم تطوير الويب",
                "Date": "2025-04-27T00:00:00.000000Z",
                "Type": "استشاري",
                "Article Photo": null,
                "created_at": "2025-04-29T18:23:08.000000Z",
                "updated_at": "2025-04-29T18:23:08.000000Z",
                "user": {
                    "UserID": 3,
                    "first_name": "Expert",
                    "last_name": "Consultant"
                }
            }
        ],
        "first_page_url": "http://127.0.0.1:8000/api/v1/articles?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://127.0.0.1:8000/api/v1/articles?page=1",
        "links": [
            { "url": null, "label": "&laquo; Previous", "active": false },
            { "url": "http://127.0.0.1:8000/api/v1/articles?page=1", "label": "1", "active": true },
            { "url": null, "label": "Next &raquo;", "active": false }
        ],
        "next_page_url": null,
        "path": "http://127.0.0.1:8000/api/v1/articles",
        "per_page": 15,
        "prev_page_url": null,
        "to": 2,
        "total": 2
    }
    ```

### Show Single Article

*   **Method:** `GET`
*   **URL:** `/articles/{article_id}` (e.g., `/articles/1`)
*   **Authentication:** None Required
*   **Input Body:** None
*   **Output (Success: 200 OK):**
    ```json
    {
        "ArticleID": 1,
        "UserID": 3,
        "Title": "أهمية بناء ملف شخصي قوي للخريجين",
        "Description": "... نص المقال هنا يتحدث عن كيفية بناء ملف شخصي جذاب لأصحاب العمل",
        "Date": "2025-04-24T00:00:00.000000Z",
        "Type": "نصائح",
        "Article Photo": null,
        "created_at": "2025-04-29T18:23:08.000000Z",
        "updated_at": "2025-04-29T18:23:08.000000Z",
        "user": {
            "UserID": 3,
            "first_name": "Expert",
            "last_name": "Consultant"
        }
    }
    ```
*   **Output (Failure):**
    *   `404 Not Found`: Article with the given ID does not exist.

### List Jobs

*   **Method:** `GET`
*   **URL:** `/jobs`
*   **Authentication:** None Required
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Paginated List - similar structure to Articles List)
    ```json
    {
        "current_page": 1,
        "data": [
            {
                "JobID": 1,
                "UserID": 2, // User who posted the job
                "Job Title": "Junior Web Developer",
                "Job Description": "Seeking a motivated junior developer to join our team...",
                "Qualification": "Bachelor's degree in CS or related field, basic knowledge of PHP/JS.",
                "Site": "Riyadh (On-site)",
                "Date": "2025-04-22T00:00:00.000000Z",
                "Skills": "PHP, Laravel, JavaScript, MySQL, HTML, CSS",
                "Type": "وظيفة",
                "End Date": "2025-05-29T00:00:00.000000Z",
                "Status": "مفعل",
                "created_at": "2025-04-29T18:23:08.000000Z",
                "updated_at": "2025-04-29T18:23:08.000000Z",
                "user": { // User details (Company Manager)
                    "UserID": 2,
                    "first_name": "Company8",
                    "last_name": "Manager8"
                }
            },
            // ... more jobs
        ],
        // ... pagination links
    }
    ```

### Show Single Job

*   **Method:** `GET`
*   **URL:** `/jobs/{job_id}` (e.g., `/jobs/1`)
*   **Authentication:** None Required
*   **Input Body:** None
*   **Output (Success: 200 OK):**
    ```json
    {
        "JobID": 1,
        "UserID": 2,
        "Job Title": "Junior Web Developer",
        "Job Description": "Seeking a motivated junior developer to join our team...",
        "Qualification": "Bachelor's degree in CS or related field, basic knowledge of PHP/JS.",
        "Site": "Riyadh (On-site)",
        "Date": "2025-04-22T00:00:00.000000Z",
        "Skills": "PHP, Laravel, JavaScript, MySQL, HTML, CSS",
        "Type": "وظيفة",
        "End Date": "2025-05-29T00:00:00.000000Z",
        "Status": "مفعل",
        "created_at": "2025-04-29T18:23:08.000000Z",
        "updated_at": "2025-04-29T18:23:08.000000Z",
        "user": {
            "UserID": 2,
            "first_name": "Company", // Note: Example data inconsistency (Company vs Company8)
            "last_name": "Manager"  // Note: Example data inconsistency (Manager vs Manager8)
        }
    }
    ```
*   **Output (Failure):**
    *   `404 Not Found`: Job with the given ID does not exist.

### List Courses

*   **Method:** `GET`
*   **URL:** `/courses`
*   **Authentication:** None Required
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Paginated List - similar structure to Articles List)
    ```json
    {
        "current_page": 1,
        "data": [
            {
                "CourseID": 1,
                "UserID": 2, // User who created the course
                "Course name": "Introduction to Laravel 11",
                "Trainers name": "Dr. Expert Consultant",
                "Course Description": "A comprehensive introduction to the Laravel framework.",
                "Site": "اونلاين",
                "Trainers Site": "Expert Consulting Platform",
                "Start Date": "2025-05-09T00:00:00.000000Z",
                "End Date": "2025-06-08T00:00:00.000000Z",
                "Enroll Hyper Link": "https://enroll.expert.com/laravel11",
                "Stage": "مبتدئ",
                "Certificate": "يوجد",
                "created_at": "2025-04-29T18:23:09.000000Z",
                "updated_at": "2025-04-29T18:23:09.000000Z",
                "creator": { // User details (Company Manager)
                    "UserID": 2,
                    "first_name": "Company8",
                    "last_name": "Manager8"
                }
            },
            // ... more courses
        ],
        // ... pagination links
    }
    ```

### Show Single Course

*   **Method:** `GET`
*   **URL:** `/courses/{course_id}` (e.g., `/courses/1`)
*   **Authentication:** None Required
*   **Input Body:** None
*   **Output (Success: 200 OK):**
    ```json
    {
        "CourseID": 1,
        "UserID": 2,
        "Course name": "Introduction to Laravel 11",
        "Trainers name": "Dr. Expert Consultant",
        "Course Description": "A comprehensive introduction to the Laravel framework.",
        "Site": "اونلاين",
        "Trainers Site": "Expert Consulting Platform",
        "Start Date": "2025-05-09T00:00:00.000000Z",
        "End Date": "2025-06-08T00:00:00.000000Z",
        "Enroll Hyper Link": "https://enroll.expert.com/laravel11",
        "Stage": "مبتدئ",
        "Certificate": "يوجد",
        "created_at": "2025-04-29T18:23:09.000000Z",
        "updated_at": "2025-04-29T18:23:09.000000Z",
        "creator": {
             "UserID": 2,
             "first_name": "Company", // Note: Example data inconsistency
             "last_name": "Manager"  // Note: Example data inconsistency
         }
    }
    ```
*   **Output (Failure):**
    *   `404 Not Found`: Course with the given ID does not exist.

### List Companies

*   **Method:** `GET`
*   **URL:** `/companies`
*   **Authentication:** None Required
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Paginated List - similar structure to Articles List)
    ```json
    {
        "current_page": 1,
        "data": [
            {
                "CompanyID": 1,
                "UserID": 2, // Associated user ID (Company Manager)
                "Name": "Tech Solutions Inc.",
                "Email": "contact@techsolutions.com",
                "Phone": "0119998877",
                "Description": "Leading IT solutions provider in KSA.",
                "Country": "Saudi Arabia",
                "City": "Riyadh",
                "Detailed Address": "123 Tech Street, Olaya",
                "Media": null,
                "Web site": "https://techsolutions.com",
                "status": "pending", // or 'approved'
                "created_at": "2025-04-29T18:23:08.000000Z",
                "updated_at": "2025-05-03T07:27:44.000000Z"
             }
             // ... more companies
        ],
        // ... pagination links
    }
    ```

### Show Single Company

*   **Method:** `GET`
*   **URL:** `/companies/{company_id}` (e.g., `/companies/1`)
*   **Authentication:** None Required
*   **Input Body:** None
*   **Output (Success: 200 OK):**
    ```json
     {
         "CompanyID": 1,
         "UserID": 2,
         "Name": "Tech Solutions Inc.",
         "Email": "contact@techsolutions.com",
         "Phone": "555123456", // Note: Phone differs from list example
         "Description": "A leading company in software development.",
         "Country": "Saudi Arabia",
         "City": "Riyadh",
         "Detailed Address": "123 Tech Street, Olaya",
         "Media": null,
         "Web site": "https://techsolutions.com",
         "status": "pending",
         "created_at": "2025-04-29T18:23:08.000000Z",
         "updated_at": "2025-04-29T18:23:08.000000Z" // Note: updated_at differs
     }
     ```
*   **Output (Failure):**
    *   `404 Not Found`: Company with the given ID does not exist.

### List Skills

*   **Method:** `GET`
*   **URL:** `/skills`
*   **Query Parameter (Optional):** `search=term` (e.g., `/skills?search=PHP`)
*   **Authentication:** None Required
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Array of Skills)
    ```json
    [
        { "SkillID": 8, "Name": "Communication" },
        { "SkillID": 11, "Name": "Flutter" },
        { "SkillID": 13, "Name": "Git" },
        { "SkillID": 3, "Name": "JavaScript" },
        { "SkillID": 2, "Name": "Laravel" },
        { "SkillID": 6, "Name": "MySQL" },
        { "SkillID": 1, "Name": "PHP" },
        { "SkillID": 7, "Name": "Problem Solving" },
        { "SkillID": 10, "Name": "Project Management" },
        { "SkillID": 5, "Name": "React" },
        { "SkillID": 12, "Name": "REST API Design" },
        { "SkillID": 9, "Name": "Teamwork" },
        { "SkillID": 4, "Name": "Vue.js" }
    ]
    ```
    *(Note: If `search` parameter is used, the list will be filtered)*

---

## Authenticated User Endpoints (Requires Bearer Token)

### List Groups

*   **Method:** `GET`
*   **URL:** `/groups`
*   **Authentication:** Bearer Token Required
*   **Input Body:** None
*   **Output (Success: 200 OK):**
    ```json
    [
        {
            "GroupID": 1,
            "Telegram Hyper Link": "https://t.me/laravel_developers_ksa"
        },
        {
            "GroupID": 2,
            "Telegram Hyper Link": "https://t.me/flutter_devs_middle_east"
        },
        {
            "GroupID": 3,
            "Telegram Hyper Link": "https://t.me/job_opportunities_tech"
        }
    ]
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.

### Show Single Group

*   **Method:** `GET`
*   **URL:** `/groups/{group_id}` (e.g., `/groups/1`)
*   **Authentication:** Bearer Token Required
*   **Input Body:** None
*   **Output (Success: 200 OK):**
    ```json
    {
        "GroupID": 1,
        "Telegram Hyper Link": "https://t.me/laravel_developers_ksa"
    }
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.
    *   `404 Not Found`: Group with the given ID does not exist.

### Show User Profile

*   **Method:** `GET`
*   **URL:** `/profile`
*   **Authentication:** Bearer Token Required
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Example for a graduate user)
    ```json
    {
        "ProfileID": 1,
        "UserID": 4,
        "University": "King Saud University",
        "GPA": "4.5",
        "Personal Description": "Enthusiastic computer science graduate seeking opportunities.",
        "Technical Description": "Proficient in web development technologies.",
        "Git Hyper Link": "https://github.com/graduatestudent",
        "created_at": "2025-04-29T18:23:08.000000Z",
        "updated_at": "2025-04-29T18:23:08.000000Z"
    }
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.
    *   `404 Not Found`: Profile not found for the user (might happen if registration failed mid-way, though unlikely with current setup).

### Update User Profile

*   **Method:** `PUT`
*   **URL:** `/profile`
*   **Authentication:** Bearer Token Required
*   **Input Body:** (Send only the attributes you want to update)
    ```json
    {
        "University": "MIT",
        "Personal Description": "Highly motivated individual seeking challenges."
        // "GPA": "4.6",
        // "Technical Description": "Updated tech description.",
        // "Git Hyper Link": "https://github.com/newlink"
    }
    ```
*   **Output (Success: 200 OK):** (Returns the updated profile)
    ```json
    {
        "ProfileID": 1,
        "UserID": 4,
        "University": "MIT", // Updated
        "GPA": "4.5", // Not updated in this example
        "Personal Description": "Highly motivated individual seeking challenges.", // Updated
        "Technical Description": "Proficient in web development technologies.", // Not updated
        "Git Hyper Link": "https://github.com/graduatestudent", // Not updated
        "created_at": "2025-04-29T18:23:08.000000Z",
        "updated_at": "2025-05-02T15:30:04.000000Z" // Updated timestamp
    }
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.
    *   `422 Unprocessable Entity`: Validation errors on input data.

### Update User Skills

*   **Method:** `POST` (*Note: This might typically be a PUT or PATCH, but the doc says POST*)
*   **URL:** `/profile/skills`
*   **Authentication:** Bearer Token Required
*   **Input Body:** (Array of Skill IDs the user possesses)
    ```json
    {
        "skills": [1, 5, 10] // Skill IDs for PHP, React, Project Management
    }
    ```
*   **Output (Success: 200 OK):** (Returns the user object with updated skills pivot data)
    ```json
    {
        "UserID": 4,
        "first_name": "Graduate",
        "last_name": "Student",
        // ... other user fields
        "skills": [
            {
                "SkillID": 5,
                "Name": "React",
                "pivot": { // Pivot table data
                    "UserID": 4,
                    "SkillID": 5,
                    "Stage": "متقدم" // Example - Stage might be set elsewhere or default
                }
            },
            {
                "SkillID": 1,
                "Name": "PHP",
                "pivot": {
                    "UserID": 4,
                    "SkillID": 1,
                    "Stage": null // Example - Stage might be null
                }
            },
            {
                "SkillID": 10,
                "Name": "Project Management",
                "pivot": {
                    "UserID": 4,
                    "SkillID": 10,
                    "Stage": null
                }
            }
        ]
    }
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.
    *   `422 Unprocessable Entity`: Invalid input (e.g., non-existent skill IDs, incorrect format).

### List My Job Applications

*   **Method:** `GET`
*   **URL:** `/my-applications`
*   **Authentication:** Bearer Token Required
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Array of applications)
    ```json
    [
        {
            "ID": 2, // Application ID
            "UserID": 4,
            "JobID": 2,
            "Status": "Rejected", // e.g., Pending, Reviewed, Rejected, Accepted
            "Date": "2025-04-27T00:00:00.000000Z", // Application date? Or Job Date? Clarify needed.
            "Description": "Applied via seeder.", // Cover letter/notes
            "CV": "/path/to/default_cv.pdf", // Path to CV file
            "created_at": "2025-04-29T18:23:09.000000Z",
            "updated_at": "2025-04-29T18:23:09.000000Z",
            "job_opportunity": { // Details of the job applied for
                "JobID": 2,
                "Job Title": "Flutter Development Internship",
                "Type": "تدريب"
                // ... potentially other job fields
            }
        },
        {
            "ID": 1,
            "UserID": 4,
            "JobID": 1,
            "Status": "Reviewed",
            "Date": "2025-04-25T00:00:00.000000Z",
            "Description": "Applied via seeder.",
            "CV": "/path/to/default_cv.pdf",
            "created_at": "2025-04-29T18:23:09.000000Z",
            "updated_at": "2025-04-29T18:23:09.000000Z",
            "job_opportunity": {
                "JobID": 1,
                "Job Title": "Junior Web Developer",
                "Type": "وظيفة"
            }
        }
    ]
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.

### Apply for a Job

*   **Method:** `POST`
*   **URL:** `/jobs/{job_id}/apply` (e.g., `/jobs/2/apply`)
*   **Authentication:** Bearer Token Required
*   **Input Body:** (Chosen fields, e.g., CV and cover letter/description)
    ```json
    {
        "Description": "My cover letter notes.",
        "CV": "/path/cv.pdf" // Path/Reference to the uploaded CV
    }
    ```
*   **Output (Success: 201 Created):**
    ```json
    {
        "ID": 3, // New Application ID
        "UserID": 4, // Current User ID
        "JobID": 2, // Job ID applied for
        "Status": "Pending",
        "Date": "2025-05-01TXX:XX:XX.XXXXXXZ", // Timestamp of application
        "Description": "My cover letter notes.",
        "CV": "/path/cv.pdf",
        "created_at": "...",
        "updated_at": "..."
    }
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.
    *   `404 Not Found`: Job ID does not exist.
    *   `409 Conflict` / `400 Bad Request`: Already applied, or missing required fields (CV?).
    *   `422 Unprocessable Entity`: Validation errors.

### Delete Job Application (Withdraw)

*   **Method:** `DELETE`
*   **URL:** `/my-applications/{application_id}` (e.g., `/my-applications/1`)
*   **Authentication:** Bearer Token Required
*   **Input Body:** None
*   **Output (Success: 204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.
    *   `404 Not Found`: Application ID does not exist for this user.
    *   `403 Forbidden`: Cannot delete application (e.g., if already processed/rejected/accepted?).

### List My Course Enrollments

*   **Method:** `GET`
*   **URL:** `/my-enrollments`
*   **Authentication:** Bearer Token Required
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Array of enrollments)
    ```json
    [
        {
            "EnrollmentID": 1,
            "UserID": 4,
            "CourseID": 1,
            "Status": "ملغي", // Cancelled/Withdrawn? Other statuses: 'مكتمل', 'قيد التقدم'
            "Date": "2025-04-05T00:00:00.000000Z", // Enrollment date? Or Course Date?
            "Complet Date": null, // Date of completion
            "created_at": "2025-04-29T18:23:09.000000Z",
            "updated_at": "2025-04-29T18:23:09.000000Z",
            "training_course": { // Details of the enrolled course
                "CourseID": 1,
                "Course name": "Introduction to Laravel 11"
                // ... potentially other course fields
            }
        },
        {
            "EnrollmentID": 2,
            "UserID": 4,
            "CourseID": 2,
            "Status": "مكتمل", // Completed
            "Date": "2025-03-04T00:00:00.000000Z",
            "Complet Date": "2025-04-12T00:00:00.000000Z",
            "created_at": "2025-04-29T18:23:09.000000Z",
            "updated_at": "2025-04-29T18:23:09.000000Z",
            "training_course": {
                "CourseID": 2,
                "Course name": "Advanced Git Techniques"
            }
        }
    ]
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.

### Enroll in a Course

*   **Method:** `POST`
*   **URL:** `/courses/{course_id}/enroll` (e.g., `/courses/1/enroll`)
*   **Authentication:** Bearer Token Required
*   **Input Body:** None (*Assuming no extra data needed for enrollment*)
*   **Output (Success: 201 Created):**
    ```json
    {
        "UserID": 4,
        "CourseID": 1,
        "Status": "قيد التقدم", // In Progress
        "Date": "2025-05-02T00:00:00.000000Z", // Enrollment Date/Timestamp? Course Start Date?
        "Complet Date": null,
        "updated_at": "2025-05-02T16:05:26.000000Z",
        "created_at": "2025-05-02T16:05:26.000000Z",
        "EnrollmentID": 3
    }
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.
    *   `404 Not Found`: Course ID does not exist.
    *   `409 Conflict`: Already enrolled.

### Delete Course Enrollment (Unenroll)

*   **Method:** `DELETE`
*   **URL:** `/my-enrollments/{enrollment_id}` (e.g., `/my-enrollments/1`)
*   **Authentication:** Bearer Token Required
*   **Input Body:** None
*   **Output (Success: 204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.
    *   `404 Not Found`: Enrollment ID does not exist for this user.
    *   `409 Conflict` / `403 Forbidden`: Cannot unenroll (e.g., if course already completed?).

### Show Recommendations

*   **Method:** `GET`
*   **URL:** `/recommendations`
*   **Authentication:** Bearer Token Required
*   **Input Body:** None
*   **Output (Success: 200 OK):**
    ```json
    {
        "recommended_jobs": [
            { // Job object structure, same as GET /jobs/{id}
                "JobID": 1,
                "UserID": 2,
                "Job Title": "Junior Web Developer",
                "Job Description": "Seeking a motivated junior developer to join our team...",
                "Qualification": "Bachelor's degree in CS or related field, basic knowledge of PHP/JS.",
                "Site": "Riyadh (On-site)",
                "Date": "2025-04-22T00:00:00.000000Z",
                "Skills": "PHP, Laravel, JavaScript, MySQL, HTML, CSS",
                "Type": "وظيفة",
                "End Date": "2025-05-29T00:00:00.000000Z",
                "Status": "مفعل",
                "created_at": "2025-04-29T18:23:08.000000Z",
                "updated_at": "2025-04-29T18:23:08.000000Z",
                "user": {
                    "UserID": 2,
                    "first_name": "Company", // Inconsistency noted before
                    "last_name": "Manager"
                 }
            }
            // ... potentially more recommended jobs
        ],
        "recommended_courses": [
            // Course object structure, same as GET /courses/{id}
            // ... potentially recommended courses (example shows empty array)
        ]
    }
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.

---

## Company Manager Routes

**Base URL Prefix:** `/company-manager` (e.g., `/company-manager/company`)
**Authentication:** Bearer Token Required (for a user with 'Company Manager' type)

### Show Company Data

*   **Method:** `GET`
*   **URL:** `/company-manager/company`
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Company details associated with the manager)
    ```json
    {
        "CompanyID": 1,
        "UserID": 2,
        "Name": "Tech Solutions Inc.",
        "Email": "contact@techsolutions.com",
        "Phone": "555123456", // Example inconsistency
        "Description": "A leading company in software development.",
        "Country": "Saudi Arabia",
        "City": "Riyadh",
        "Detailed Address": "123 Tech Street, Olaya",
        "Media": null,
        "Web site": "https://techsolutions.com",
        "status": "pending",
        "created_at": "2025-04-29T18:23:08.000000Z",
        "updated_at": "2025-04-29T18:23:08.000000Z" // Example inconsistency
    }
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`: Invalid or missing token.
    *   `403 Forbidden`: User is not a Company Manager.
    *   `404 Not Found`: No company associated with this manager.

### Update Company Data

*   **Method:** `PUT`
*   **URL:** `/company-manager/company`
*   **Input Body:** (Send only the fields to update)
    ```json
    {
        "Phone": "0119998877",
        "Description": "Leading IT solutions provider in KSA."
        // ... other fields like Name, Email, Address, etc.
    }
    ```
*   **Output (Success: 200 OK):** (Returns the updated company data)
    ```json
    {
        "CompanyID": 1,
        "UserID": 2,
        "Name": "Tech Solutions Inc.",
        "Email": "contact@techsolutions.com",
        "Phone": "0119998877", // Updated
        "Description": "Leading IT solutions provider in KSA.", // Updated
        "Country": "Saudi Arabia",
        "City": "Riyadh",
        "Detailed Address": "123 Tech Street, Olaya",
        "Media": null,
        "Web site": "https://techsolutions.com",
        "status": "pending",
        "created_at": "2025-04-29T18:23:08.000000Z",
        "updated_at": "2025-05-03T07:27:44.000000Z" // Updated timestamp
    }
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`/`404 Not Found` (as above).
    *   `422 Unprocessable Entity`: Validation errors.

### Manage Job Opportunities (CRUD)

*   **Resource URL:** `/company-manager/jobs`
*   **Uses `apiResources` pattern:**
    *   `GET /company-manager/jobs`: List jobs created by this company manager. (Output: Paginated list, similar structure to public `/jobs` but filtered)
    *   `POST /company-manager/jobs`: Create a new job posting.
        *   **Input:**
            ```json
            {
                "Job Title": "Frontend Developer",
                "Job Description": "React expert needed.",
                "Qualification": "Bachelor's degree, 2+ years experience.",
                "Site": "Jeddah",
                "Date": "2025-05-01", // Publish Date
                "Skills": "React, JavaScript, HTML, CSS, Git",
                "Type": "وظيفة",
                "End Date": "2025-06-01", // Application Deadline
                "Status": "مفعل" // Or 'غير مفعل' (inactive)
            }
            ```
        *   **Output (201 Created):** The created job object with assigned `JobID` and `UserID`.
    *   `GET /company-manager/jobs/{job_id}`: Show details of a specific job created by this manager. (Output: Single job object)
    *   `PUT /company-manager/jobs/{job_id}`: Update a job posting.
        *   **Input:** Fields to update (e.g., `{ "Job Title": "Frontend Developer (Senior)" }`)
        *   **Output (200 OK):** Updated job object.
    *   `DELETE /company-manager/jobs/{job_id}`: Delete a job posting.
        *   **Output (204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Job ID not found or doesn't belong to this manager.
    *   `422 Unprocessable Entity`: Validation errors (POST/PUT).

### Manage Course Opportunities (CRUD)

*   **Resource URL:** `/company-manager/courses`
*   **Uses `apiResources` pattern (similar to Jobs):**
    *   `GET /company-manager/courses`: List courses created by this manager.
    *   `POST /company-manager/courses`: Create a new course.
        *   **Input:**
            ```json
            {
                "Course name": "Introduction to Laravel 12",
                "Trainers name": "Dr. Expert Consultant",
                "Course Description": "A comprehensive introduction to the Laravel framework.",
                "Site": "اونلاين",
                "Trainers Site": "Expert Consulting Platform",
                "Start Date": "2025-05-09",
                "End Date": "2025-06-08",
                "Enroll Hyper Link": "https://enroll.expert.com/laravel12",
                "Stage": "مبتدئ",
                "Certificate": "يوجد" // Or 'لا يوجد'
            }
            ```
        *   **Output (201 Created):** Created course object with `CourseID`.
    *   `GET /company-manager/courses/{course_id}`: Show details of a specific course. (*Doc incorrectly shows `/jobs/1` URL here*)
    *   `PUT /company-manager/courses/{course_id}`: Update a course. (*Doc incorrectly shows `/jobs/3` URL*)
        *   **Input:** Fields to update (e.g., `{ "Trainers name": "Dr. Abdulhady" }`)
        *   **Output (200 OK):** Updated course object.
    *   `DELETE /company-manager/courses/{course_id}`: Delete a course. (*Doc incorrectly shows `/jobs/3` URL*)
        *   **Output (204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Course ID not found or doesn't belong to this manager.
    *   `422 Unprocessable Entity`: Validation errors (POST/PUT).

### View Job Applicants

*   **Method:** `GET`
*   **URL:** `/company-manager/jobs/{job_id}/applicants` (e.g., `/company-manager/jobs/2/applicants`)
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Array of applicant details for the specified job)
    ```json
    [
        {
            "ID": 2, // Application ID
            "UserID": 4, // Applicant User ID
            "JobID": 2,
            "Status": "Rejected", // Status of this specific application
            "Date": "2025-04-27T00:00:00.000000Z",
            "Description": "Applied via seeder.", // Applicant's cover letter/notes
            "CV": "/path/to/default_cv.pdf",
            "created_at": "2025-04-29T18:23:09.000000Z",
            "updated_at": "2025-04-29T18:23:09.000000Z",
            "user": { // Applicant's user details
                "UserID": 4,
                "first_name": "Graduate",
                "last_name": "Student",
                "email": "graduate@student.com",
                "phone": "444444444",
                "profile": { // Applicant's profile details
                    "ProfileID": 1,
                    "UserID": 4,
                    "University": "MIT",
                    "GPA": "4.5",
                    "Personal Description": "Highly motivated individual seeking challenges.",
                    "Technical Description": "Proficient in web development technologies.",
                    "Git Hyper Link": "https://github.com/graduatestudent",
                    "created_at": "2025-04-29T18:23:08.000000Z",
                    "updated_at": "2025-05-02T15:30:04.000000Z"
                }
            }
        }
        // ... more applicants
    ]
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Job ID not found or doesn't belong to this manager.

### View Course Enrollees

*   **Method:** `GET`
*   **URL:** `/company-manager/courses/{training_course}/enrollees` (e.g., `/company-manager/courses/1/enrollees`)
*   **Input Body:** None
*   **Output (Success: 200 OK):** (Array of enrollee details)
    ```json
    [
        {
            "EnrollmentID": 3,
            "UserID": 4,
            "CourseID": 1,
            "Status": "قيد التقدم", // Status of this enrollment
            "Date": "2025-05-02T00:00:00.000000Z",
            "Complet Date": null,
            "created_at": "2025-05-02T16:05:26.000000Z",
            "updated_at": "2025-05-02T16:05:26.000000Z",
            "user": { // Enrollee's user details
                "UserID": 4,
                "first_name": "Graduate",
                "last_name": "Student",
                "email": "graduate@student.com",
                "phone": "444444444",
                "profile": { // Enrollee's profile details
                    "ProfileID": 1,
                    "UserID": 4,
                    "University": "MIT",
                    "GPA": "4.5",
                    "Personal Description": "Highly motivated individual seeking challenges.",
                    "Technical Description": "Proficient in web development technologies.",
                    "Git Hyper Link": "https://github.com/graduatestudent",
                    "created_at": "2025-04-29T18:23:08.000000Z",
                    "updated_at": "2025-05-02T15:30:04.000000Z"
                }
            }
        }
        // ... more enrollees
    ]
    ```
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Course ID not found or doesn't belong to this manager.

---

## Consultant Routes

**Base URL Prefix:** `/consultant` (e.g., `/consultant/articles`)
**Authentication:** Bearer Token Required (for a user with 'Consultant' or 'Expert' type)

### Manage Articles (CRUD)

*   **Resource URL:** `/consultant/articles`
*   **Uses `apiResources` pattern:**
    *   `GET /consultant/articles`: List articles created by this consultant. (Output: Paginated list, same structure as public `/articles` but filtered)
    *   `POST /consultant/articles`: Create a new article.
        *   **Input:**
            ```json
            {
                "Title": "أهمية بناء ملف شخصي قوي للخريجين الجديدين",
                "Description": "... نص المقال هنا يتحدث عن كيفية بناء ملف شخصي جذاب لأصحاب العمل",
                "Date": "2025-04-24T00:00:00.000000Z", // Publish date
                "Type": "نصائح", // Category/Type
                "Article Photo": null // Optional path to photo
            }
            ```
        *   **Output (201 Created):** Created article object with `ArticleID`.
    *   `GET /consultant/articles/{article_id}`: Show details of a specific article created by this consultant. (Output: Single article object)
    *   `PUT /consultant/articles/{article_id}`: Update an article.
        *   **Input:** Fields to update (e.g., `{ "Title": "أهمية بناء ملف شخصي قوي للخريجين القدماء" }`)
        *   **Output (200 OK):** Updated article object.
    *   `DELETE /consultant/articles/{article_id}`: Delete an article.
        *   **Output (204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Article ID not found or doesn't belong to this consultant.
    *   `422 Unprocessable Entity`: Validation errors (POST/PUT).

---

## Admin Routes

**Base URL Prefix:** `/admin` (e.g., `/admin/users`)
**Authentication:** Bearer Token Required (for a user with 'Admin' type)

*(Note: Many admin endpoints mirror public or other role endpoints but operate on ALL data, not just the current user's or manager's data. CRUD operations are often implied by `apiResources`)*

### Manage Users (CRUD - No POST from doc, but implied)

*   **Resource URL:** `/admin/users`
*   **Methods:**
    *   `GET /admin/users`: List all users. (Output: Paginated list of user objects, includes profile/company details if available)
    *   `POST /admin/users`: Create a new user. (*Not explicitly in doc, but standard for CRUD*)
        *   **Input:**
            ```json
            {
                "first_name": "Admin",
                "last_name": "Test",
                "username": "admintest",
                "email": "admintest@example.com",
                "password": "password123", // No confirmation needed here? Doc implies yes.
                "phone": "0598765432",
                "type": "Admin", // Or Graduate, Company Manager, Consultant
                "status": "مفعل", // Or 'غير مفعل'
                "email_verified": true // Or false
            }
            ```
        *   **Output (201 Created):** Created user object.
    *   `GET /admin/users/{user_id}`: Show details of a specific user. (Output: Single user object with profile/company)
    *   `PUT /admin/users/{user_id}`: Update a user.
        *   **Input:** Fields to update (e.g., `{ "first_name": "Company8", "last_name": "Manager8" }`)
        *   **Output (200 OK):** Updated user object.
    *   `DELETE /admin/users/{user_id}`: Delete a user.
        *   **Output (204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: User ID not found.
    *   `422 Unprocessable Entity`: Validation errors (POST/PUT).

### Manage Skills (CRUD)

*   **Resource URL:** `/admin/skills`
*   **Uses `apiResources` pattern:**
    *   `GET /admin/skills`: List all skills. (Output: Array of skill objects)
    *   `POST /admin/skills`: Create a new skill.
        *   **Input:** `{"Name": "Jira"}`
        *   **Output (201 Created):** `{"Name": "Jira", "SkillID": 14}`
    *   `GET /admin/skills/{skill_id}`: Show a specific skill. (Output: Single skill object)
    *   `PUT /admin/skills/{skill_id}`: Update a skill.
        *   **Input:** `{"Name": "JIRA"}`
        *   **Output (200 OK):** `{"Name": "JIRA", "SkillID": 14}`
    *   `DELETE /admin/skills/{skill_id}`: Delete a skill.
        *   **Output (204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Skill ID not found.
    *   `422 Unprocessable Entity`: Validation errors (POST/PUT - e.g., duplicate name).

### Manage Groups (CRUD)

*   **Resource URL:** `/admin/groups`
*   **Uses `apiResources` pattern:**
    *   `GET /admin/groups`: List all groups. (Output: Array of group objects)
    *   `POST /admin/groups`: Create a new group.
        *   **Input:** `{"Telegram Hyper Link": "https://t.me/laravel_developers_ksa_sy"}`
        *   **Output (201 Created):** Created group object with `GroupID`.
    *   `GET /admin/groups/{group_id}`: Show a specific group. (Output: Single group object)
    *   `PUT /admin/groups/{group_id}`: Update a group.
        *   **Input:** `{"Telegram Hyper Link": "https://t.me/laravel_developers_ksa_sy_qa"}`
        *   **Output (200 OK):** Updated group object.
    *   `DELETE /admin/groups/{group_id}`: Delete a group.
        *   **Output (204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Group ID not found.
    *   `422 Unprocessable Entity`: Validation errors (POST/PUT).

### Manage Companies (CRUD - POST/PUT/DELETE inferred)

*   **Resource URL:** `/admin/companies`
*   **Methods:**
    *   `GET /admin/companies`: List all companies. (Output: Paginated list)
    *   `POST /admin/companies`: Create a new company profile.
        *   **Input:** (Requires associating with a UserID, likely a Company Manager)
            ```json
            {
                "UserID": 1, // User ID of the Company Manager
                "Name": "Tech Solutions Incs.", // Note 's' added in example
                "Email": "contact@techsolutions.com",
                "Phone": "0119998877",
                "Description": "Leading IT solutions provider in KSA.",
                "Country": "Saudi Arabia",
                "City": "madina",
                "Detailed Address": "123 Tech Street, Olaya",
                "Media": null,
                "Web site": "https://techsolutions.com",
                "status": "pending" // Admin might create directly as 'approved'
            }
            ```
        *   **Output (201 Created):** Created company object with `CompanyID`.
    *   `GET /admin/companies/{company_id}`: Show a specific company. (Output: Single company object)
    *   `PUT /admin/companies/{company_id}`: Update a company.
        *   **Input:** Fields to update (e.g., `{"Country": "syiria", "City": "hama"}`)
        *   **Output (200 OK):** Updated company object.
    *   `DELETE /admin/companies/{company_id}`: Delete a company.
        *   **Output (204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Company ID not found.
    *   `422 Unprocessable Entity`: Validation errors.

### Manage Articles (Admin - CRUD)

*   **Resource URL:** `/admin/articles`
*   **Uses `apiResources` pattern:**
    *   `GET /admin/articles`: List all articles from all users. (Output: Paginated list)
    *   `POST /admin/articles`: Create a new article (requires assigning a `UserID`).
        *   **Input:** (Similar to Consultant POST, but must include `UserID`)
            ```json
            {
                "UserID": 3, // ID of the user credited for the article
                "Title": "أهمية بناء ملف شخصي قوي للخريجين الجديدين",
                "Description": "... نص المقال هنا يتحدث عن كيفية بناء ملف شخصي جذاب لأصحاب العمل",
                "Date": "2025-04-24T00:00:00.000000Z",
                "Type": "نصائح",
                "Article Photo": null
            }
            ```
        *   **Output (201 Created):** Created article object.
    *   `GET /admin/articles/{article_id}`: Show any article by ID. (Output: Single article object)
    *   `PUT /admin/articles/{article_id}`: Update any article.
        *   **Input:** Fields to update (Can include `UserID` if reassigning).
        *   **Output (200 OK):** Updated article object.
    *   `DELETE /admin/articles/{article_id}`: Delete any article.
        *   **Output (204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Article ID not found.
    *   `422 Unprocessable Entity`: Validation errors (e.g., invalid `UserID`).

### Manage Jobs (Admin - CRUD)

*   **Resource URL:** `/admin/jobs`
*   **Uses `apiResources` pattern:**
    *   `GET /admin/jobs`: List all jobs from all companies. (Output: Paginated list)
    *   `POST /admin/jobs`: Create a new job (requires assigning a company manager `UserID`).
        *   **Input:** (Similar to Company Manager POST, but must include `UserID`)
            ```json
            {
                 "UserID": 2, // ID of the company manager posting the job
                 "Job Title": "Web Developer",
                 "Job Description": "Seeking a motivated junior developer to join our team...",
                 "Qualification": "Bachelor's degree in CS or related field, basic knowledge of PHP/JS.",
                 "Site": "Riyadh (On-site)",
                 "Date": "2025-04-22T00:00:00.000000Z",
                 "Skills": "PHP, Laravel, JavaScript, MySQL, HTML, CSS",
                 "Type": "وظيفة",
                 "End Date": "2025-05-29T00:00:00.000000Z",
                 "Status": "مفعل"
            }
            ```
        *   **Output (201 Created):** Created job object.
    *   `GET /admin/jobs/{job_id}`: Show any job by ID. (Output: Single job object)
    *   `PUT /admin/jobs/{job_id}`: Update any job.
        *   **Input:** Fields to update.
        *   **Output (200 OK):** Updated job object.
    *   `DELETE /admin/jobs/{job_id}`: Delete any job.
        *   **Output (204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Job ID not found.
    *   `422 Unprocessable Entity`: Validation errors.

### Manage Courses (Admin - CRUD)

*   **Resource URL:** `/admin/courses`
*   **Uses `apiResources` pattern:**
    *   `GET /admin/courses`: List all courses from all companies. (Output: Paginated list)
    *   `POST /admin/courses`: Create a new course (requires assigning a company manager `UserID`).
        *   **Input:** (Similar to Company Manager POST, but needs `UserID`)
            ```json
             {
                 "UserID": 2, // ID of the company manager creating the course
                 "Course name": "Introduction to Laravel 12",
                 // ... other course fields
             }
             ```
        *   **Output (201 Created):** Created course object.
    *   `GET /admin/courses/{course_id}`: Show any course by ID. (Output: Single course object)
    *   `PUT /admin/courses/{course_id}`: Update any course.
        *   **Input:** Fields to update.
        *   **Output (200 OK):** Updated course object.
    *   `DELETE /admin/courses/{course_id}`: Delete any course.
        *   **Output (204 No Content):**
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Course ID not found.
    *   `422 Unprocessable Entity`: Validation errors.

### Manage Company Requests

*   **List Pending Requests:**
    *   **Method:** `GET`
    *   **URL:** `/admin/company-requests`
    *   **Input Body:** None
    *   **Output (Success: 200 OK):** (Paginated list of companies with `status: "pending"`)
        ```json
        {
            "current_page": 1,
            "data": [
                { // Company object structure for pending companies
                    "CompanyID": 1,
                    "UserID": 2,
                    "Name": "Tech Solutions Inc.",
                    // ... other company fields
                    "status": "pending",
                    // ... timestamps
                    "user": { // Details of the manager who submitted
                        "UserID": 2,
                        "first_name": "Company8", // Example inconsistency
                        "last_name": "Manager8",
                        "email": "manager@company.com"
                    }
                }
                // ... more pending requests
            ],
            // ... pagination links
        }
        ```
*   **Approve Company Request:**
    *   **Method:** `PUT`
    *   **URL:** `/admin/company-requests/{company_id}/approve` (e.g., `/admin/company-requests/1/approve`)
    *   **Input Body:** None
    *   **Output (Success: 200 OK):**
        ```json
        {
            "message": "Company approved successfully.",
            "company": { // Company object with status updated to 'approved' (or similar)
                "CompanyID": 1,
                // ... other fields
                "status": "approved" // Status should change
                // ...
            }
        }
        ```
*   **Reject Company Request:**
    *   **Method:** `PUT`
    *   **URL:** `/admin/company-requests/{company_id}/reject` (e.g., `/admin/company-requests/1/reject`)
    *   **Input Body:** None
    *   **Output (Success: 200 OK):**
        ```json
        {
            "message": "Company rejected successfully.",
            "company": { // Company object with status updated to 'rejected' (or remains pending/deleted?)
                "CompanyID": 1,
                // ... other fields
                "status": "rejected" // Or maybe status remains pending, or company deleted? Clarify needed.
                // ...
            }
        }
        ```
*   **Output (Failure):**
    *   `401 Unauthorized`/`403 Forbidden`.
    *   `404 Not Found`: Company ID not found or not pending.

```








